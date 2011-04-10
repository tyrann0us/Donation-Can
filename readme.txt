=== Donation Can ===
Contributors: jarkkolaine
Donate link: http://treehouseapps.com/donation-can
Tags: donations, paypal, fundraising, money
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 1_5_0

Collect PayPal donations towards multiple goals on your blog and show the progress to your visitors using sidebar widgets.

== Description ==

Donation Can is a WordPress plugin that lets you set goals (in dollars) and collect donations to meet them. Donations are
collected through PayPal and can be tracked inside your WordPress admin area separately for each goal.

Key features:

*   Define goals with name, description and amount of money required to meet the goal. For example "Help me buy a MacBook Pro" or "Save the rainforests."
*   Use WordPress widgets to define how and where the donation forms and progress bars are shown in your blog layout.
*   Be notified when someone makes a donation to one of your goals.
*   Follow the progress and browse donations inside the WordPress admin area.
*   Get a quick glance at the overall status of your donations using the Donation Can dashboard widget.
*   Customize (almost) everything.

The Plugin is completely FREE, but if you find it useful, it would be great if you took time to check out [Train for Humanity](http://trainforhumanity.org/) and help us support peace and development in Darfur by [sponsoring my training](http://trainforhumanity.org/author/jarkko). 

== Installation ==

Donation Can installation follows the standard way of most WordPress plugins:

1. Upload files to your `/wp-content/plugins/` directory (preserve sub-directory structure)
1. Activate the plugin through the 'Plugins' menu in WordPress

After the plugin has been installed, set up your PayPal information on the plugin settings page and define your first goal.

== Embedding Donation Forms to Pages and Posts ==

You can use the [donation-can donation_goal] quick tag to embed donation forms to your WordPress posts and pages.

The quick tag requires one parameter ("donation_goal"), which is one of the donation goals you have created in the plugin settings. In addition to that mandatory parameter, you can use the following parameters:

* show_progress: Set to "true" or "false" (default = "true")
* show_description: Set to "true" or "false" (default = "true")
* show_donations: Set to "true" or "false" (default = "false")
* show_title: Set to "true" or "false" (default = "true") 

These parameters are used as key-value pairs, e.g. "show_progress=false"

Example:

[donation-can coffee show_progress=false]


== Frequently Asked Questions ==

If your question wasn't answered here, visit the [official plugin page](http://jarkkolaine.com/plugins/donation-can/) and leave a comment and I will answer your question. If the question is common, it will be added to this page in the next update.

== Screenshots ==

Screenshots go here


== Changelog ==

= 1.4.4 =
* Bug fix: Added "flush rules" to make sure the permalink settings are activated correctly. This should fix the donations not appearing bug for most (if not all) users.

= 1.4.3 =
* Bug fix: Donations made to deleted goals are not shown anymore (they are still kept in the database as history data, so you can access them with MySQL)
* Bug fix: Paths to plugin files should work for all users now

= 1.4.2 =
* Added support for PATHINFO type permalinks in IPN notification handling

= 1.4.1 =
* Quick update to fix a small mistake that had passed the testing unnoticed. Goal progress is now visible again.

= 1.4 =
* Improved PayPal IPN handling that should remove most (if not all) of the problems with payments not being stored to the database.
* New setting for testing the plugin in PayPal Sandbox mode
* Support for multiple currencies
* Offline donations

= 1.3.1 =
* Minor update to fix the user capabilities required for accessing settings pages.

= 1.3 =
* Fixed the PayPal URL in PayPal payment confirmations to fix a problem with some web hosts that were unable to connect to the SSL protocol.

= 1.2 =
* Fixed a bug that caused some files (css, javascript) and links to not load

= 1.1 =
* Added an option that allows donors to specify the donation amount 
* Added a tag [donation-can goal_id] that allows you to embed donation forms inside your posts and pages
* Made it possible to create donation goals without setting a target amount 

= 1.0 =
* First released version.
