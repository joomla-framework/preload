<?php
/**
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Preload\Tests;

use Fig\Link\Link;
use Joomla\Preload\PreloadManager;
use PHPUnit\Framework\TestCase;

/**
 * Test class for \Joomla\Preload\PreloadManager
 */
class PreloadManagerTest extends TestCase
{
	/**
	 * @var  PreloadManager
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	protected function setUp(): void
	{
		$this->object = new PreloadManager;
	}

	/**
	 * @testdox  A Link header for the specified relations is added
	 */
	public function testLink()
	{
		$this->object->link('/foo.css', 'preload prefetch', ['as' => 'style', 'crossorigin' => true]);

		$link = (new Link('preload prefetch', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

		$this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
	}

	/**
	 * @testdox  A Link header for the preload relation is added
	 */
	public function testPreload()
	{
		$this->object->preload('/foo.css', ['as' => 'style', 'crossorigin' => true]);

		$link = (new Link('preload', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

		$this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
	}

	/**
	 * @testdox  A Link header for the dns-prefetch relation is added
	 */
	public function testDnsPrefetch()
	{
		$this->object->dnsPrefetch('/foo.css', ['as' => 'style', 'crossorigin' => true]);

		$link = (new Link('dns-prefetch', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

		$this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
	}

	/**
	 * @testdox  A Link header for the preconnect relation is added
	 */
	public function testPreconnect()
	{
		$this->object->preconnect('/foo.css', ['as' => 'style', 'crossorigin' => true]);

		$link = (new Link('preconnect', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

		$this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
	}

	/**
	 * @testdox  A Link header for the prefetch relation is added
	 */
	public function testPrefetch()
	{
		$this->object->prefetch('/foo.css', ['as' => 'style', 'crossorigin' => true]);

		$link = (new Link('prefetch', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

		$this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
	}

	/**
	 * @testdox  A Link header for the prerender relation is added
	 */
	public function testPrerender()
	{
		$this->object->prerender('/foo.css', ['as' => 'style', 'crossorigin' => true]);

		$link = (new Link('prerender', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

		$this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
	}
}
