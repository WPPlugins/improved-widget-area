=== Improved widget area ===
Contributors: 42functions
Tags: widget area, sidebars, duplicate, categories, widgets, backend, area, 42functions
Requires at least: 3.1.3
Tested up to: 3.2.1
Stable tag: 1.0

Take your widget area to the next level by categorising it.

== Description ==
Are you also tired of those endless list of sidebars in your backend? Then fear no longer, 42functions is here to rescue you! We do that be elegantly categorise your widget into a clarifying view. We do that by grouping the pre defined categories together and simply showing only the one category you want.

= Using multiple themes? =
No worries we got that covered, the plugin recognizes the theme your using and shows you the corresponding settings!
= To lazy to categorise your theme? = 
No worries we also can categorise theme for you, (if your developer uses proper prefixes). Just press the autoformat button and the plugin will start grouping your sidebars together. Please note: If this functionality doesn't work for you, don't complain at us, complain at the developer building your theme.
= My sidebars are fine, but I hate duplicated widgets! =
Well then don't use our plugin for the wonderfull categorising but use it for the sidebar duplicator. This is simply a widget containing another sidebar.

== Screenshots ==
1. Screenshot of the backend settings. Categorising your sidebars is easy by just dragging them to the right category.
2. This is how your backend widget page will look like.

== Developer ==
You might have notice'd we'd implemented a autoformat button. We'de like to share our knowlege with you so you can be as lazy as you can get when activating this plugin. So wake up and take some coffee because this is how it works:

1. We loop trough all registered sidebars.
1. When a sidebar has a 'category' field we take that as our active sidebar.
1. We split the id of the sidebar on a underscore (_) or a hyphen (-), when the splitting actually works the first group is considered as the category.
1. Your sidebar is listed under the other sidebars.

Example of id conversions:

* post-archive-header | Will result in category: post
* post_archive_header | Will also result in category: post
* postarchiveheader | Will result in category: other

== Installation ==

Download, Upgrading, Installation:

**Upgrade**

1. First deactivate the Improved widget area plugin
1. Remove the `xlii-improved-widget-area` directory

**Install**

1. Unzip the `xlii-improved-widget-area.zip` file. 
1. Upload the the `xlii-improved-widget-area` folder (not just the files in it!) to your `wp-contents/plugins` folder. If you're using FTP, use 'binary' mode.

**Activate**

1. In your WordPress administration, go to the Plugins page
1. Activate the Improved widget area plugin

If you find any bugs or have any ideas, please mail us.
