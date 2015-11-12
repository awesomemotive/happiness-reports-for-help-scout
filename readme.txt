=== Happiness Reports for Help Scout ===
Contributors: sumobi
Tags: Help Scout, Help, Scout, HelpScout, Support, Happiness, Ratings, Reports, Andrew Munro, sumobi
Requires at least: 3.9
Tested up to: 4.3.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add Help Scout happiness reports to your website

== Description ==

> This plugin requires Help Scout API Access and therefore will only work with a paid [Help Scout](https://www.helpscout.net/ "Help Scout") plan.

Do you use [Help Scout](https://www.helpscout.net/ "Help Scout")?
Are you proud of your customer support? Showing highly positive ratings is a
great way to bring in more customers, be more transparent and hold yourself
accountable.

Happiness Reports for Help Scout allows you to show your customer happiness reports
on your website using WordPress shortcodes. If you're a developer, you can also
use PHP functions for greater control.

Shortcode Usage:

= [happiness_report] =
Show all ratings (great, okay, not good) as an unordered list

= [happiness_report rating="great"] =
Show only the "great" rating

= [happiness_report rating="okay"] =
Show only the "okay" rating

= [happiness_report rating="not good"] =
Show only the "not good" ratings

= [happiness_report graph="yes"] =
Show all ratings (great, okay, not good) as individual graphs

= [happiness_report rating="great" graph="yes"] =
Show only the "great" rating as a graph

= [happiness_report rating="okay" graph="yes"] =
Show only the "okay" rating as a graph

= [happiness_report rating="not good" graph="yes"] =
Show only the "not good" rating as a graph

Setup:

1. Activate the plugin
2. Go to Settings &rarr; Happiness reports
3. Enter your [Help Scout API Key](http://developer.helpscout.net/#generating-an-api-key "Help Scout API Key") in the provided fields
4. Save changes
5. Select the mailboxes you'd like to show happiness reports from
6. Select a date range
7. Save changes
8. Use the provided shortcodes to show your happiness reports on your website!

== Installation ==

1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP Admin plugin page)
1. Activate this plugin

OR you can just install it with WordPress by going to Plugins >> Add New >> and type this plugin's name

== Upgrade Notice ==

== Changelog ==

= 1.0.0 =
* Initial release
