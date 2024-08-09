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

use Contao\System;
use Symfony\Component\HttpFoundation\Request;
use Softleister\Timetracker\timetrackerTools;

timetrackerTools::getSettings( );           // Settings laden

//-------------------------------------------------------------------------
// Back end modules
//-------------------------------------------------------------------------
$GLOBALS['BE_MOD']['timetracker'] = [
    'timetrackerZeiten' => [
        'tables'      => ['tl_timetracker_log'],
        'export'      => ['\Softleister\Timetracker\LogExport', 'exportLog'],
        'opentimes'   => ['\Softleister\Timetracker\LogExport', 'openTimes'],
        'javascript'  => ['bundles/softleistertimetracker/timetracker.js'],
    ],
    'timetrackerSetting' => [
        'tables'      => ['tl_timetracker_setting'],
    ],
];


//-------------------------------------------------------------------------
// Style sheet
//-------------------------------------------------------------------------
if( System::getContainer( )->get( 'contao.routing.scope_matcher' )
                           ->isBackendRequest( System::getContainer( )->get( 'request_stack' )
                           ->getCurrentRequest( ) ?? Request::create(''))) {

    $GLOBALS['TL_CSS'][] = 'bundles/softleistertimetracker/styles.css|static';
}


//=========================================================================
