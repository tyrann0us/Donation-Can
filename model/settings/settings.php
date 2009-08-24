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

require("general_settings.php");
require("goals.php");
require("add_goal.php");
require("donations.php");

function donation_can_setup_admin_menus() {
	add_menu_page('Donation Can', 'Donation Can', 9, __FILE__, 'donation_can_top_menu');
	add_submenu_page(__FILE__, 'Donation Can Settings - General', 'General Settings', 9, __FILE__, 'donation_can_settings_page');
	add_submenu_page(__FILE__, 'Donation Can Settings - Goals', 'Goals', 9, "goals.php", 'donation_can_goals_menu');
	add_submenu_page(__FILE__, 'Donation Can Settings - Add New Goal', 'Add New Goal', 9, "add_goal.php", 'donation_can_add_goal_menu');
	add_submenu_page(__FILE__, 'Donation Can Settings - Browse Donations', 'Donations', 9, "donations.php", 'donation_can_donations_menu');
}

function donation_can_top_menu() {
}

add_action('admin_menu', 'donation_can_setup_admin_menus');
?>