## Joomla\Preload\Service\PreloadProvider

The `PreloadProvider` class is a `Joomla\DI\Container` service provider which registers the preload manager and event subscriber as services in your application's dependency injection container.

### Usage

The service provider requires no arguments to be instantiated and can be registered to the container through its `registerServiceProvider()` method.

```php
use Joomla\DI\Container;
use Joomla\Preload\Service\PreloadProvider;

$container = new Container;
$container->registerServiceProvider(new PreloadProvider);
```

### Services

The provider registers the following service keys:

* "Joomla\Preload\PreloadManager" - a [`PreloadManager`](../PreloadManager.md) instance
* "Joomla\Preload\EventListener\PreloadSubscriber" - a [`PreloadSubscriber`](../EventListener/PreloadSubscriber.md) instance
	* This service has the "event.subscriber" tag as well
