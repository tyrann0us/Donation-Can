=== Donation Can ===
Contributors: jarkkolaine
Donate link: http://treehouseapps.com/donation-can
Tags: donations, paypal, fundraising, money
Requires at least: 2.8
Tested up to: 3.1.1
Stable tag: 1_5_0

Collect PayPal donations towards multiple goals on your blog and show the progress to your visitors using sidebar widgets.

== Description ==

Donation Can is a WordPress plugin that lets you set fundraising goals (in multiple currencies) and collect donations to meet them. Donations are
collected through PayPal and can be tracked inside your WordPress admin area separately for each goal.

Key features:

*   Define goals with name, description and amount of money required to meet the goal. For example "Help me buy a MacBook Pro" or "Save the rainforests."
*   Use WordPress widgets and shortcodes to define how and where the donation forms and progress bars are shown in your blog layout.
*   Be notified by email when someone makes a donation to one of your goals.
*   Follow the progress and browse donations inside the WordPress admin area.
*   Get a quick glance at the overall status of your donations using the Donation Can dashboard widget.
*   Easy to customize.

== Installation ==

Donation Can installation follows the standard way of most WordPress plugins:

1. Upload files to your `/wp-content/plugins/` directory (preserve sub-directory structure)
1. Activate the plugin through the 'Plugins' menu in WordPress

After the plugin has been installed, set up your PayPal information on the plugin settings page and define your first goal.

IMPORTANT: Donation Can doesn't work with the default permalink settings (The format with URLs such as http://www.example.com/?p=10), 
so you need to select one of the other options to make the plugin work. As a bonus, changing your permalink structure
is also good for Search Engine Optimization.

== Using Donation Can ==

= 1. Creating a donation goal =

Collecting donations through Donation Can starts from creating a donation goal. A goal contains a cause (= a title and
a description of what you are collecting the money for), and a money goal (or no goal for an openended fundraising campaign)
stating how much money you need.

To create a donation goal, click on the "Add New Goal" link in the Donation Can menu group.

= 2. Using widgets to show the donation form =

Donation Can comes with three widgets that you can use in your blog theme to present Donation Can features
to your visitors:

1. *Donation Form* allows you to show a donation form (optionally including a progress bar, description, and a list of latest donations).
1. *Latest Donations* is for listing latest donations to one or all of your goals on your blog.
1. *Fundraising Progress* shows a progress bar without a donation form.

= 3. Embedding a donation form to a page or a post =

Donation forms can be embedded to any post or page on your blog using the [donation-can] shortcode. You can
type the shortcode by hand, but it's probably easier to just click on the "Donation Form" button next to the
media upload buttons on your post editing page. This popup will create a correctly formatted donation for you
without any typing required.

For those interested in the details of the shortcode:

The shortcode requires one parameter ("donation_goal"), which is one of the donation goals you have created in the plugin settings. In addition to that mandatory parameter, you can use the following parameters:

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

Coming soon...

== Upgrade Notice ==

= 1.5.0 =
This version contains many of improvements that make Donation Can easier to use.

== Changelog ==

= 1.5.0 =
* New design for the "Add Donation Goal" page makes it easier to create new donation goals
* Goal ID is generated automatically based on the goal name (can be edited before saving)
* A currency can now be selected separately for each goal
* Donation history is stored also for donations that were never completed
* Sorting options for goals (currently updated from the general settings page)
* Option to subtract PayPal fees from donations shown
* Deleting donations (marks donations as deleted but leaves the data in the database as backup)
* Donation Can now allows donating even after the donation goal has been reached.
* New popup for easily embedding donation forms to blog posts and pages
* Bug fix: Plugin cannot be used without updating permalink settings anymore
* Bug fix: Permission problems fixed
* Bug fix: Input fields with money values are checked for correct formatting and fixed
  automatically to prevent problems due to malformatted numbers.
* Bug fix: Listing donations (show_donations=true) now works in shortcodes as well as in widgets
* UI fixes to make browsing the donations and goals easier
* Renamed the donation can menu pages to make sure they don't conflict with other plugins
* Bug fix: link from dashboard widget to general settings works now (used to say "You don't have sufficient permissions")
* Decimal numbers are now allowed in donation options. Donation goal only in full digits.
* Bug fix: If same transaction is sent by PayPal multiple times, the same row is updated instead of adding the donation again
* Removed shipping options from General Settings page as unnecessary element
* Small fixes to plugin installation process

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
