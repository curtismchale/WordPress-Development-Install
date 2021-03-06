=== Debug Bar Transients ===
Contributors: ocean90
Tags: debug bar, transients, debug
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VR8YU922B7K46
Requires at least: 3.3
Tested up to: 3.5
Stable tag: 0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Debug Bar Transients adds a new panel to Debug Bar that displays information about WordPress transients.

== Description ==

Debug Bar Transients adds information about WordPress Transients to a new panel in the Debug Bar. This plugin is an extension for [Debug Bar](http://wordpress.org/extend/plugins/debug-bar/) and thus is dependent upon Debug Bar being installed for it to work properly.

Once installed, you will have access to the following information:

* Number of existing transients
* List of custom transients
* List of core transients
* List of custom site transients
* List of core site transients
* An option to delete a transient

**Sounds pretty good? Install now!**

*This plugin is inspired by the [Debug Bar Cron](http://wordpress.org/extend/plugins/debug-bar-cron/) plugin.*

= Feedback = 
If you want, you can drop me a line @[ocean90](http://twitter.com/ocean90) on Twitter or @[Dominik Schilling](https://plus.google.com/u/0/101675293278434581718/) on Google+.

= More =
Try also some of my [other plugins](http://profiles.wordpress.org/users/ocean90) or visit my site [wpGrafie.de](http://wpgrafie.de/).

== Screenshots ==

1. How it will look.

== Frequently Asked Questions ==

= What are WordPress Transients? =
The Transients API is very similar to the Options API but with the added feature of an expiration time, which simplifies the process of using the wp_options database table to store cached information. [Continue reading](http://codex.wordpress.org/Transients_API).

== Installation ==

Note: There will be NO settings page.

For an automatic installation through WordPress:

1. Go to the 'Add New' plugins screen in your WordPress admin area
1. Search for 'Debug Bar Transients'
1. Click 'Install Now' and activate the plugin


For a manual installation via FTP:

1. Upload the `debug-bar-transients` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' screen in your WordPress admin area


To upload the plugin through WordPress, instead of FTP:

1. Upload the downloaded zip file on the 'Add New' plugins screen (see the 'Upload' tab) in your WordPress admin area and activate.

== Changelog ==
= 0.3.0 =
* Fixed a missing pre close tag
* Fixed the height and width for the values in Opera and Firefox
* New: Number of invalid transients beside the total transients number

= 0.2.0 =
* Removed debug cruft
* Some clean up

= 0.1.0 =
* First release.
