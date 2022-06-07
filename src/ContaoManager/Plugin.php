<?php

/**
 * Extension for Contao 4
 *
 * @copyright  Softleister 2020-2022
 * @author     Softleister <info@softleister.de>
 * @package    contao-timetracker-bundle
 */

namespace Softleister\TimetrackerBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;


/**
 * Plugin for the Contao Manager.
 *
 * @author Softleister
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles( ParserInterface $parser )
    {
        return [
            BundleConfig::create( 'Softleister\TimetrackerBundle\SoftleisterTimetrackerBundle' )
                ->setLoadAfter( ['Contao\CoreBundle\ContaoCoreBundle'] ),
        ];
    }
}
