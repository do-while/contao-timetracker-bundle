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

//-----------------------------------------------------------------
//  timetrackerTools:    Hilfsprogramme zu dieser Erweiterung
//-----------------------------------------------------------------
class timetrackerTools extends \Backend
{

	public function getSettings( $force = false )
	{                                                                                                             
		// Sind die Settings bereits geladen?
		if( is_array($GLOBALS['TIMETRACKER']['KUNDEN']) && !force ) return true;

		// installierte Version
		$GLOBALS['TIMETRACKER']['VERSION'] = \System::getContainer()->getParameter('kernel.packages')['do-while/contao-timetracker-bundle'];

		// Globale Arrays auffbauen
		$db = \Database::getInstance();

		// Kunden-Array
		$arrKunden = [];
		$objKunden = $db->execute( "SELECT kundenID, kundenname, kundennr, agentur, stundensatz FROM tl_timetracker_setting WHERE type='kunde' ORDER BY kundenID");
		while( $objKunden->next() ) {
			$arrKunden[$objKunden->kundenID] = $objKunden->row();
		}
		$GLOBALS['TIMETRACKER']['KUNDEN'] = $arrKunden;

		// Stop-Code-Array
		$arrCalcStop = $arrNoList = [];
		$objStop = $db->execute( "SELECT taskID, calcstop, nolist FROM tl_timetracker_setting WHERE type='task' AND active=1 ORDER BY taskID");
		while( $objStop->next() ) {
			if( $objStop->calcstop ) $arrCalcStop[] = $objStop->taskID;
			if( $objStop->nolist ) $arrNoList[] = $objStop->taskID;
		}
		$GLOBALS['TIMETRACKER']['CALCSTOP'] = $arrCalcStop;
		$GLOBALS['TIMETRACKER']['NOLIST'] = $arrNoList;

		return true;	
    }
	
	
	//-------------------------------------------------------------------------
}
