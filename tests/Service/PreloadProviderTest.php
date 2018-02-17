<?php
/**
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Preload\Tests\Service;

use Joomla\DI\Container;
use Joomla\Preload\PreloadManager;
use Joomla\Preload\Service\PreloadProvider;
use PHPUnit\Framework\TestCase;

/**
 * Test class for \Joomla\Preload\Service\PreloadProvider
 */
class PreloadProviderTest extends TestCase
{
	/**
	 * @testdox  The services are registered to the container
	 */
	public function testServiceRegistration()
	{
		$container = (new Container)
			->registerServiceProvider(new PreloadProvider);

		$this->assertTrue($container->has(PreloadManager::class));
	}
}
