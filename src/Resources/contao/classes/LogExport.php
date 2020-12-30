<?php

/**
 * Extension for Contao 4
 *
 * @copyright  Softleister 2020
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
*/

namespace Softleister\Timetracker;

use Softleister\Timetracker\timetrackerTools;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

//-----------------------------------------------------------------
//  LogExport:    Exportklasse
//-----------------------------------------------------------------
class LogExport extends \Backend
{
    //-----------------------------------------------------------------
    //  Function compile
    //-----------------------------------------------------------------
    public function exportLog()
    {
		if( \Input::get('key') != 'export' ) {
			return '';
        }
        
        timetrackerTools::getSettings( true );          // Settings laden

		/** @var Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $objSessionBag */
		$objSessionBag = \Contao\System::getContainer()->get('session')->getBag('contao_backend');
		$filter = $objSessionBag->get('filter');

		// Return the current category
        $kundeID = $filter['tl_timetracker_log']['kunde'] ?? '';
        if( $kundeID == '' ) return 'Kein Kunde ausgewählt.';

        $summe = ['gesamt' => 0, 'berechnet' => 0 ];
        $objLog = $this->Database->prepare( "SELECT * FROM tl_timetracker_log WHERE kunde=? ORDER BY datum DESC")
                                 ->limit( 200 )
                                 ->execute( $kundeID );
        if( $objLog->numRows < 1 ) return 'Keine Zeiten abzurechnen.';

        $datei = TL_ROOT . '/system/tmp/Zeitnachweis_' . \Contao\System::getContainer()->get('contao.slug')->generate( $GLOBALS['TIMETRACKER']['KUNDEN'][$kundeID]['kundenname'], $kundeID ) . '_' . date('my') . '.xlsx';

        // Excel aufbauen
        $styleArray = [
            'borders' => [
                'horizontal' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'E0E0E0E0'],
                ],
            ],
        ];
        
        $spreadsheet = new Spreadsheet( );
        $spreadsheet->getProperties()->setTitle( basename( $datei ) );
        $sheet = $spreadsheet->getActiveSheet( );

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);
        $sheet->getHeaderFooter()->setOddFooter('&L&B' . $spreadsheet->getProperties()->getTitle() . '&C' . \Environment::get('host') . '&RSeite &P von &N');

        // Überschriften
        $sheet->setCellValue( 'A1', $GLOBALS['TIMETRACKER']['KUNDEN'][$kundeID]['kundenname'] );
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setSize(24);
        $sheet->getRowDimension('1')->setRowHeight(24);
        $sheet->setCellValue( 'A2', 'Kundennr. ' . $GLOBALS['TIMETRACKER']['KUNDEN'][$kundeID]['kundennr'] );
        $sheet->mergeCells('A2:D2');

        $sheet->setCellValue( 'A4', 'Datum' );
        $sheet->setCellValue( 'B4', 'Dauer' );
        $sheet->setCellValue( 'C4', 'Tätigkeit' );
        $sheet->setCellValue( 'D4', 'Bemerkungen' );
        $sheet->getStyle('A4:D4')->getFont()->setBold(true);

        $line = 5;
        while( $objLog->next() ) {
            if( in_array( $objLog->aufgabe, $GLOBALS['TIMETRACKER']['CALCSTOP'] ) && ($objLog->nostop != 1) ) break;    // bei ## Abrechnung ## beenden
            if( in_array( $objLog->aufgabe, $GLOBALS['TIMETRACKER']['NOLIST'] ) ) continue;                             // Eintrag nicht listen

            $arrDauer = \StringUtil::trimsplit( ':', $objLog->dauer );
            $dauer = ($arrDauer[1] * 60) + ($arrDauer[0] * 60 * 60);                        // Dauer in Sekunden
            $summe['gesamt'] += $dauer;
            if( $objLog->noinvoice != '1' ) $summe['berechnet'] += $dauer;

            $content = strip_tags( $objLog->beschreibung );

            $sheet->setCellValue( 'A' . $line, date('d.m.Y', $objLog->datum) );
            $sheet->setCellValue( 'B' . $line, sprintf( '%02d:%02d', floor($dauer/3600), round(($dauer/60)%60) ) );
            $sheet->setCellValue( 'C' . $line, $content );
            $sheet->setCellValue( 'D' . $line, $objLog->noinvoice == 1 ? 'ohne Berechnung' : '' );
            
            $sheet->getStyle('A' . $line . ':D' . $line)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            if( $objLog->noinvoice == 1 ) $sheet->getStyle('A' . $line . ':D' . $line)->getFont()->getColor()->setRGB('808080');
            $zeilen = substr_count( $content, "\n" ) + 1;
            $sheet->getRowDimension($line)->setRowHeight( (12.5 * $zeilen) + 2);
            $line++;
        }
        $line++;

        $sheet->getStyle('A4:B' . $line)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:D' . ($line-1))->applyFromArray( $styleArray );

        // Summe
        $sheet->setCellValue( 'B' . $line, sprintf( '%02d:%02d', floor($summe['berechnet']/3600), round( ($summe['berechnet']/60)%60, 1) ) );
        $sheet->setCellValue( 'C' . $line, 'Summe (entspricht ' . str_replace('.', ',', round( $summe['berechnet']/3600, 1)) . ' Stunden)' );
        $sheet->getStyle('B' . $line . ':C' . $line)->getFont()->setBold(true);
        
        for( $i = 'A'; $i <= 'D'; $i++ ) $sheet->getColumnDimension($i)->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(false);
        $sheet->getColumnDimension('C')->setWidth(90);

        $writer = new Xlsx( $spreadsheet );
        $writer->save( $datei );                                                            // temporäre Datei speichern

        // Set the content-type:
        header( 'Pragma: public' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Cache-Control: private', false );
        header( 'Content-Type: application/vnd.ms-excel' );
        header( 'Content-disposition: attachment; filename=' . basename( $datei ) );
        header( 'Content-Length: ' . filesize( $datei ) );
        header( 'Content-Transfer-Encoding: binary' );

        readfile( $datei );                                                                  // Datei senden
        unlink( $datei );                                                                    // temporäre Datei löschen
        exit;
    }


    //-----------------------------------------------------------------
}


// log_message( __METHOD__ . ' - objKunde=' . print_r( $objKunde->row(), 1 ), 'sl_debug.log' );
