## Joomla\Preload\PreloadManager

The `PreloadManager` manages adding links for HTTP/2 resources to a response. This manager is used for adding resources to a `Link` header to be sent with your response.

### Instantiating the class

This class requires an implementation of the PSR-13 [`Psr\Link\EvolvableLinkProviderInterface`](https://www.php-fig.org/psr/psr-13/#32-psrlinkevolvablelinkinterface), if one is not provided when the class is instantiated then the [`Fig\Link\GenericLinkProvider`](https://github.com/php-fig/link-util/blob/master/src/GenericLinkProvider.php) is used.

```php
use Joomla\Preload\PreloadManager;

// Uses default EvolvableLinkProviderInterface
$manager = new PreloadManager;

// Uses custom link provider
$linkProvider = /* new Psr\Link\EvolvableLinkProviderInterface implementation */
$manager = new PreloadManager($linkProvider);
```

### Interface with link provider

#### Get the link provider

The `getLinkProvider()` method retrieves the link provider currently in use by the manager.

```php
/**
 * @return  EvolvableLinkProviderInterface
 */
public function getLinkProvider(): EvolvableLinkProviderInterface;
```

#### Replace the link provider

The `setLinkProvider()` method replaces the link provider currently in use by the manager with the given provider.

```php
/**
 * @param   EvolvableLinkProviderInterface  $linkProvider  The link provider
 *
 * @return  void
 */
public function setLinkProvider(EvolvableLinkProviderInterface $linkProvider);
```

### Adding links

#### Preload a resource

The `preload()` method is a shortcut method for specifying a resource should have the "preload" relation.

```php
/**
 * @param   string  $uri         The relation URI
 * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('crossorigin' => 'use-credentials')")
 *
 * @return  void
 *
 * @since   __DEPLOY_VERSION__
 */
public function preload(string $uri, array $attributes = []);
```

#### DNS Prefetch a resource

The `dnsPrefetch()` method is a shortcut method for specifying a resource should have the "dns-prefetch" relation.

```php
/**
 * @param   string  $uri         The relation URI
 * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('crossorigin' => 'use-credentials')")
 *
 * @return  void
 *
 * @since   __DEPLOY_VERSION__
 */
public function dnsPrefetch(string $uri, array $attributes = []);
```

#### Preconnect to a resource

The `preconnect()` method is a shortcut method for specifying a resource should have the "preconnect" relation.

```php
/**
 * @param   string  $uri         The relation URI
 * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('crossorigin' => 'use-credentials')")
 *
 * @return  void
 *
 * @since   __DEPLOY_VERSION__
 */
public function preconnect(string $uri, array $attributes = []);
```

#### Prefetch a resource

The `prefetch()` method is a shortcut method for specifying a resource should have the "prefetch" relation.

```php
/**
 * @param   string  $uri         The relation URI
 * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('crossorigin' => 'use-credentials')")
 *
 * @return  void
 *
 * @since   __DEPLOY_VERSION__
 */
public function prefetch(string $uri, array $attributes = []);
```

#### Prerender a resource

The `prerender()` method is a shortcut method for specifying a resource should have the "prerender" relation.

```php
/**
 * @param   string  $uri         The relation URI
 * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('crossorigin' => 'use-credentials')")
 *
 * @return  void
 *
 * @since   __DEPLOY_VERSION__
 */
public function prerender(string $uri, array $attributes = []);
```

#### Add a resource

The `link()` method is used for adding a link to a resource with its relations.

```php
/**
 * @param   string  $uri         The relation URI
 * @param   string  $rel         The relation type (e.g. "preload", "prefetch", "prerender" or "dns-prefetch")
 * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('crossorigin' => 'use-credentials')")
 *
 * @return  void
 *
 * @since   __DEPLOY_VERSION__
 */
public function link(string $uri, string $rel, array $attributes = []);
```
