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


$GLOBALS['TL_DCA']['tl_timetracker_task'] = [

    // Config
    'config' => [
        'dataContainer'               => DC_Table::class,
        'enableVersioning'            => true,
        'onsubmit_callback'           => [
            ['tl_timetracker_task', 'manageTaskID']
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
            'mode'                    => DataContainer::MODE_SORTED,
            'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
            'fields'                  => ['aufgabe'],
            'panelLayout'             => 'filter;search,limit',
        ],
        'label' => [
            'fields'                  => ['id'],
            'label_callback'          => ['tl_timetracker_task', 'getLabel']
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
        'default'                     => '{task_legend},aufgabe,abrechnung,calcstop,nolist,defaultid;'
                                        .'{detail_legend},beschreibung;'
                                        .'{activate_legend},active,taskID'
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
        'aufgabe' => [
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => ['type' => 'string', 'length' => 255, 'default' => '']
        ],
        'abrechnung' => [
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => ['type' => 'boolean', 'default' => false]
        ],
        'calcstop' => [
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => ['type' => 'boolean', 'default' => false]
        ],
        'nolist' => [
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => ['type' => 'boolean', 'default' => false]
        ],
        'defaultid' => [
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => ['type' => 'boolean', 'default' => false]
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
			'toggle'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => ['type' => 'boolean', 'default' => false]
        ],
        'taskID' => [
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>8, 'readonly'=>true, 'tl_class'=>'w50'],
            'sql'                     => ['type' => 'integer', 'notnull' => false, 'unsigned' => true, 'default' => '0']
        ],
//--------
    ]
];


//--- Klasse tl_timetracker_task ---
class tl_timetracker_task extends Backend
{
    //---------------------------------------------------------------
    //  Label für Aufgabenliste
    //---------------------------------------------------------------
    public function getLabel( $row, $label )
    {
        $label = '<span style="display:inline-block;width:400px">' . $row['aufgabe'] . '</span>'
               . ($row['defaultid'] ? '<span style="color:#e00">default</span>' : '');
        $class = $row['active'] === '1' ? ' active' : ' inactive';

        return '<span class="timetrack' . $class . '">' . $label . '</span>';
    }


    //---------------------------------------------------------------
    // TaskID beim ersten Speichern eintragen
    //---------------------------------------------------------------
    public function manageTaskID( $dc )
    {
        // Front end call
        if( !$dc instanceof DataContainer ) return;

        // Return if there is no active record (override all)
        if( !$dc->activeRecord ) return;

        // taskID setzen, wenn noch nicht vergeben
        if( $dc->activeRecord->taskID == 0 ) {
            $objLast = $this->Database->execute( "SELECT MAX(taskID) AS lastid FROM tl_timetracker_task" );

            $arrSet = [];
            $arrSet['tstamp'] = time();
            $arrSet['taskID'] = $objLast->lastid + 1;
            $this->Database->prepare( "UPDATE tl_timetracker_task %s WHERE id=?" )
                           ->set( $arrSet )
                           ->execute( $dc->activeRecord->id );
        }
    }


    //---------------------------------------------------------------
}
