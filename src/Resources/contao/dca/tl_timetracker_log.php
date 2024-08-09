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

use Contao\Input;
use Contao\System;
use Contao\Backend;
use Contao\Database;
use Contao\DC_Table;
use Contao\BackendUser;


$GLOBALS['TL_DCA']['tl_timetracker_log'] = [
    // Config
    'config' => [
        'dataContainer'               => DC_Table::class,
        'enableVersioning'            => true,
        'onload_callback'             => [ ['tl_timetracker_log', 'setKundenID'] ],
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ]
        ]
    ],

    // List
    'list' => [
        'sorting' => [
            'mode'                    => 2,
            'flag'                    => 6,
            'fields'                  => ['datum DESC'],
            'panelLayout'             => 'filter;sort,search,limit'
        ],
        'label' => [
            'fields'                  => ['datum', 'dauer', 'kunde', 'aufgabe'],
            'format'                  => '%s / %s / %s / %s',
            'label_callback'          => ['tl_timetracker_log', 'logLabel']
        ],
        'global_operations' => [
            'export' => [
                'href'                => 'key=export',
                'class'               => 'header_xls_export',
            ],
            'opentimes' => [
                'href'                => 'key=opentimes',
                'class'               => 'header_opentimes',
            ],
            'all'
        ],
    ],

    // Palettes
    'palettes' => [
        'default'                     => '{kunde_legend},kunde,aufgabe;'
                                        .'{zeit_legend},datum,dauer,startzeit;'
                                        .'{aufgabe_legend},beschreibung,noinvoice,nostop;'
                                        .'{user_legend},username,member;'
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql'                     => ['type' => 'integer', 'notnull' => false, 'unsigned' => true, 'autoincrement' => true]
        ],
        'tstamp' => [
            'sql'                     => ['type' => 'integer', 'notnull' => false, 'unsigned' => true, 'default' => '0']
        ],
        'pid' => [
            'sql'                     => ['type' => 'integer', 'notnull' => false, 'unsigned' => true, 'default' => '0']
        ],
//--------
        'kunde' => [
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'search'                  => true,
            'flag'                    => 1,
            'inputType'               => 'select',
            'options_callback'        => ['tl_timetracker_log', 'getTimetrackerKunden'],
            'load_callback'           => [ ['tl_timetracker_log', 'checkFilterKunde'] ],
            'eval'                    => ['mandatory'=>true, 'chosen'=>true, 'tlass'=>'clr w50', 'includeBlankOption'=>true],
            'sql'                     => ['type' => 'string', 'length' => 80, 'default' => '']
        ],
        'aufgabe' => [
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'search'                  => true,
            'default'                 => $GLOBALS['TIMETRACKER']['DEFAULT'],
            'flag'                    => 1,
            'inputType'               => 'select',
            'options_callback'        => ['tl_timetracker_log', 'getTimetrackerAufgaben'],
            'load_callback'           => [ ['tl_timetracker_log', 'checkFilterAufgabe'] ],
            'eval'                    => ['mandatory'=>true, 'chosen'=>true, 'tl_class'=>'w50', 'includeBlankOption'=>true],
            'sql'                     => ['type' => 'string', 'length' => 11, 'default' => '']
        ],
//--------
        'datum' => [
            'default'                 => time(),
            'filter'                  => true,
            'exclude'                 => true,
            'sorting'                 => true,
            'flag'                    => 8,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard'],
            'load_callback'           => [ ['tl_timetracker_log', 'loadDate'] ],
            'sql'                     => ['type' => 'string', 'length' => 11, 'default' => '']
        ],
        'dauer' => [
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['tl_class'=>'clr w50'],
            'sql'                     => ['type' => 'string', 'length' => 5, 'default' => '']
        ],
        'startzeit' => [
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'time', 'doNotCopy'=>true, 'tl_class'=>'w50'],
            'load_callback'           => [ ['tl_timetracker_log', 'loadTime'] ],
            'sql'                     => ['type' => 'string', 'length' => 6, 'default' => '']
        ],
//--------
        'beschreibung' => [
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'search'                  => true,
            'eval'                    => ['rte'=>'tinyMCE', 'tl_class'=>'clr'],
            'sql'                     => "text NULL"
        ],
        'noinvoice' => [
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['doNotCopy'=>true, 'tl_class'=>'m12 w50'],
            'sql'                     => ['type' => 'boolean', 'default' => false]
        ],
        'nostop' => [
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['doNotCopy'=>true, 'tl_class'=>'m12 w50'],
            'sql'                     => ['type' => 'boolean', 'default' => false]
        ],
//--------
        'username' => [
            'default'                 => BackendUser::getInstance()->id,
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_user.name',
            'eval'                    => ['doNotCopy'=>true, 'mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'],
            'sql'                     => ['type' => 'integer', 'notnull' => false, 'unsigned' => true, 'default' => '0'],
            'relation'                => ['type'=>'hasOne', 'load'=>'lazy']
        ],
        'member' => [
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_member.username',
            'eval'                    => ['doNotCopy'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'],
            'sql'                     => ['type' => 'integer', 'notnull' => false, 'unsigned' => true, 'default' => '0'],
            'relation'                => ['type'=>'hasOne', 'load'=>'lazy']
        ],
    ]
];


