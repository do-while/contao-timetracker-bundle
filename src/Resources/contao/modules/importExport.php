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
//  importExport:    Import- und Exportklasse
//
//  Import aus "Anuko Time Tracker" https://www.anuko.com/time-tracker/index.htm
//-----------------------------------------------------------------
class importExport extends \BackendModule
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_timetracker_importexport';

    //-----------------------------------------------------------------
    //  Function compile
    //-----------------------------------------------------------------
    protected function compile()
    {
        $db = \Database::getInstance();

        //=== Import Projekte ===
        // $objKunde = $db->prepare("SELECT * FROM tt_projects ORDER BY id")->execute();

        // while( $objKunde->next() ) {
        //     $objSetting = $db->prepare("SELECT id FROM tl_timetracker_setting WHERE kundenID=?")->execute( $objKunde->id );
        //     if( $objSetting->numRows > 0 ) continue;

        //     $arrSet = [];
        //     $arrSet['tstamp']       = time();
        //     $arrSet['type']         = 'kunde';
        //     $arrSet['kundenname']   = $objKunde->name;
        //     $arrSet['kundenID']     = $objKunde->id;
        //     $arrSet['beschreibung'] = $objKunde->description === null ? '' : '<p>' . $objKunde->description . '</p>';
        //     $arrSet['active']       = $objKunde->status == 1 ? '1' : '';
        //     $arrSet['stundensatz']  = '60';

        //     $db->prepare("INSERT INTO tl_timetracker_setting %s")->set( $arrSet )->execute();
        // }


        //=== Import Aufgaben ===
        // $objTask = $db->prepare("SELECT * FROM tt_tasks ORDER BY id")->execute();

        // while( $objTask->next() ) {
        //     $objSetting = $db->prepare("SELECT id FROM tl_timetracker_setting WHERE taskID=?")->execute( $objTask->id );
        //     if( $objSetting->numRows > 0 ) continue;

        //     $arrSet = [];
        //     $arrSet['tstamp']       = time();
        //     $arrSet['type']         = 'task';
        //     $arrSet['aufgabe']      = $objTask->name;
        //     $arrSet['taskID']       = $objTask->id;
        //     $arrSet['beschreibung'] = $objTask->description === null ? '' : '<p>' . $objTask->description . '</p>';
        //     $arrSet['active']       = $objTask->status == 1 ? '1' : '';

        //     $db->prepare("INSERT INTO tl_timetracker_setting %s")->set( $arrSet )->execute();
        // }


        //=== Import Einträge ===
        // $objLog = $db->prepare("SELECT * FROM tt_log ORDER BY id")->execute();
        // while( $objLog->next() ) {
            
        //     $arrSet = [];
        //     $arrSet['tstamp']       = strtotime( $objLog->timestamp );
        //     $arrSet['pid']          = 0;
        //     $arrSet['kunde']        = $objLog->project_id;
        //     $arrSet['aufgabe']      = $objLog->task_id;
        //     $arrSet['datum']        = strtotime( $objLog->date . ' 00:00:00' );
        //     $arrSet['dauer']        = substr( $objLog->duration, 0, 5 );
        //     $arrSet['startzeit']    = empty($objLog->start) ? '' : substr( $objLog->start, 0, 5 );
        //     $arrSet['beschreibung'] = '<p>' . nl2br( $objLog->comment, false ) . '</p>';
        //     $arrSet['noinvoice']    = $objLog->billable == 1 ? '' : '1';
        //     $arrSet['username']     = 0;
        //     $arrSet['member']       = 0;

        //     $db->prepare("INSERT INTO tl_timetracker_log %s")->set( $arrSet )->execute();
        // }
        
    	// $this->redirect( str_replace( '&reset=1', '', \Environment::get('request') ) );
    }


// log_message( __METHOD__ . ' - objKunde=' . print_r( $objKunde->row(), 1 ), 'sl_debug.log' );
    //-----------------------------------------------------------------
}
