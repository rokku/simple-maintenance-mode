=== Simple Maintenance Mode ===
Contributors: markbridgeman
Donate link: 
Tags: maintenance mode, coming soon, maintenance page, site offline, maintenance
Requires at least: 5.0
Tested up to: 6.5.2
Requires PHP: 7.0
Stable tag: 1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple maintenance mode plugin for WordPress. Customize your maintenance page with a logo, headline, and WYSIWYG message.

== Description ==

Simple Maintenance Mode lets administrators easily enable a maintenance mode for their WordPress site. 
When enabled, non-logged-in visitors will see a custom maintenance page with:

- A custom logo
- A headline message
- A WYSIWYG text message

The WordPress admin bar turns red while maintenance mode is active, and an admin notice is displayed.

Perfect for site updates or temporary downtime, without the complexity.

== Installation ==

1. Upload the `simple-maintenance-mode` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to **Settings > Maintenance Mode** to configure.

== Frequently Asked Questions ==

= Will this affect logged-in administrators? =

No. Administrators (users with `manage_options` capability) will see the normal site.

= Can I upload a logo? =

Yes, you can upload a logo using the WordPress Media Library.

= Will search engines be affected? =

Yes. The plugin returns a 503 Service Unavailable status code while active, which tells search engines the downtime is temporary.

== Screenshots ==

1. Maintenance mode settings page
2. Example maintenance page
3. Admin bar turns red when active

== Changelog ==

= 1.1 =
* Added media uploader support for logo upload
* Replaced checkbox with styled on/off toggle
* Cleaned code for WordPress.org submission

== Upgrade Notice ==

= 1.1 =
Minor update. Added logo upload and cleaned admin UI.

== License ==

This plugin is licensed under the GPLv2 or later.
