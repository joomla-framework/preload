<?php
/**
 * Part of the Joomla Framework Preload Package
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Preload\EventListener;

use Joomla\Application\AbstractWebApplication;
use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\Event\SubscriberInterface;
use Joomla\Preload\PreloadManager;
use Psr\Link\EvolvableLinkProviderInterface;
use Symfony\Component\WebLink\HttpHeaderSerializer;

/**
 * Asset preloading event subscriber
 *
 * @since  __DEPLOY_VERSION__
 */
class PreloadSubscriber implements SubscriberInterface
{
	/**
	 * The preload manager.
	 *
	 * @var    PreloadManager
	 * @since  __DEPLOY_VERSION__
	 */
	private $preloadManager;

	/**
	 * Event subscriber constructor.
	 *
	 * @param   PreloadManager  $preloadManager  The preload manager
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(PreloadManager $preloadManager)
	{
		$this->preloadManager = $preloadManager;
	}

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			ApplicationEvents::BEFORE_RESPOND => 'sendLinkHeader',
		];
	}

	/**
	 * Sends the link header for preloaded assets.
	 *
	 * @param   ApplicationEvent  $event  Event object
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function sendLinkHeader(ApplicationEvent $event)
	{
		/** @var AbstractWebApplication $application */
		$application = $event->getApplication();

		$linkProvider = $this->preloadManager->getLinkProvider();

		if ($linkProvider instanceof EvolvableLinkProviderInterface && $links = $linkProvider->getLinks())
		{
			$application->setHeader('Link', (new HttpHeaderSerializer)->serialize($links));
		}
	}
}
