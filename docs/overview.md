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

## Things to know before you build on this

**The manager only collects; the header comes from the subscriber.** `PreloadManager::link()` adds
a link to the provider and nothing else, despite its docblock saying "Adds a `Link` HTTP header".
The header is written by `EventListener\PreloadSubscriber` on the application's `BEFORE_RESPOND`
event. Without `joomla/event` and `symfony/web-link` installed and the subscriber registered, the
collected links have no effect.

**Nothing is validated.** `link()` accepts any URI and any attribute values and passes them to the
serializer, which writes them into a response header. If a URI can come from user input, check it
for control characters yourself before handing it over.

**No deduplication and no limit.** Calling `preload('/app.css')` twice produces two identical
entries, and there is no cap on how many links accumulate. A loop over user data can grow the
`Link` header past what proxies accept, which turns into a 502 or 431 for the whole response.

**`modulepreload` is missing.** There are helpers for `preload`, `prefetch`, `preconnect`,
`dns-prefetch` and `prerender`, but not for ES modules. Use the generic method:

```php
$preloadManager->link('/app.mjs', 'modulepreload');
```

**Fonts need `crossorigin`.** A font preloaded without it is fetched twice:

```php
$preloadManager->preload('/fonts/x.woff2', ['as' => 'font', 'crossorigin' => true]);
```

**The `suggest` version ranges are stale.** `composer.json` suggests `joomla/application ^2.0`,
`joomla/di ^1.5|^2.0`, `joomla/event ^2.0` and `symfony/web-link ^3.4|^4.4|^5.0`, while
`require-dev` pins the 4.x/7.x releases actually used. Follow `require-dev`.
