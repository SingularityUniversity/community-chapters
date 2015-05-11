=== GravityView - Social Sharing ===
Tags: gravityview
Requires at least: 3.3
Tested up to: 4.2
Stable tag: trunk
Contributors: katzwebservices
License: GPL 3 or higher

Add social sharing links to Views or entries.

== Installation ==

1. Upload plugin files to your plugins folder, or install using WordPress' built-in Add New Plugin installer
2. Activate the plugin
3. Follow the instructions

== Changelog ==

= 0.3 on April 7 =
* Fixed: Fatal Error when going to an entry URL and there was no entry retrieved
* Fixed: PHP errors in the Admin when creating a new Post

= 0.2 on March 24 =
* Make Jetpack Open Graph work properly
* Fixed: Set single entry title
* Fixed: Infinite loop filter issue (requires 1.7.3)
* Added: `gravityview_social_get_title()` function to get the right title for the sharing functions
* Changed: Allow Jetpack global checkbox in View
* Fixed: Don't hide Jetpack sharing for non-Views

= 0.1 = 
* Launch