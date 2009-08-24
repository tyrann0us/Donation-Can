<?php
/*
Plugin Name: Donation Can
Version: 1.0
Plugin URI: http://jarkkolaine.com/plugins/donation-can
Description: Donation Can lets you raise funds for multiple causes using your WordPress blog and PayPal account while tracking the progress of each cause separately. <a href="tools.php?page=donation-can/donation_can.php">Click here</a> to configure settings.
Author: Jarkko Laine
Author URI: http://jarkkolaine.com
*/

/*
Copyright (c) 2009, Jarkko Laine.

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

register_activation_hook(__FILE__, 'donation_can_install');
add_filter("wp_head", "donation_can_head_filter");
add_filter("admin_head", "donation_can_head_filter");
?>