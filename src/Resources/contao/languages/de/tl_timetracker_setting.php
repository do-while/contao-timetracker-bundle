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
$GLOBALS['TL_LANG']['tl_timetracker_setting']['title']        = 'Einstellungen';
$GLOBALS['TL_LANG']['tl_timetracker_setting']['type']         = ['Typ', 'Einstellungs-Typ: Kunde/Projekt oder Aufgabe/Dienstleistung.'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['beschreibung'] = ['Anmerkungen', 'Anmerkungen zu dem Eintrag.'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['active']       = ['Eintrag ist aktiv', 'Inaktive Einträge tauchen nicht in den Selects für die Zeiterfassung auf.'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['kundenname']   = ['Kunde/Projekt', 'Geben Sie den Kundennamen oder das Projekt an.'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['kundennr']     = ['Kundennummer', 'Kundennummer im Rechnungsmodul.'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['agentur']      = ['Agentur', 'Hier die Agentur eintragen, wenn Beauftragung durch die Agentur.'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['stundensatz']  = ['Stundensatz', 'Der Netto-Stundensatz in EUR für die Berechnung auf dem Stundennachweis.'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['aufgabe']      = ['Aufgabe/Dienstleistung', 'Bezeichnung der Aufgabe oder Dienstleistung'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['abrechnung']   = ['nicht abrechnen!', 'Diese Aufgabe wird nicht abgerechnet, z.B. bei Rechnungen oder Infoeinträgen'];


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_timetracker_setting']['type_legend']     = 'Art der Einstellungen';
$GLOBALS['TL_LANG']['tl_timetracker_setting']['kunden_legend']   = 'Kunden/Projekte';
$GLOBALS['TL_LANG']['tl_timetracker_setting']['task_legend']     = 'Aufgaben/Dienstleistungen';
$GLOBALS['TL_LANG']['tl_timetracker_setting']['detail_legend']   = 'Details';
$GLOBALS['TL_LANG']['tl_timetracker_setting']['activate_legend'] = 'Aktivierung';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_timetracker_setting']['type_']['kunde'] = 'Kunde/Projekt';
$GLOBALS['TL_LANG']['tl_timetracker_setting']['type_']['task']  = 'Aufgabe/Dienstleistung';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_timetracker_setting']['edit']   = ['Neuer Eintrag', 'Eintrag mit Aufgabe oder Kunde'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['copy']   = ['Eintrag kopieren', 'Eintrag ID %s kopieren'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['delete'] = ['Eintrag löschen', 'Eintrag ID %s löschen'];
$GLOBALS['TL_LANG']['tl_timetracker_setting']['show']   = ['Eintragsdetails', 'Details zu Eintrag ID %s anzeigen'];
