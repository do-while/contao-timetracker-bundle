<?php

declare( strict_types=1 );

/**
 * Extension for Contao 5
 *
 * @copyright  Softleister 2020-2026
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
 */

namespace Softleister\Timetracker;

use Contao\Backend;
use Contao\Database;
use Composer\InstalledVersions;

// \file_put_contents( '../var/logs/sl-debug.log', __METHOD__ . ': content = ' . \print_r($content, true). "\n", FILE_APPEND );

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
        $GLOBALS['TIMETRACKER']['VERSION'] = InstalledVersions::getPrettyVersion('do-while/contao-timetracker-bundle');

        // Globale Arrays aufbauen
        $db = Database::getInstance();

        $arrTables = $db->listTables( null, true );
        if( !in_array( 'tl_timetracker_kunde', $arrTables )
          || !in_array( 'tl_timetracker_task', $arrTables ) ) return true;                  // Tabellen nicht vorhanden -> Abbruch

        // Kunden-Array
        $arrKunden = [];
        $objKunden = $db->execute( "SELECT kundenID, kundenname, kundennr, agentur, stundensatz FROM tl_timetracker_kunde ORDER BY kundenID" );
        while( $objKunden->next() ) {
            $arrKunden[$objKunden->kundenID] = $objKunden->row();
        }
        $GLOBALS['TIMETRACKER']['KUNDEN'] = $arrKunden;

        // Stop-Code-Array
        $arrCalcStop = $arrNoList = [];
        $defaultid = 0;
        $objStop = $db->execute( "SELECT taskID, calcstop, nolist, defaultid FROM tl_timetracker_task WHERE active=1 ORDER BY taskID" );
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
