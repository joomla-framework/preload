<?php

/**
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Preload\Tests\EventListener;

use Fig\Link\Link;
use Joomla\Application\AbstractApplication;
use Joomla\Application\AbstractWebApplication;
use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\Preload\EventListener\PreloadSubscriber;
use Joomla\Preload\PreloadManager;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Link\EvolvableLinkProviderInterface;

/**
 * Test class for \Joomla\Preload\EventListener\PreloadSubscriber
 */
class PreloadSubscriberTest extends TestCase
{
    /**
     * @testdox  A Link header is enqueued to the application
     */
    public function testAddHeaderToApplication()
    {
        $link = (new Link('preload', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

        $provider = $this->createMock(EvolvableLinkProviderInterface::class);
        $provider->expects($this->once())
            ->method('getLinks')
            ->willReturn([$link]);

        $manager = $this->createMock(PreloadManager::class);
        $manager->expects($this->once())
            ->method('getLinkProvider')
            ->willReturn($provider);

        $application = new TestAbstractWebApplication();

        $event = $this->createMock(ApplicationEvent::class);
        $event->expects($this->once())
            ->method('getApplication')
            ->willReturn($application);

        (new PreloadSubscriber($manager))->sendLinkHeader($event);

        $this->assertSame(
            [['name' => 'Link', 'value' => '</foo.css>; rel="preload"; as="style"; crossorigin']],
            $application->getHeaders()
        );
    }

    #[TestDox('An application that is not a web application is ignored')]
    #[DoesNotPerformAssertions]
    public function testNonWebApplicationIsIgnored()
    {
        $manager = new PreloadManager();
        $manager->preload('/foo.css', ['as' => 'style']);

        $event = new ApplicationEvent(ApplicationEvents::BEFORE_RESPOND, new TestAbstractApplication());

        // Calling setHeader() on this application would be a call to an undefined method
        (new PreloadSubscriber($manager))->sendLinkHeader($event);
    }
}

/**
 * Application without response headers
 */
class TestAbstractApplication extends AbstractApplication
{
    protected function doExecute()
    {
    }
}

/**
 * Class TestAbstractWebApplication
 *
 * To have an instance of the class to test
 *
 * @package  Joomla\Preload\Tests\EventListener
 * @since    1.0
 */
class TestAbstractWebApplication extends AbstractWebApplication {
    protected function doExecute()
    {
        // TODO: Implement doExecute() method.
    }
}
