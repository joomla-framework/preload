<?php
/**
 * Part of the Joomla Framework Preload Package
 *
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Preload\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Preload\EventListener\PreloadSubscriber;
use Joomla\Preload\PreloadManager;

/**
 * Service provider for preload package services
 *
 * @since  2.0.0
 */
class PreloadProvider implements ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function register(Container $container)
    {
        $container->share(
            PreloadManager::class,
            static function (): PreloadManager {
                return new PreloadManager();
            }
        );

        $container->share(
            PreloadSubscriber::class,
            static function (Container $container): PreloadSubscriber {
                return new PreloadSubscriber(
                    $container->get(PreloadManager::class)
                );
            }
        );

        $container->tag('event.subscriber', [PreloadSubscriber::class]);
    }
}
