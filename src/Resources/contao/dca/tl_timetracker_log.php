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

use Contao\Backend;
use Contao\DC_Table;
use Contao\BackendUser;
use Contao\Input;
use Contao\System;


$GLOBALS['TL_DCA']['tl_timetracker_log'] = [
    // Config
    'config' => [
        'dataContainer'               => DC_Table::class,
        'enableVersioning'            => true,
        'onload_callback' => [
            ['tl_timetracker_log', 'setKundenID']
        ],
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
            'all' => [
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ],
        ],
        'operations' => [
            'edit' => [
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ],
            'copy' => [
                'href'                => 'act=copy',
                'icon'                => 'copy.svg'
            ],
            'delete' => [
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'show' => [
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            ],
        ]
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
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
        'pid' => [
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
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
            'sql'                     => "varchar(80) NOT NULL default '"
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
            'sql'                     => "varchar(11) NOT NULL default ''"
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
            'sql'                     => "varchar(11) NOT NULL default ''"
        ],
        'dauer' => [
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['tl_class'=>'clr w50'],
            'sql'                     => "varchar(5) NOT NULL default ''"
        ],
        'startzeit' => [
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'time', 'doNotCopy'=>true, 'tl_class'=>'w50'],
            'load_callback'           => [ ['tl_timetracker_log', 'loadTime'] ],
            'sql'                     => "varchar(6) NOT NULL default ''"
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
            'sql'                     => "char(1) NOT NULL default ''"
        ],
        'nostop' => [
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['doNotCopy'=>true, 'tl_class'=>'m12 w50'],
            'sql'                     => "char(1) NOT NULL default ''"
        ],
//--------
        'username' => [
            'default'                 => BackendUser::getInstance()->id,
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_user.name',
            'eval'                    => ['doNotCopy'=>true, 'mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'],
            'sql'                     => "int(10) unsigned NOT NULL default 0",
            'relation'                => ['type'=>'hasOne', 'load'=>'lazy']
        ],
        'member' => [
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_member.username',
            'eval'                    => ['doNotCopy'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'],
            'sql'                     => "int(10) unsigned NOT NULL default 0",
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
    public function setKundenID()
    {
        if( empty( Input::get('id') ) ) return;           // keine Einschränkung bei direktem Aufruf

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
        $objSessionBag = System::getContainer()->get('session')->getBag('contao_backend');
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
        $objSessionBag = System::getContainer()->get('session')->getBag('contao_backend');
        $filter = $objSessionBag->get('filter');

        // Return the current category
        return $filter['tl_timetracker_log']['aufgabe'] ?? '';
    }


    //---------------------------------------------------------------
    //  Liste der Kunden/Projekte zurückgeben
    //---------------------------------------------------------------
    public function getTimetrackerKunden( )
    {
        $objKunde = $this->Database->execute( "SELECT kundenID, kundenname, kundennr FROM tl_timetracker_setting WHERE type='kunde' AND active=1 ORDER BY kundenname" );
        
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
        $objAufg = $this->Database->execute( "SELECT taskID, aufgabe FROM tl_timetracker_setting WHERE type='task' AND active=1 ORDER BY aufgabe" );
        
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
// log_message( __METHOD__ . ' - row=' . print_r( $row, 1 ), 'sl_debug.log' );

        $css = $row['noinvoice'] == '1' ? ' noinvoice' : '';

        $label = '<div class="logrow' . $css . '"><p><strong><span>' . date( 'd.m.Y', $row['datum'] ) . ' - ' . $row['dauer'] . '</span><span>' 
                                              . $this->arrKunden[$row['kunde']] . '</span><span>' 
                                              . $this->arrAufgaben[$row['aufgabe']] . '</span></strong></p><div class="note">'
                                              . $row['beschreibung'] . '</div></div>';

        return $label;
    }


    //---------------------------------------------------------------
}
