=== Native Lazyload + Polyfill ===
Contributors: nico23
Donate link: https://nextgenthemes.com/donate/
Tags: Lazyload, Images, Iframe, Embed, Loading, Polyfill
Requires at least: 4.4.0
Tested up to: 5.2.2
Requires PHP: 7.0
Stable tag: 1.1.0
License: GPL-3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds native lazyloading to all images and embeds (Chrome) and adds a polyfill to make it work in all browsers.

## Changelog ##

### 2019-10-11 - 1.1.0 ###

* Fix: Never wrap a element if its already inside any `noscript` tag.

### 2019-10-07 - 1.0.3 ###

* Fix: Recent releases did not contain vendor folder.

### 2019-10-07 - 1.0.1 ###

* Improved: Put `load="eager"` on the first element in `the_content`.

### 2019-09-28 - 0.9.13 ###

* Improved: Allow duplicate IDs in HTML

### 2019-09-01 - 0.9.12 ###

* Fix: Release did not contain vendor folder. (Release automation script bug)

### 2019-09-01 - 0.9.11 ###

* Improved: better variable name for some code.

### 2019-09-01 - 0.9.10 ###

* Improved: Do not fail on weird data types, just cast to string.

### 2019-08-30 - 0.9.9 ###

* Fixed: Prevent return of `null` on `the_content` filter.
* Improved: Return early if there is no HTML to be filtered.

### 2019-08-30 - 0.9.8 ###

* Improved: Make it work everywhere, skip the `wp_body_open` thing for now.

### 2019-08-30 - 0.9.7 ###

* Fixed: Switched HTML Parsing lib to `ivopetkov/html5-dom-document-php` native and other libs have many issues and are poorly maintained. **This means PHP 7.0+ is needed now**
* Improved: Added more filters.

### 2019-08-28 - 0.9.3 ###

* Fixed: Picture tag noscript inner wrapping was not correctly working, `QueryPath` is now used for HTML modification as PHP DOM and other libraries I tried choked on HTML5 and (nested) noscript tags.

### 2019-08-28 - 0.9.2 ###

* Improved: Filtering `the_content`, at very late time.
* Improved: Cachebust JS if `WP_DEBUG` is on.

### 2019-08-28 - 0.9.1 ###

* Release

## Description ##

Minimalistic lazyloading, loads images as they come into view. Adds native lazyloading to all images and embeds (Chrome) and adds a polyfill (1.07 KiB gzipped) to make it work in all browsers.

* [Homepage](https://nextgenthemes.com/plugins/native-lazyload-polyfill/)
* [Gitlab](https://gitlab.com/nnico/native-lazyload-polyfill)

### How it works technically (if you care) ###

* Adds `loading="lazy"` to all `<img>` and `<iframe>` inside `the_content` and other areas.
* Also wraps `<img>`, `<iframe>` and the content of `<pictures>` in `<noscript>`.
* Adds the [loading-attribute-polyfill](https://github.com/mfranzke/loading-attribute-polyfill) to do its magic in JavaScript.

Feedback/Help is very welcome.

## Installation ##

Please refer to [codex.wordpress.org/Managing_Plugins#Automatic_Plugin_Installation](https://codex.wordpress.org/Managing_Plugins#Automatic_Plugin_Installation).

## Frequently Asked Questions ##



## Screenshots ##
