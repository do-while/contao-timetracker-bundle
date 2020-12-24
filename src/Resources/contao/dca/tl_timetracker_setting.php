<?php

/**
 * Extension for Contao 4
 *
 * @copyright  Softleister 2020
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
*/

$GLOBALS['TL_DCA']['tl_timetracker_setting'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        // 'closed'                      => isset( $GLOBALS['timetracker'] ),            // nur 1 Datensatz!
        // 'notDeletable'                => true,
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
			'format'				  => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['title'],
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
		'__selector__'				  => ['type'],
		'default'                     => '{type_legend},type',
		'kunde'						  => '{type_legend},type;'
										.'{kunden_legend},kundenname,kundennr,agentur,stundensatz;'
										.'{detail_legend},beschreibung;'
										.'{activate_legend},active',
		'task'						  => '{type_legend},type;'
										.'{task_legend},aufgabe,abrechnung,taskID;'
										.'{detail_legend},beschreibung;'
										.'{activate_legend},active'
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
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['type'],
			'default'                 => 'text',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'				  => ['kunde', 'task'],
			'reference'               => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['type_'],
			'eval'                    => ['mandatory'=>true, 'includeBlankOption'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(16) NOT NULL default ''"
		),
		'beschreibung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['beschreibung'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['rte'=>'tinyMCE', 'helpwizard'=>true],
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NULL"
		),
		'active' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['active'],
			'default'				  => '1',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
		),
//--- Kunde/Projekt ---
		'kundenname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['kundenname'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'kundennr' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['kundennr'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>8, 'tl_class'=>'w50'],
			'sql'                     => "varchar(8) NOT NULL default ''"
		),
		'kundenID' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'agentur' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['agentur'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'stundensatz' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['stundensatz'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>8, 'tl_class'=>'w50'],
			'sql'                     => "varchar(8) NOT NULL default ''"
		),
//--- Aufgabe/Dienstleistung ---
		'aufgabe' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['aufgabe'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'taskID' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'abrechnung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_setting']['abrechnung'],
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
class tl_timetracker_setting extends \Backend
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
	//  Add an image to each page in the tree
	// 
	//  @param array  $row
	//  @param string $label
	// 
	//  @return string
    //---------------------------------------------------------------
	public function getLabel( $row, $label )
	{
		if( $row['type'] === 'kunde' ) {
			$label = '<span>Kunde/Projekt:</span><span>' . $row['kundenname'] . ' / ' . $row['agentur'] . ' (' . $row['kundennr'] . ')</span>';
		}
		else {
			$label = '<span>Aufgabe:</span><span>' . $row['aufgabe'] . '</span>';
		}
		$class = $row['active'] === '1' ? ' active' : ' inactive';

// log_message( __METHOD__ . ' - row=' . print_r( $row, 1 ), 'sl_debug.log' );

		return '<span class="timetrack' . $class . '">' . $label . '</span>';
	}


	//---------------------------------------------------------------
}
