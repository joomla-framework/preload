<?php

/**
 * Part of the Joomla Framework Preload Package
 *
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Preload;

use Fig\Link\GenericLinkProvider;
use Fig\Link\Link;
use Psr\Link\EvolvableLinkProviderInterface;

/**
 * Manager for HTTP/2 asset preloading
 *
 * @since  2.0.0
 */
class PreloadManager
{
    /**
     * The link provider
     *
     * @var    EvolvableLinkProviderInterface
     * @since  2.0.0
     */
    protected $linkProvider;

    /**
     * PreloadManager constructor
     *
     * @param   EvolvableLinkProviderInterface  $linkProvider  The link provider
     *
     * @since   2.0.0
     */
    public function __construct(?EvolvableLinkProviderInterface $linkProvider = null)
    {
        $this->linkProvider = $linkProvider ?: new GenericLinkProvider();
    }

    /**
     * Get the link provider
     *
     * @return  EvolvableLinkProviderInterface
     *
     * @since   2.0.0
     */
    public function getLinkProvider(): EvolvableLinkProviderInterface
    {
        return $this->linkProvider;
    }

    /**
     * Set the link provider
     *
     * @param   EvolvableLinkProviderInterface  $linkProvider  The link provider
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function setLinkProvider(EvolvableLinkProviderInterface $linkProvider): void
    {
        $this->linkProvider = $linkProvider;
    }

    /**
     * Adds a "Link" HTTP header.
     *
     * @param   string  $uri         The relation URI
     * @param   string  $rel         The relation type (e.g. "preload", "prefetch", "prerender" or "dns-prefetch")
     * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('pr' => 0.5)")
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function link(string $uri, string $rel, array $attributes = []): void
    {
        $link = new Link($rel, $uri);

        foreach ($attributes as $key => $value) {
            $link = $link->withAttribute($key, $value);
        }

        $this->setLinkProvider($this->getLinkProvider()->withLink($link));
    }

    /**
     * Preloads a resource.
     *
     * @param   string  $uri         The relation URI
     * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('crossorigin' => 'use-credentials')")
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function preload(string $uri, array $attributes = []): void
    {
        $this->link($uri, 'preload', $attributes);
    }

    /**
     * Resolves a resource origin as early as possible.
     *
     * @param   string  $uri         The relation URI
     * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('pr' => 0.5)")
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function dnsPrefetch(string $uri, array $attributes = []): void
    {
        $this->link($uri, 'dns-prefetch', $attributes);
    }

    /**
     * Initiates a early connection to a resource (DNS resolution, TCP handshake, TLS negotiation).
     *
     * @param   string  $uri         The relation URI
     * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('pr' => 0.5)")
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function preconnect(string $uri, array $attributes = []): void
    {
        $this->link($uri, 'preconnect', $attributes);
    }

    /**
     * Indicates to the client that it should prefetch this resource.
     *
     * @param   string  $uri         The relation URI
     * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('pr' => 0.5)")
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function prefetch(string $uri, array $attributes = []): void
    {
        $this->link($uri, 'prefetch', $attributes);
    }

    /**
     * Indicates to the client that it should prerender this resource.
     *
     * @param   string  $uri         The relation URI
     * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('pr' => 0.5)")
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function prerender(string $uri, array $attributes = []): void
    {
        $this->link($uri, 'prerender', $attributes);
    }
}
