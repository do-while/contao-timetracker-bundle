<?php

declare( strict_types=1 );

/**
 * Extension for Contao 5
 *
 * @copyright  Softleister 2020-2024
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
 */

namespace Softleister\Timetracker;

use Contao\Backend;
use Contao\System;
use Contao\Database;

//-----------------------------------------------------------------
//  timetrackerTools:    Hilfsprogramme zu dieser Erweiterung
//-----------------------------------------------------------------
class timetrackerTools extends Backend
{

    public static function getSettings( $force = false )
    {
        // Sind die Settings bereits geladen?
        if( isset( $GLOBALS['TIMETRACKER'] ) && is_array( $GLOBALS['TIMETRACKER']['KUNDEN'] ) && !$force ) return true;

        // installierte Version
        $GLOBALS['TIMETRACKER']['VERSION'] = System::getContainer()->getParameter('kernel.packages')['do-while/contao-timetracker-bundle'];

        // Globale Arrays auffbauen
        $db = Database::getInstance();

        $arrTables = $db->listTables( null, true );
        if( !in_array( 'tl_timetracker_setting', $arrTables ) ) return true;                // Einstellungen nicht vorhanden -> Abbruch

        $arrSpalten = $db->listFields( 'tl_timetracker_setting', true );                    // Prüfen, ob alle benötigten Spalten vorhanden
        foreach( $arrSpalten AS $field ) $arrFields[] = $field['name'];
        if( !in_array( 'kundenID', $arrFields )
          || !in_array( 'kundenname', $arrFields )
          || !in_array( 'kundennr', $arrFields )
          || !in_array( 'agentur', $arrFields )
          || !in_array( 'stundensatz', $arrFields )
          || !in_array( 'type', $arrFields )
          || !in_array( 'active', $arrFields )
          || !in_array( 'taskID', $arrFields )
          || !in_array( 'calcstop', $arrFields )
          || !in_array( 'nolist', $arrFields )
          || !in_array( 'defaultid', $arrFields ) ) return true;

        // Kunden-Array
        $arrKunden = [];
        $objKunden = $db->execute( "SELECT kundenID, kundenname, kundennr, agentur, stundensatz FROM tl_timetracker_setting WHERE type='kunde' ORDER BY kundenID");
        while( $objKunden->next() ) {
            $arrKunden[$objKunden->kundenID] = $objKunden->row();
        }
        $GLOBALS['TIMETRACKER']['KUNDEN'] = $arrKunden;

        // Stop-Code-Array
        $arrCalcStop = $arrNoList = [];
        $defaultid = 0;
        $objStop = $db->execute( "SELECT taskID, calcstop, nolist, defaultid FROM tl_timetracker_setting WHERE type='task' AND active=1 ORDER BY taskID");
        while( $objStop->next() ) {
            if( $objStop->calcstop ) $arrCalcStop[] = $objStop->taskID;
            if( $objStop->nolist ) $arrNoList[] = $objStop->taskID;
            if( $objStop->defaultid ) $defaultid = $objStop->taskID;
        }
        $GLOBALS['TIMETRACKER']['CALCSTOP'] = $arrCalcStop;
        $GLOBALS['TIMETRACKER']['NOLIST'] = $arrNoList;
        $GLOBALS['TIMETRACKER']['DEFAULT'] = $defaultid;

        return true;	
    }


    //-------------------------------------------------------------------------
}
