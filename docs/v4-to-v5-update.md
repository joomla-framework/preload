## Updating from v4 to v5

The following changes were made to the Preload package between v4 and v5.

### `PreloadManager::prerender()` removed

`Joomla\Preload\PreloadManager::prerender()`, deprecated in 4.x, has been removed. Browsers no longer prerender pages
for the "prerender" relation: Chromium only prefetches the resource and replaced prerendering with the
[Speculation Rules API](https://developer.mozilla.org/en-US/docs/Web/API/Speculation_Rules_API), and Firefox and Safari
never supported it.

Use the Speculation Rules API to prerender pages. If you still need the header entry, add it with `link()`:

```php
// v4
$preloadManager->prerender('/next-page');

// v5
$preloadManager->link('/next-page', 'prerender');
```
