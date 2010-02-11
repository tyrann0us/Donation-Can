<?php
/*
Plugin Name: Donation Can
Version: 1.4.2
Plugin URI: http://jarkkolaine.com/plugins/donation-can
Description: Donation Can lets you raise funds for multiple causes using your WordPress blog and PayPal account while tracking the progress of each cause separately. <a href="tools.php?page=donation-can/donation_can.php">Click here</a> to configure settings.
Author: Jarkko Laine
Author URI: http://jarkkolaine.com
*/

/*
Copyright (c) 2009-2010, Jarkko Laine.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

require("model/data.php");

require("model/widgets/widgets.php");
require("model/dashboard/dashboard.php");
require("model/settings/settings.php");

// Helper methods for theme developers
require("theme_methods.php");

// Adds the style sheet definition to head
function donation_can_head_filter() {
    echo '<link rel="stylesheet" href="' . get_bloginfo('url') . '/wp-content/plugins/donation-can/view/style.css"/>';

    // Add the custom style created in settings
    $options = get_option("donation_can_general");
    if ($options != null && isset($options["custom"])) {
        echo "<style type=\"text/css\" media=\"screen\">";
        echo $options["custom"];
        echo "</style>";
    }
}

function donation_can_split_tag_to_array($tag) {
    // Remove white space and possible <br />'s added by WordPress
    $tag =  preg_replace('/[ \t\n]+/', ' ', $tag);
    $tag = str_replace("<br />", "", $tag);

    // Extract data from tag
    $tag_data_array = explode(" ", $tag);

    return $tag_data_array;
}

function donation_can_parse_donation_can_tag($tag_data_array) {
	$code = $tag_data_array[1];

	// Initialize array with default values and replace them with passed parameters if there are any
	$parameters = array("show_progress" => "true", "show_description" => "true", 
		"show_donations" => "false", "show_title" => "true", "title" => "");
	
	if (count($tag_data_array) > 2) {
		for ($i = 2; $i < count($tag_data_array); $i++) {
			$data = explode("=", $tag_data_array[$i]);
			$parameters[$data[0]] = $data[1];
		}
	}       

	$widget_content = "<div class=\"donation-can_content-widget\">";
	$widget_content .= get_donation_can_donation_form($code, 
		strtolower($parameters["show_progress"]) == "true", 
		strtolower($parameters["show_description"]) == "true",
		strtolower($parameters["show_donations"]) == "true", 
		strtolower($parameters["show_title"]) == "true", 
		$parameters["title"]);
	$widget_content .= "</div>";

	return $widget_content;
}

/**
 * The content filter replaces all donation can quick tags with a donation box.
 */
function donation_can_content_filter($content = "") {
	$new_content = "";
	$offset = 0;
	
	while ($offset < strlen($content)) {
		$pos = strpos($content, "[", $offset);

		if ($pos === false) {
			// No more tags in content. Done.
			$new_content = $new_content . substr($content, $offset);
			break;
		} else {
			// Append the content since previous tag or beginning
			$new_content = $new_content . substr($content, $offset, ($pos - $offset));

			// Extract the tag
			$end_pos = strpos($content, "]", $pos);
			$tag = substr($content, $pos + 1, ($end_pos - $pos - 1));
			
			$tag_data_array = donation_can_split_tag_to_array($tag);
			
			// Handle the tag
			if ($tag_data_array[0] == "donation-can") {
				$new_content = $new_content . donation_can_parse_donation_can_tag($tag_data_array);
			} else {
				// Not a Donation Can tag -- ignore
				$new_content = $new_content . "[$tag]";
			}
			
			$offset = $end_pos + 1;
		}
	}
	
	return $new_content;
}

//
// Some WordPress magic to get the handle the callback (IPN) from PayPal
// (See http://www.james-vandyne.com/2009/08/process-paypal-ipn-requests-through-wordpress/
// for more info on how this works)
//

function donation_can_query_vars($vars) {
    $new_vars = array('donation_can_ipn');
    $vars = $new_vars + $vars;
    return $vars;
}

function donation_can_parse_request($wp) {
    // Process PayPal IPN requests
    if (array_key_exists('donation_can_ipn', $wp->query_vars)
            && $wp->query_vars['donation_can_ipn'] == 'paypal') {
        // Located in data.php
        donation_can_proccess_paypal_ipn($wp);
    }
}

function donation_can_rewrite_rules($wp_rewrite) {
    $new_rules = array('donation_can_ipn/paypal' => 'index.php?donation_can_ipn=paypal');
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

function donation_can_admin_notices() {
    $options = get_option("donation_can_general");
    if ($options != null && $options["debug_mode"]) {
        echo '<div class="donation-can-admin-notice">';

        $settings_url = get_bloginfo("url") . '/wp-admin/admin.php?page=donation-can/model/settings/settings.php';
        _e('Donation Can is in sandbox mode. Remember to <a href="' . $settings_url . '">switch back to normal mode</a> when done testing.');
        echo '</div>';
    }
}

register_activation_hook(__FILE__, 'donation_can_install');
add_filter("wp_head", "donation_can_head_filter");
add_filter("the_content", "donation_can_content_filter");
add_filter("admin_head", "donation_can_head_filter");
add_filter('query_vars', 'donation_can_query_vars');
add_action('parse_request', 'donation_can_parse_request');
add_action('generate_rewrite_rules', 'donation_can_rewrite_rules');
add_action('admin_notices', 'donation_can_admin_notices');
?>
