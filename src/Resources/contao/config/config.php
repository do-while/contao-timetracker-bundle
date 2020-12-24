<?php

/**
 * Extension for Contao 4
 *
 * @copyright  Softleister 2020
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 * @licence    LGPL
*/

\Softleister\Timetracker\timetrackerTools::getSettings();            // Settings laden

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
        ),
        'timetrackerSetting' => array
        (
            'tables'      => array('tl_timetracker_setting'),
        ),
        // 'timetrackerImportExport' => array
        // (
        //     'callback'   => 'Softleister\Timetracker\importExport',
        // ),
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
