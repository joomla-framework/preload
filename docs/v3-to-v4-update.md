# Updating from v3 to v4

Release 4.0.0 raises the PHP requirement and moves to `psr/link` 2.0. Nothing changed in `src/`.

## At a glance

| | v3 (3.0.1) | v4 (4.0.0) |
|---|---|---|
| PHP | `^8.1.0` | `^8.3.0` |
| `psr/link` | `^1.0` | `^2.0` |
| Public API | — | unchanged |

## Minimum supported PHP version raised

All Framework packages now require **PHP 8.3** or newer.

## No API changes in this package

`git diff 3.0.1 HEAD -- src/` is empty. `PreloadManager`, `PreloadSubscriber` and `PreloadProvider`
are byte-for-byte identical to 3.0.1.

## `psr/link` 2.0

PSR-13 2.0 adds return types to the interfaces:

```php
// psr/link 1.x
public function getLinks();
public function withLink(LinkInterface $link);

// psr/link 2.0
public function getLinks(): iterable;
public function withLink(LinkInterface $link): static;
```

This only matters if you **implement** `LinkInterface`, `LinkProviderInterface` or
`EvolvableLinkProviderInterface` yourself and pass that implementation to
`PreloadManager::setLinkProvider()`. Add the return types to match.

Consuming the interfaces — the normal case, where you pass the `Fig\Link\GenericLinkProvider` that
the manager creates by default — needs no change.

## Dependency changes

| Package | v3 (3.0.1) | v4 (4.0.0) |
|---|---|---|
| `php` | `^8.1.0` | `^8.3.0` |
| `psr/link` | `^1.0` | `^2.0` |
| `fig/link-util` | `^1.0` | unchanged |

Optional packages in `suggest` moved to their 4.x releases: `joomla/application`, `joomla/di` and
`joomla/event`. Note that their `suggest` texts still name 2.x/3.x version ranges — read the
`require-dev` block for the versions actually tested against.
