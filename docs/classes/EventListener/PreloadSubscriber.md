## Joomla\Preload\EventListener\PreloadSubscriber

The `PreloadSubscriber` class is an event subscriber which hooks the `Joomla\Application\ApplicationEvents::BEFORE_RESPOND` event to add a "Link" header with HTTP/2 resources to your application's response.

### Instantiating the class

The event subscriber requires a [`Joomla\Preload\PreloadManager`](../PreloadManager.md) instance when it is instantiated.

```php
use Joomla\Preload\EventListener\PreloadSubscriber;
use Joomla\Preload\PreloadManager;

$manager = new PreloadManager;

$subscriber = new PreloadSubscriber($manager);
```

### Adding to an event dispatcher

Since the `PreloadSubscriber` implements `Joomla\Event\SubscriberInterface`, the class can be added to a `Joomla\Event\DispatcherInterface` instance through its `addSubscriber()` method.

```php
use Joomla\Event\Dispatcher;
use Joomla\Preload\EventListener\PreloadSubscriber;
use Joomla\Preload\PreloadManager;

$manager = new PreloadManager;

$subscriber = new PreloadSubscriber($manager);

$dispatcher = new Dispatcher;
$dispatcher->addSubscriber($subscriber);
```

### `joomla/di` integration

If using the `Joomla\DI\Container` as your application's service container, the [`Joomla\Preload\Service\PreloadProvider`](../Service/PreloadProvider.md) tags the subscriber with the "event.subscriber" tag. Therefore, when creating your dispatcher service, you can fetch this subscriber (and any others with the same tag) using the `Container::getTagged()` method.

```php
use Joomla\DI\Container;
use Joomla\Event\Dispatcher;
use Joomla\Event\DispatcherInterface;
use Joomla\Preload\Service\PreloadProvider;

$container = new Container;
$container->registerServiceProvider(new PreloadProvider);

$container->alias(Dispatcher::class, DispatcherInterface::class)
	->share(
		DispatcherInterface::class,
		function (Container $container)
		{
			$dispatcher = new Dispatcher;

			// Fetch all services with the "event.subscriber" tag, inherently this means all subscribers must be tagged before your dispatcher is created
			foreach ($container->getTagged('event.subscriber') as $subscriber)
			{
				$dispatcher->addSubscriber($subscriber);
			}

			return $dispatcher;
		}
	);
```
