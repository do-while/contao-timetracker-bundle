<?php

/**
 * Extension for Contao 4
 *
 * @copyright  Softleister 2020
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 */


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['CTE']['timetracker'] = array('timetracker', 'timetracker');

// Backend-Module
$GLOBALS['TL_LANG']['MOD']['timetracker']        = 'timetracker';
$GLOBALS['TL_LANG']['MOD']['timetrackerZeiten']  = array('Zeiten', 'Eingabe der Projektzeiten');
$GLOBALS['TL_LANG']['MOD']['timetrackerSetting'] = array('Einstellungen', 'Einstellungen der timetracker - V' . $GLOBALS['TIMETRACKER']['VERSION'] );

$GLOBALS['TL_LANG']['FMD']['timetracker']                 = 'timetracker';
$GLOBALS['TL_LANG']['FMD']['timetrackerThemenliste']      = array('Themenliste', 'Liste der Themen für Mandanten und Referenzgeber');
$GLOBALS['TL_LANG']['FMD']['timetrackerMandantenliste']   = array('Mandantenliste', 'Auflistung aller Mandanten eines Referenzgebers');
$GLOBALS['TL_LANG']['FMD']['timetrackerMandantenblatt']   = array('Mandantenblatt', 'Liste aller Vorgänge des Mandanten zum Thema');
$GLOBALS['TL_LANG']['FMD']['timetrackerMandantendetails'] = array('Mandanten-Details', 'Details aus einem Historie-Eintrag');
$GLOBALS['TL_LANG']['FMD']['timetrackerNotifications']    = array('Timetracker-Benachrichtigungen', 'Handling der Notifications, sollte per Cronjob aufgerufen werden.');
