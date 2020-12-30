<?php

/**
 * Extension for Contao 4
 *
 * @copyright  Softleister 2020
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
*/

$GLOBALS['TL_DCA']['tl_timetracker_log'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
        'enableVersioning'            => true,
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
			'mode'                    => 2,
			'flag'                    => 6,
			'fields'                  => array('datum DESC'),
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('datum', 'dauer', 'kunde', 'aufgabe'),
			'format'				  => '%s / %s / %s / %s',
			'label_callback'          => array('tl_timetracker_log', 'logLabel')
		),
		'global_operations' => array
		(
			'export' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_timetracker_log']['export'],
				'href'                => 'key=export',
				'class'               => 'header_xls_export',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			),
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_timetracker_log']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_timetracker_log']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_timetracker_log']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_timetracker_log']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			),
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'				      => '{kunde_legend},kunde,aufgabe;'
										.'{zeit_legend},datum,dauer,startzeit;'
										.'{aufgabe_legend},beschreibung,noinvoice,nostop;'
										.'{user_legend},username,member;'
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
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
		),
//--------
		'kunde' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_log']['kunde'],
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'inputType'               => 'select',
			'options_callback'        => array('tl_timetracker_log', 'getTimetrackerKunden'),
			'load_callback'			  => array(
											array( 'tl_timetracker_log', 'checkFilterKunde' )
			  							 ),
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'tl_class'=>'clr w50', 'includeBlankOption'=>true),
			'sql'                     => "varchar(80) NOT NULL default ''"
		),
        'aufgabe' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_log']['aufgabe'],
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'inputType'               => 'select',
			'options_callback'        => array('tl_timetracker_log', 'getTimetrackerAufgaben'),
			'load_callback'			  => array(
											array( 'tl_timetracker_log', 'checkFilterAufgabe' )
			  							 ),
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'tl_class'=>'w50', 'includeBlankOption'=>true),
			'sql'                     => "varchar(11) NOT NULL default ''"
		),
//--------
		'datum' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_log']['datum'],
			'default'                 => time(),
			'filter'                  => true,
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'load_callback' 		  => array (
											array('tl_timetracker_log', 'loadDate')
										 ),
			'sql'                     => "varchar(11) NOT NULL default ''"
		),
		'dauer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_log']['dauer'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'clr w50'),
			'sql'                     => "varchar(5) NOT NULL default ''"
		),
        'startzeit' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_log']['startzeit'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'time', 'doNotCopy'=>true, 'tl_class'=>'w50'),
			'load_callback'			  => array (
											array('tl_timetracker_log', 'loadTime')
										 ),
			'sql'                     => "varchar(6) NOT NULL default ''"
		),
//--------
		'beschreibung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_log']['beschreibung'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'search'                  => true,
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
        'noinvoice' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_log']['noinvoice'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'m12 w50'),
			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'nostop' => array
        (
			'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_log']['nostop'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'m12 w50'),
			'sql'                     => "char(1) NOT NULL default ''"
        ),
//--------
		'username' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_log']['username'],
			'default'                 => Contao\BackendUser::getInstance()->id,
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('doNotCopy'=>true, 'mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default 0",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
		'member' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_timetracker_log']['member'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_member.username',
			'eval'                    => array('doNotCopy'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default 0",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
	)
);


//--- Klasse tl_timetracker_log ---
class tl_timetracker_log extends \Backend
{
	protected $arrKunden = [];
	protected $arrAufgaben = [];

	/**
	 * Import the back end user object
	 */
	public function __construct( )
	{
		parent::__construct( );
		$this->import( BackendUser::class, 'User' );

		$this->arrKunden = $this->getTimetrackerKunden( );
		$this->arrAufgaben = $this->getTimetrackerAufgaben( );
    }
    
	//---------------------------------------------------------------
	//  Datum formatieren
	//---------------------------------------------------------------
	public function loadDate( $value )
	{
		return strtotime( date('Y-m-d', $value) . ' 00:00:00' );
	}

	//---------------------------------------------------------------
	//  Zeit formatieren
	//---------------------------------------------------------------
	public function loadTime( $value )
	{
		return strtotime( '1970-01-01 ' . date('H:i:s', $value) );
	}


	//---------------------------------------------------------------
	// Feld 'Kunde' vorbelegen mit dem Filterwert
	//---------------------------------------------------------------
	public function checkFilterKunde( $varValue )
	{
		// Do not change the value if it has been set already
		if( ($varValue > 0) || Contao\Input::post('FORM_SUBMIT') == 'tl_timetracker_log' ) {
			return $varValue;
		}

		/** @var Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $objSessionBag */
		$objSessionBag = Contao\System::getContainer()->get('session')->getBag('contao_backend');
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
		if( ($varValue > 0) || Contao\Input::post('FORM_SUBMIT') == 'tl_timetracker_log' ) {
			return $varValue;
		}

		/** @var Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $objSessionBag */
		$objSessionBag = Contao\System::getContainer()->get('session')->getBag('contao_backend');
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
