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

use Contao\Backend;
use Contao\DC_Table;
use Contao\DataContainer;


$GLOBALS['TL_DCA']['tl_timetracker_kunde'] = [

    // Config
    'config' => [
        'dataContainer'               => DC_Table::class,
        'enableVersioning'            => true,
        'onsubmit_callback'           => [
            ['tl_timetracker_kunde', 'manageKundenID']
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'kundennr' => 'index'
            ]
        ]
    ],

    // List
    'list' => [
        'sorting' => [
            'mode'                    => DataContainer::MODE_SORTABLE,
            'panelLayout'             => 'filter;sort,search,limit',
            'defaultSearchField'      => 'kundenname',
        ],
        'label' => [
            'fields'                  => ['id'],
            'label_callback'          => ['tl_timetracker_kunde', 'getLabel']
        ],
        'global_operations' => [
            'all' => [
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ]
        ],
    ],

    // Palettes
    'palettes' => [
        'default'                     => '{kunden_legend},kundenname,kundennr,agentur,stundensatz;'
                                        .'{detail_legend},beschreibung;'
                                        .'{activate_legend},active,kundenID,hidelist'
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
        'kundenname' => [
            'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => ['type' => 'string', 'length' => 255, 'default' => '']
        ],
        'kundennr' => [
            'flag'                    => DataContainer::SORT_INITIAL_LETTERS_ASC,
            'search'                  => true,
            'sorting'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>8, 'tl_class'=>'w50'],
            'sql'                     => ['type' => 'string', 'length' => 8, 'default' => '']
        ],
        'agentur' => [
            'search'                  => true,
            'filter'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => ['type' => 'string', 'length' => 255, 'default' => '']
        ],
        'stundensatz' => [
            'filter'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>8, 'tl_class'=>'w50'],
            'sql'                     => ['type' => 'string', 'length' => 8, 'default' => '']
        ],
//--------
        'beschreibung' => [
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => ['rte'=>'tinyMCE', 'helpwizard'=>true],
            'explanation'             => 'insertTags',
            'sql'                     => "mediumtext NULL"
        ],
//--------
        'active' => [
            'default'                 => '1',
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => ['type' => 'boolean', 'default' => false]
        ],
        'kundenID' => [
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>8, 'readonly'=>true, 'tl_class'=>'w50'],
            'sql'                     => ['type' => 'integer', 'notnull' => false, 'unsigned' => true, 'default' => '0']
        ],
        'hidelist' => [
            'filter'                  => true,
			'reverseToggle'           => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => ['type' => 'boolean', 'default' => false]
        ],
//--------
    ]
];


//--- Klasse tl_timetracker_kunde ---
class tl_timetracker_kunde extends Backend
{
    //---------------------------------------------------------------
    //  Label für Kundenliste
    //---------------------------------------------------------------
    public function getLabel( $row, $label )
    {
        $label = '<span style="display:inline-block;width:80px">' . $row['kundennr'] . '</span>'
                .'<span>' .  $row['kundenname'] . ( empty( $row['agentur'] ) ? '' : ' / ' . $row['agentur'] ) .  '</span>';
        $class = $row['active'] === '1' ? ' active' : ' inactive';

        return '<span class="timetrack' . $class . '">' . $label . '</span>';
    }


    //---------------------------------------------------------------
    // KundenID beim ersten Speichern eintragen
    //---------------------------------------------------------------
    public function manageKundenID( $dc )
    {
        // Front end call
        if( !$dc instanceof DataContainer ) return;

        // Return if there is no active record (override all)
        if( !$dc->activeRecord ) return;

        // kundenID setzen, wenn noch nicht vergeben
        if( $dc->activeRecord->kundenID == 0 ) {
            $objLast = $this->Database->execute( "SELECT MAX(kundenID) AS lastid FROM tl_timetracker_kunde" );

            $arrSet = [];
            $arrSet['tstamp']   = time();
            $arrSet['kundenID'] = $objLast->lastid + 1;
            $this->Database->prepare( "UPDATE tl_timetracker_kunde %s WHERE id=?" )
                           ->set( $arrSet )
                           ->execute( $dc->activeRecord->id );
        }
    }


    //---------------------------------------------------------------
}
