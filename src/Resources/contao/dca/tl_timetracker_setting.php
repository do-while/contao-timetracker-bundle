<?php

/**
 * Extension for Contao 4
 *
 * @copyright  Softleister 2020-2022
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
*/

use Contao\Backend;


$GLOBALS['TL_DCA']['tl_timetracker_setting'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'onsubmit_callback' => array
        (
            array('tl_timetracker_setting', 'manageIds')
        ),
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 0,
            'fields'                  => ['type', 'kundenname', 'aufgabe'],
            'panelLayout'             => 'filter;search,limit',
        ),
        'label' => array
        (
            'fields'                  => ['id'],
            'format'                  => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['title'],
            'label_callback'          => array('tl_timetracker_setting', 'getLabel')
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['copy'],
                'href'                => 'act=paste&amp;mode=copy',
                'icon'                => 'copy.svg',
                'attributes'          => 'onclick="Backend.getScrollOffset()"'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            ),
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => ['type'],
        'default'                     => '{type_legend},type',
        'kunde'                       => '{type_legend},type;'
                                        .'{kunden_legend},kundenname,kundennr,agentur,stundensatz;'
                                        .'{detail_legend},beschreibung;'
                                        .'{activate_legend},active,kundenID,hidelist',
        'task'                        => '{type_legend},type;'
                                        .'{task_legend},aufgabe,abrechnung,calcstop,nolist,defaultid;'
                                        .'{detail_legend},beschreibung;'
                                        .'{activate_legend},active,taskID'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'pid' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
//--------
        'type' => array
        (
            'default'                 => 'text',
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'options'                 => ['kunde', 'task'],
            'reference'               => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['type_'],
            'eval'                    => ['mandatory'=>true, 'includeBlankOption'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'],
            'sql'                     => "varchar(16) NOT NULL default ''"
        ),
        'beschreibung' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => ['rte'=>'tinyMCE', 'helpwizard'=>true],
            'explanation'             => 'insertTags',
            'sql'                     => "mediumtext NULL"
        ),
        'active' => array
        (
            'default'                 => '1',
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
//--- Kunde/Projekt ---
        'kundenname' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'kundennr' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>8, 'tl_class'=>'w50'],
            'sql'                     => "varchar(8) NOT NULL default ''"
        ),
        'kundenID' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>8, 'readonly'=>true, 'tl_class'=>'w50'],
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'agentur' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'stundensatz' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>8, 'tl_class'=>'w50'],
            'sql'                     => "varchar(8) NOT NULL default ''"
        ),
//--- Aufgabe/Dienstleistung ---
        'aufgabe' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'taskID' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>8, 'readonly'=>true, 'tl_class'=>'w50'],
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'abrechnung' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'calcstop' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'nolist' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'defaultid' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'hidelist' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['tl_class'=>'m12 w50'],
            'sql'                     => "char(1) NOT NULL default ''"
        ),
//--------
    )
);


//--- Klasse tl_timetracker_setting ---
class tl_timetracker_setting extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct( )
    {
        parent::__construct( );
        $this->import( 'BackendUser', 'User' );
    }


    //---------------------------------------------------------------
    //  Label für Einstellungen
    //---------------------------------------------------------------
    public function getLabel( $row, $label )
    {
        if( $row['type'] === 'kunde' ) {
            $label = '<span>Kunde/Projekt:</span><span>' . $row['kundenname'] . ' / ' . $row['agentur'] . ' (' . $row['kundennr'] . ')</span>';
        }
        else {
            $label = '<span>Aufgabe:</span><span style="display:inline-block;width:400px">' . $row['aufgabe'] . '</span>' . ($row['defaultid'] ? '<span style="color:#e00">default</span>' : '');
        }
        $class = $row['active'] === '1' ? ' active' : ' inactive';

// log_message( __METHOD__ . ' - row=' . print_r( $row, 1 ), 'sl_debug.log' );

        return '<span class="timetrack' . $class . '">' . $label . '</span>';
    }


    //---------------------------------------------------------------
    // KundenID bzw. taskID beim ersten Speichern eintragen
    //---------------------------------------------------------------
    public function manageIds( $dc )
    {
        // Front end call
        if( !$dc instanceof Contao\DataContainer ) {
            return;
        }

        // Return if there is no active record (override all)
        if( !$dc->activeRecord ) {
            return;
        }

        // kundenID setzen
        if( ($dc->activeRecord->type === 'kunde') && ($dc->activeRecord->kundenID == 0) ) {
            // letzte kundenID ermitteln
            $objLast = $this->Database->execute( "SELECT max(kundenID) AS lastid FROM tl_timetracker_setting" );

            // nächste kundenID vergeben
            $arrSet = [];
            $arrSet['tstamp']   = time();
            $arrSet['kundenID'] = $objLast->lastid + 1;
            $this->Database->prepare( "UPDATE tl_timetracker_setting %s WHERE id=?" )
                           ->set( $arrSet )
                           ->execute( $dc->activeRecord->id );
        }

        // taskID setzen
        if( ($dc->activeRecord->type === 'task') && ($dc->activeRecord->taskID == 0) ) {
            // letzte taskID ermitteln
            $objLast = $this->Database->execute("SELECT max(taskID) AS lastid FROM tl_timetracker_setting");

            // nächste taskID vergeben
            $arrSet = [];
            $arrSet['tstamp'] = time();
            $arrSet['taskID'] = $objLast->lastid + 1;
            $this->Database->prepare( "UPDATE tl_timetracker_setting %s WHERE id=?" )
                           ->set( $arrSet )
                           ->execute( $dc->activeRecord->id );
        }
    }


    //---------------------------------------------------------------
}
