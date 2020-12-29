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

//-----------------------------------------------------------------
//  LogExport:    Exportklasse
//-----------------------------------------------------------------
class LogExport extends \Backend
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_timetracker_logexport';

    //-----------------------------------------------------------------
    //  Function compile
    //-----------------------------------------------------------------
    public function exportLog()
    {
		if( \Input::get('key') != 'export' ) {
			return '';
        }
        
        timetrackerTools::getSettings( true );

        $stop = 15;

        // $db = \Database::getInstance();

		/** @var Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $objSessionBag */
		$objSessionBag = \Contao\System::getContainer()->get('session')->getBag('contao_backend');
		$filter = $objSessionBag->get('filter');

		// Return the current category
        $kundeID = $filter['tl_timetracker_log']['kunde'] ?? '';
        if( $kundeID == '' ) return 'Kein Kunde ausgewählt.';

// log_message( __METHOD__ . ' - kundeID=' . print_r( $kundeID, 1 ), 'sl_debug.log' );
// log_message( __METHOD__ . ' - kunde=' . print_r( $GLOBALS['TIMETRACKER']['KUNDEN'][$kundeID]['kundenname'], 1 ), 'sl_debug.log' );

        $result = '<h1>' . $GLOBALS['TIMETRACKER']['KUNDEN'][$kundeID]['kundenname'] . '</h1>'
                 .'<p>Kundennummer: ' . $GLOBALS['TIMETRACKER']['KUNDEN'][$kundeID]['kundennr'] . '</p>';

        $objLog = $this->Database->prepare( "SELECT * FROM tl_timetracker_log WHERE kunde=? ORDER BY datum DESC")
                                 ->limit( 200 )
                                 ->execute( $kundeID );
        $summe = 0;
        while( $objLog->next() ) {
            if( $objLog->aufgabe == $stop ) break;                          // bei ## Abrechnung ## beenden

            $arrDauer = \StringUtil::trimsplit( ':', $objLog->dauer );
            $dauer = ($arrDauer[1] * 60) + ($arrDauer[0] * 60 * 60);        // Dauer in Sekunden
            $summe += $dauer;


            $result .= '<p><strong>' . date('d.m.Y', $objLog->datum) . '&nbsp;&nbsp;' 
                     . sprintf( '%02d:%02d', floor($dauer/3600), round(($dauer/60)%60) ) . '</strong></p>'
                     . $objLog->beschreibung;
        }

        $result .= '<h2>Gesamt: ' . $summe . ' s (' . sprintf( '%02d:%02d', floor($summe/3600), round( ($summe/60)%60, 1) ) . ')</h2>';




        return $result;
        
		// Redirect
		// $this->redirect(str_replace('&key=export', '', \Environment::get('request')));
    }


// log_message( __METHOD__ . ' - objKunde=' . print_r( $objKunde->row(), 1 ), 'sl_debug.log' );
    //-----------------------------------------------------------------
}
