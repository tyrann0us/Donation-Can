=== Donation Can ===
Contributors: jarkkolaine
Donate link: http://treehouseapps.com/donation-can
Tags: donations, paypal, fundraising, money
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: 1_5_7

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

= 1. Creating a donation cause =

Collecting donations through Donation Can starts from creating a donation goal. A goal contains a cause (= a title and
a description of what you are collecting the money for), and a money goal (or no goal for an openended fundraising campaign)
stating how much money you need.

To create a donation goal, click on the "Add New Cause" link in the Donation Can menu group.

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

The shortcode requires one parameter (goal_id="donation_goal"), where "donation_goal" is one of the donation goals you have created in the plugin settings. In addition to that mandatory parameter, you can use the following parameters:

* show_progress: Set to "true" or "false" (default = "true")
* show_description: Set to "true" or "false" (default = "true")
* show_donations: Set to "true" or "false" (default = "false")
* show_title: Set to "true" or "false" (default = "true") 

These parameters are used as key-value pairs, e.g. "show_progress=false"

Example:

[donation-can goal_id="coffee" show_progress=false]

== Frequently Asked Questions ==

If your question wasn't answered here, visit the [official plugin page](http://jarkkolaine.com/plugins/donation-can/) and leave a comment and I will answer your question. If the question is common, it will be added to this page in the next update.

== Screenshots ==

1. The default look of a Donation Can fundraising widget.
2. Adding a new donation cause
3. Browsing donations
4. Manually adding a new donation

== Upgrade Notice ==

= 1.5.7.1 =
Quick bug fix to 1.5.7.

= 1.5.7 =
Donation Can is now ready for localization. Bug and plugin compatibility fixes.

= 1.5.6 =
Bug fixes.

= 1.5.5 =
Improvements to settings and e-mail communication. Bug fixes.

= 1.5.4 =
Improvements to the widget style editor and several other small fixes.

= 1.5.3 =
Adds a whole new editor for defining widget styles and two default styles to choose from.

= 1.5.2.1 =
This is yet another quick bug fix release that fixes problems with adding offline donations.

= 1.5.2 =
This is a bug fix release. If you see all your donations and are collecting them just fine, you can safely skip this update.

= 1.5.0 =
This version contains many of improvements that make Donation Can easier to use.

== Changelog ==

= 1.5.7.1 =
* Fixed a bug in number formatting.

= 1.5.7 =
* Added a settings page for exporting Donation Can data as CSV or XML. Some data is only available in XML format.
* Added uninstall functionality that deletes the database tables and settings added by Donation Can.
* Full localization support.
* Finnish translation. More languages coming in the upcoming versions. Get in touch if you want to provide a translation for your language.
* Plugin security improvements on settings pages and AJAX calls.
* Split the name of the donor into first name and last name to allow more options for e-mail message formatting.
* Fixed compatibility problems with the "After the deadline" plugin.
* All money strings in Donation Can are now consistently formatted to always show two digits.
* Fixed sorting of latest donations to be by time instead of id.
* Offline donations added through the "Add new donation" page can now be marked anonymous.

= 1.5.6 =
* Donation widget settings now show only the settings that are applicable to the selected style.
* Donation widget now allows selecting a "Summary (all causes)" option which shows all causes in the widget with a drop down list for choosing the cause to donate to. Only available when using the default widget style right now.
* Localization: Special characters now work in UTF8, also on the PayPal page. Make sure your WordPress installation is configured to use UTF-8 to benefit from this update.
* Improved paging for donations. Now works well also when the number of donations rise very high.
* Resetting donations from a cause is now done using the time of donation rather than id to prevent problems with eChecks clearing only after reset.
* Bug fix: Fixed the link from plugin list to general settings.
* Bug fix: Fixed links within admin pages to work properly also when WordPress admin is moved to a custom location.

= 1.5.5 =
* Reorganized the general setting page.
* Added new settings fields for customizing the PayPal checkout page.
* Added support for uploading an image to use on the PayPal checkout page.
* Added separate field for configuring the PayPal sandbox email address.
* Made logging a configurable setting. By default logging is turned off, and if you turn it on, you must make sure the log file is writeable.
* Added a settings field for customizing the email notification template.
* Added support for sending receipts to donors. The receipt template is customizable.
* Added support for sending e-mail messages as HTML.
* Removed CSS styling options from the general settings page. Styling should now be done using the "Widget styles" menu.
* Added a date field on the "Add donation" page for defining the date when the donation was received.
* Added a widget style customization option for letting the user define his custom donation sum using a text field (only available when using radio buttons)
* Restricted Donation Can menus to administrator level. If you want to grand other roles access to Donation Can menus, you can give one or more of the following capabilities to the role of your choice: dc_general_settings, dc_causes, dc_donations, dc_styles, dc_dashboard.
* Added the latest donations element to the default widget styles ("Default" and "Default Vertical").
* Added an option to define the number of latest donations to list in widget.
* Small layout fixes to make everything look good with the latest WordPress version.
* Bug fix: Refunds from PayPal now work correctly.
* Bug fix: Setting the number of recent donations to show in the latest donations widget is now working correctly.
* Bug fix: Removing CSS definitions from widget styles fixed.
* Bug fix: Saving widget styles in WordPress 3.2.1 fixed.
* Bug fix: Improved the handling of special character when listing donors' names.

= 1.5.4 =
* Added support for replacing the default donation button with your own graphic.
* Added option for showing donation options as radio buttons.
* Added support for resetting the counter for a specific goal. All donations for that goal are kept in history, but only the ones added after the resetting are shown to visitors.
* Added support for cloning widget style templates to make it easier to create new customizations
* Donations can now be made anonymously.
* GBP and JPY are now converted to the proper graphical symbols instead of using the three letter acronyms
* Bug fix: Defined a text color (dark gray) for the default widget styles 
* Bug fix: Better error reporting when users create donation widgets without specifying a cause.

= 1.5.3 =
* Editor for defining custom widget styles for donation forms (The old CSS from general settings is still included, but that functionality is deprecated and will be removed at some point)
* Two default widget styles: "Default" and "Default Vertical"
* New currency: Turkish Lira (according to PayPal, it only works for Turkish customers)
* Bug fix: Selecting the thank you / cancelled page on general settings tab works now

= 1.5.2.1 =
* Bug fix: There was still an issue left from the previous fixes that caused problems when adding offline donations.

= 1.5.2 =
* Bug fix: Fixed a bug database upgrade bug introduced in previous version
* Bug fix: Fixed a bug related to passing the selected currency to PayPal

= 1.5.1 =
* Bug fix: Database upgrades don't rely on the plugin activation hook anymore as WordPress doesn't call that method in automatic updates.
* Bug fix: Texts in donation lists work even without a proper *.mo file now.
* Bug fix: Better permalink functionality makes the plugin work nicely along with other plugins that use custom permalink structures (tested with the Webcomic plugin).
* Bug fix: Fixed the action url in donation form for index permalinks.

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
