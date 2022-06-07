<?php

/**
 * Extension for Contao 4
 *
 * @copyright  Softleister 2020-2022
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
*/

namespace Softleister\Timetracker;

use Contao\Backend;
use Contao\Input;
use Contao\System;
use Contao\Environment;
use Contao\StringUtil;
use Softleister\Timetracker\timetrackerTools;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

//-----------------------------------------------------------------
//  LogExport:    Exportklasse
//-----------------------------------------------------------------
class LogExport extends Backend
{
    //-----------------------------------------------------------------
    //  Function compile
    //-----------------------------------------------------------------
    public function exportLog()
    {
        if( Input::get('key') != 'export' ) {
            return '';
        }
        
        timetrackerTools::getSettings( true );          // Settings laden

        /** @var Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $objSessionBag */
        $objSessionBag = System::getContainer()->get('session')->getBag('contao_backend');
        $filter = $objSessionBag->get('filter');

        // Return the current category
        $kundeID = $filter['tl_timetracker_log']['kunde'] ?? '';
        if( $kundeID == '' ) return 'Kein Kunde ausgewählt.';

        $summe = ['gesamt' => 0, 'berechnet' => 0 ];
        $objLog = $this->Database->prepare( "SELECT * FROM tl_timetracker_log WHERE kunde=? ORDER BY datum DESC")
                                 ->limit( 200 )
                                 ->execute( $kundeID );
        if( $objLog->numRows < 1 ) return 'Keine Zeiten abzurechnen.';

        $datei = TL_ROOT . '/system/tmp/Zeitnachweis_' . System::getContainer()->get('contao.slug')->generate( $GLOBALS['TIMETRACKER']['KUNDEN'][$kundeID]['kundenname'], $kundeID ) . '_' . date('my') . '.xlsx';

        // Excel aufbauen
        $styleArray = [
            'borders' => [
                'horizontal' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'E0E0E0E0'],
                ],
            ],
        ];
        
        $spreadsheet = new Spreadsheet( );
        $spreadsheet->getProperties()->setTitle( basename( $datei ) );
        $sheet = $spreadsheet->getActiveSheet( );

        $sheet->getPageSetup()->setOrientation( PageSetup::ORIENTATION_LANDSCAPE );
        $sheet->getPageSetup()->setPaperSize( PageSetup::PAPERSIZE_A4 );
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);
        $sheet->getHeaderFooter()->setOddFooter('&L&B' . $spreadsheet->getProperties()->getTitle() . '&C' . Environment::get('host') . '&RSeite &P von &N');

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

            $arrDauer = StringUtil::trimsplit( ':', $objLog->dauer );
            $dauer = ($arrDauer[1] * 60) + ($arrDauer[0] * 60 * 60);                        // Dauer in Sekunden
            $summe['gesamt'] += $dauer;
            if( $objLog->noinvoice != '1' ) $summe['berechnet'] += $dauer;

            $content = str_replace( '<br>', "\n", strip_tags( $objLog->beschreibung, '<br>' ) );

            $sheet->setCellValue( 'A' . $line, date('d.m.Y', $objLog->datum) );
            $sheet->setCellValue( 'B' . $line, sprintf( '%02d:%02d', floor($dauer/3600), round(($dauer/60)%60) ) );
            $sheet->setCellValue( 'C' . $line, $content );
            $sheet->setCellValue( 'D' . $line, $objLog->noinvoice == 1 ? 'ohne Berechnung' : '' );
            
            $sheet->getStyle('A' . $line . ':D' . $line)->getAlignment()->setVertical( Alignment::VERTICAL_TOP );
            if( $objLog->noinvoice == 1 ) $sheet->getStyle('A' . $line . ':D' . $line)->getFont()->getColor()->setRGB('808080');
            $zeilen = substr_count( $content, "\n" ) + 1;
            $sheet->getRowDimension($line)->setRowHeight( (12.5 * $zeilen) + 2);
            $line++;
        }
        $line++;

        $sheet->getStyle('A4:B' . $line)->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
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
    //  Offene Zeiten, die noch nicht abgerechnet sind auflisten
    //-----------------------------------------------------------------
    public function openTimes()
    {
        if( Input::get('key') != 'opentimes' ) {
            return '';
        }
        
        timetrackerTools::getSettings( true );          // Settings laden

        //  | Kunde | Kundennr | Gesamtzeit | seit letzter Rechung | noch abzurechnen | 
        $result = '<table class="openTab">'
                . '<thead><tr>'
                . '<th>Kunde</th>'
                . '<th>Kundennr.</th>'
                . '<th>Gesamtzeit</th>'
                . '<th>seit letzter Rechnung</th>'
                . '<th>noch abzurechnen</th>'
                . '</tr></thead>'
                . '<tbody>';

        $objKunde = $this->Database->prepare( "SELECT * FROM tl_timetracker_setting WHERE type=? AND active=1 AND hidelist<>1 ORDER BY kundenname")
                                   ->execute( 'kunde' );
        if( $objKunde->numRows < 1 ) return 'Keine Kunden gefunden.';

        while( $objKunde->next() ) {
            $summe = ['gesamt' => 0, 'lastRE' => 0, 'berechnet' => 0 ];
            $link = 'contao?do=timetrackerZeiten&amp;id=' . $objKunde->kundenID . '&amp;ref=' . Input::get('ref');
            $result .= '<tr><td><a href="' . $link . '" title="Zeitabrechnung ' . $objKunde->kundenname . ' aufrufen">' . $objKunde->kundenname . '</a></td><td>' . $objKunde->kundennr . '</td>';

            $objLog = $this->Database->prepare( "SELECT * FROM tl_timetracker_log WHERE kunde=? ORDER BY datum DESC")
                                     ->limit( 1000 )
                                     ->execute( $objKunde->kundenID );
            
            if( $objLog->numRows > 0 ) {                                                            // Alle Zeiten durchrechnen
                $restop = false;
                while( $objLog->next() ) {
                    if( in_array( $objLog->aufgabe, $GLOBALS['TIMETRACKER']['CALCSTOP'] ) && ($objLog->nostop != 1) ) $restop = true;   // bei ## Abrechnung ## nur noch Gesamtzeit weiterrechnen
                    if( in_array( $objLog->aufgabe, $GLOBALS['TIMETRACKER']['NOLIST'] ) 
                      || in_array( $objLog->aufgabe, $GLOBALS['TIMETRACKER']['CALCSTOP'] ) ) continue;                                  // Eintrag nicht listen

                    $arrDauer = StringUtil::trimsplit( ':', $objLog->dauer );
                    $dauer = ($arrDauer[1] * 60) + ($arrDauer[0] * 60 * 60);                        // Dauer in Sekunden
                    $summe['gesamt'] += $dauer;                                                     // Gesamtzeit
                    if( !$restop ) {
                        $summe['lastRE'] += $dauer;                                                 // Zeit seit letzter Rechnung
                        if( $objLog->noinvoice != '1' ) $summe['berechnet'] += $dauer;              // noch zu berechnende Zeit
                    }
                }
                $class = $summe['berechnet'] >= 3600 ? 'rechnung' : 'peanuts';
                $result .= '<td>' . sprintf( '%02d:%02d', floor($summe['gesamt']/3600), round(($summe['gesamt']/60)%60) ) . '</td>'
                         . '<td>' . sprintf( '%02d:%02d', floor($summe['lastRE']/3600), round(($summe['lastRE']/60)%60) ) . '</td>'
                         . '<td class="' . $class . '">' . ($summe['berechnet'] < 1 ? '-' : sprintf( '%02d:%02d', floor($summe['berechnet']/3600), round(($summe['berechnet']/60)%60) ) ) . '</td>';
            }
            else $result .= '<td colspan="3">Keine Zeiten eingetragen.</td>';

            $result .= '</tr>';
        }

        $result .= '</tbody>'
                 . '</table>';

        return '<div class="opentimesTab">' . $result . '</div>';
    }


//-----------------------------------------------------------------
}


// log_message( __METHOD__ . ' - objKunde=' . print_r( $objKunde->row(), 1 ), 'sl_debug.log' );

//         /** @var Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $objSessionBag */
//         $objSessionBag = System::getContainer()->get('session')->getBag('contao_backend');
//         $filter = $objSessionBag->get('filter');
// log_message( __METHOD__ . ' - $filter=' . print_r( $filter, 1 ), 'sl_debug.log' );

//         // Return the current category
//         $kundeID = $filter['tl_timetracker_log']['kunde'] ?? '';
//         if( $kundeID == '' ) return 'Kein Kunde ausgewählt.';
