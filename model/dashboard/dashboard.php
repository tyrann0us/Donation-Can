<?php
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

// Dashboard widget
function donation_can_dashboard_widget() {
	wp_add_dashboard_widget("donation_can_dashboard", "Fundraising Status", 'render_donation_can_dashboard_widget');
}

function render_donation_can_dashboard_widget() {
	$donations = donation_can_get_donations(0, 5);
	$goals = donation_can_get_goals(true);
	
	$options = get_option("donation_can_general");
	$paypal_account =  $options["paypal_email"];
	
	require(__FILE__ . "/../../../view/dashboard_widget.php");
}

add_action('wp_dashboard_setup', 'donation_can_dashboard_widget'); 
?>