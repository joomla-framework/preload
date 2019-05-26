## Overview

The Preload package provides a set of utility classes for managing HTTP/2 preload relations.

### Usage

The [`Joomla\Preload\PreloadManager`](classes/PreloadManager.md) class is used to assemble the data to build a "Link" header to include in your application's response with all HTTP/2 related resources and their relations (actions to be taken).

The [`Joomla\Preload\EventListener\PreloadSubscriber`](classes/EventListener/PreloadSubscriber.md) class is used to register an event listener for any `Joomla\Application\AbstractWebApplication` to attach this "Link" header to your response.

The [`Joomla\Preload\Service\PreloadProvider`](classes/Service/PreloadProvider.md) class is used to integrate these classes into your application's dependency injection container when using `Joomla\DI\Container`.

### Example Integration

The below example assumes you are using Twig in your application, have registered the `PreloadProvider` to your dependency injection container to expose the manager service and send the "Link" header, and you want to specify your CSS or JavaScript resources have a HTTP/2 relation on them.

```twig
<link href="{{ http2_link('media/css/app.css', 'preload', {'as' => 'stylesheet'}) }}" rel="stylesheet">
<script src="{{ http2_link('media/js/app.js', 'preload', {'as' => 'script'}) }}"></script>
```

```php
use Joomla\Preload\PreloadManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class PreloadExtension extends AbstractExtension
{
	private $preloadManager;
	
	public function __construct(PreloadManager $preloadManager)
	{
		$this->preloadManager = $preloadManager;
	}
	
	public function getFunctions()
	{
		return [
			new TwigFunction('http2_link', [$this, 'linkAsset']),
		];
	}
	
	/**
	 * Preload a resource
	 *
	 * @param   string  $uri         The URI for the resource to preload
	 * @param   string  $linkType    The preload method(s) to apply
	 * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('pr' => 0.5)")
	 *
	 * @return  string  The resource URI, useful for chaining this function with another Twig function in your template
	 */
	public function linkAsset(string $uri, string $linkType = 'preload', array $attributes = []): string
	{
		$this->preloadManager->link($uri, $linkType, $attributes);

		return $uri;
	}
```
