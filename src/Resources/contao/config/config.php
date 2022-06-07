<?php

/**
 * Extension for Contao 4
 *
 * @copyright  Softleister 2020-2022
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
*/

use Softleister\Timetracker\timetrackerTools;

timetrackerTools::getSettings();            // Settings laden

//-------------------------------------------------------------------------
// Back end modules
//-------------------------------------------------------------------------
array_insert($GLOBALS['BE_MOD'], 0, array
(
    'timetracker' => array
    (
        'timetrackerZeiten' => array
        (
            'tables'      => array('tl_timetracker_log'),
            'export'      => array('\Softleister\Timetracker\LogExport', 'exportLog'),
            'opentimes'   => array('\Softleister\Timetracker\LogExport', 'openTimes')
        ),
        'timetrackerSetting' => array
        (
            'tables'      => array('tl_timetracker_setting'),
        ),
    )
));


//-------------------------------------------------------------------------
// Style sheet
//-------------------------------------------------------------------------
if (TL_MODE == 'BE')
{
    $GLOBALS['TL_CSS'][] = 'bundles/softleistertimetracker/styles.css|static';
}


//=========================================================================
