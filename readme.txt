=== Plugin Downloads Display ===
Contributors: dantaylorseo
Tags: plugin downloads, plugin development, shortcode, developer
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 1.0


A plugin that allows you to display the number of times a plugin has been downloaded. Can either be displayed in posts with a shortcode or in templates using a function.

== Description ==

A plugin that allows you to display the number of times a plugin has been downloaded. Can either be displayed in posts with a shortcode or in templates using a function.

Shortcode: `[show_downloads slug="slug-of-plugin"]`

Function: `<?php plugin_downloads("slug-of-plugin"); ?>`

Credit Harish Chouhan [Tuts+](http://code.tutsplus.com/tutorials/communicating-with-the-wordpress-org-plugin-api--wp-33069) for original code.

== Installation ==

1. Upload `plugin-download-display` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use shortcode `[show_downloads slug="slug-of-plugin"]` in posts/pages
4. Use function `<?php plugin_downloads('slug-of-plugin'); ?>` in templates

== Changelog ==

= 1.0 =
* First edition