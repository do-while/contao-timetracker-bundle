<?php

/**
 * Extension for Contao 4
 *
 * @copyright  Softleister 2020
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
*/

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_timetracker_log']['kunde']	      = array('Kunde / Projekt', 'Wählen Sie den Kunden oder das Projekt aus.');
$GLOBALS['TL_LANG']['tl_timetracker_log']['aufgabe']	  = array('Aufgabe / Dienstleistung', 'Wählen Sie Aufgabe / Dienstleistung aus.');
$GLOBALS['TL_LANG']['tl_timetracker_log']['datum']		  = array('Datum', 'Datum der Arbeit.');
$GLOBALS['TL_LANG']['tl_timetracker_log']['startzeit']	  = array('Startzeit', 'Uhrzeit beim Start der Aufgabe (optionell).');
$GLOBALS['TL_LANG']['tl_timetracker_log']['dauer']        = array('Dauer', 'Abzurechnende Arbeitszeit');
$GLOBALS['TL_LANG']['tl_timetracker_log']['beschreibung'] = array('Bearbeitung', 'Dateils zur Bearbeitung.');
$GLOBALS['TL_LANG']['tl_timetracker_log']['noinvoice']    = array('nicht abrechnen', 'Die Aufgabe wird nicht in Rechnung gestellt.');
$GLOBALS['TL_LANG']['tl_timetracker_log']['nostop']       = array('Abrechnungsstop nicht beachten', 'Die Aufrechnung der Zeiten soll durch diesen Eintrag nicht beendet werden.');
$GLOBALS['TL_LANG']['tl_timetracker_log']['username']     = array('Backend-User', 'Backend-User, der den Eintrag vorgenommen hat.');
$GLOBALS['TL_LANG']['tl_timetracker_log']['member']       = array('Frontend-Member', 'Frontend-Member, der den Eintrag vorgenommen hat.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_timetracker_log']['kunde_legend']   = 'Kunde und Aufgabe';
$GLOBALS['TL_LANG']['tl_timetracker_log']['zeit_legend']    = 'Zeitinformation';
$GLOBALS['TL_LANG']['tl_timetracker_log']['aufgabe_legend'] = 'Aufgabenbeschreibung';
$GLOBALS['TL_LANG']['tl_timetracker_log']['user_legend']    = 'Bearbeiter';


/**
 * Icon-Texte
 */
$GLOBALS['TL_LANG']['tl_timetracker_log']['new']    = array('Neuer Eintrag', 'Eintrag hinzufügen');
$GLOBALS['TL_LANG']['tl_timetracker_log']['edit']   = array('Eintrag bearbeiten', 'Eintrag ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_timetracker_log']['copy']   = array('Eintrag kopieren', 'Eintrag ID %s kopieren');
$GLOBALS['TL_LANG']['tl_timetracker_log']['delete'] = array('Eintrag löschen', 'Eintrag ID %s löschen');
$GLOBALS['TL_LANG']['tl_timetracker_log']['toggle'] = array('Eintrag aktivieren/deaktivieren', 'Eintrag ID %s aktivieren/deaktivieren');
$GLOBALS['TL_LANG']['tl_timetracker_log']['show']   = array('Eintragsdetails', 'Details zum Eintrag ID %s anzeigen');

$GLOBALS['TL_LANG']['tl_timetracker_log']['export']    = array('Exportieren', 'Exportieren der Zeitlisten');
$GLOBALS['TL_LANG']['tl_timetracker_log']['opentimes'] = array('Offene Zeiten', 'Nicht abgerechnete Zeiten auflisten');