//--- Klasse tl_timetracker_log ---
class tl_timetracker_log extends Backend
{
    protected $arrKunden = [];
    protected $arrAufgaben = [];

    /**
     * Import the back end user object
     */
    public function __construct( )
    {
        // parent::__construct( );
        // $this->import( BackendUser::class, 'User' );

        $this->arrKunden = $this->getTimetrackerKunden( );
        $this->arrAufgaben = $this->getTimetrackerAufgaben( );
    }


    //---------------------------------------------------------------
    //  Aufruf mit ID?  Dann Filter setzen
    //---------------------------------------------------------------
    public function setKundenID( )
    {
        if( Input::get('id') == '' ) return;           // keine Einschränkung bei direktem Aufruf

        $GLOBALS['TL_DCA']['tl_timetracker_log']['list']['sorting']['filter'][] = array( 'kunde=?', Input::get('id') );
    }


    //---------------------------------------------------------------
    //  Datum formatieren
    //---------------------------------------------------------------
    public function loadDate( $value )
    {
        if( empty( $value ) ) $value = time();

        return strtotime( date('Y-m-d', $value) . ' 00:00:00' );
    }

    //---------------------------------------------------------------
    //  Zeit formatieren
    //---------------------------------------------------------------
    public function loadTime( $value )
    {
        if( empty( $value ) ) return '';

        return strtotime( '1970-01-01 ' . date('H:i:s', $value) );
    }


    //---------------------------------------------------------------
    // Feld 'Kunde' vorbelegen mit dem Filterwert
    //---------------------------------------------------------------
    public function checkFilterKunde( $varValue )
    {
        // Do not change the value if it has been set already
        if( ($varValue > 0) || Input::post('FORM_SUBMIT') == 'tl_timetracker_log' ) {
            return $varValue;
        }

        /** @var Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $objSessionBag */
        $objSessionBag = System::getContainer( )->get( 'request_stack' )->getSession( )->getBag( 'contao_backend' );
        $filter = $objSessionBag->get('filter');

        // Return the current category
        return $filter['tl_timetracker_log']['kunde'] ?? '';
    }


    //---------------------------------------------------------------
    // Feld 'Aufgabe' vorbelegen mit dem Filterwert
    //---------------------------------------------------------------
    public function checkFilterAufgabe( $varValue )
    {
        // Do not change the value if it has been set already
        if( ($varValue > 0) || Input::post('FORM_SUBMIT') == 'tl_timetracker_log' ) {
            return $varValue;
        }

        /** @var Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $objSessionBag */
        $objSessionBag = System::getContainer( )->get( 'request_stack' )->getSession( )->getBag( 'contao_backend' );
        $filter = $objSessionBag->get('filter');

        // Return the current category
        return $filter['tl_timetracker_log']['aufgabe'] ?? '';
    }


    //---------------------------------------------------------------
    //  Liste der Kunden/Projekte zurückgeben
    //---------------------------------------------------------------
    public function getTimetrackerKunden( )
    {
        $db = Database::getinstance( );
        $objKunde = $db->execute( "SELECT kundenID, kundenname, kundennr FROM tl_timetracker_setting WHERE type='kunde' AND active=1 ORDER BY kundenname" );
        
        $arrKunden = [];
        while( $objKunde->next() ) {
            $arrKunden[$objKunde->kundenID] = $objKunde->kundenname . (empty($objKunde->kundennr) ? '' :  ' (' . $objKunde->kundennr . ')');
        }

        return $arrKunden;
    }


    //---------------------------------------------------------------
    //  Liste der Aufgaben/Dienstleistungen zurückgeben
    //---------------------------------------------------------------
    public function getTimetrackerAufgaben( )
    {
        $db = Database::getinstance( );
        $objAufg = $db->execute( "SELECT taskID, aufgabe FROM tl_timetracker_setting WHERE type='task' AND active=1 ORDER BY aufgabe" );
        
        $arrAufgaben = [];
        while( $objAufg->next() ) {
            $arrAufgaben[$objAufg->taskID] = $objAufg->aufgabe;
        }

        return $arrAufgaben;
    }


    //---------------------------------------------------------------
    // Backend-Zeile aufbauen
    //---------------------------------------------------------------
    public function logLabel( $row, $label )
    {
        $css = $row['noinvoice'] == '1' ? ' noinvoice' : '';

        $label = '<div class="logrow' . $css . '"><p><strong><span>' . date( 'd.m.Y', $row['datum'] ) . ' - ' . $row['dauer'] . '</span><span>' 
                                              . $this->arrKunden[$row['kunde']] . '</span><span>' 
                                              . $this->arrAufgaben[$row['aufgabe']] . '</span></strong></p><div class="note">'
                                              . $row['beschreibung'] . '</div></div>';

        return $label;
    }


    //---------------------------------------------------------------
}
