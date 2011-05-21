<?php
/*
Copyright (c) 2009-2011, Jarkko Laine.

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

require("donation_can_general_settings.php");
require("donation_can_goals.php");
require("donation_can_add_goal.php");
require("donation_can_donations.php");
require("donation_can_add_donation.php");
require("donation_can_widget_styles.php");
require("donation_can_edit_widget_style.php");

function donation_can_setup_admin_menus() {
    add_menu_page(__('Donation Can', "donation_can"), __('Donation Can', "donation_can"), "manage_options", "donation_can_general_settings.php", 'donation_can_top_menu');
    add_submenu_page('donation_can_general_settings.php', __('Donation Can Settings - General', "donation_can"), __('General Settings', "donation_can"), "manage_options", "donation_can_general_settings.php", 'donation_can_settings_page');
    add_submenu_page('donation_can_general_settings.php', __('Donation Can Settings - Causes', "donation_can"), __('Causes', "donation_can"), "manage_options", "donation_can_goals.php", 'donation_can_goals_menu');
    add_submenu_page('donation_can_general_settings.php', __('Donation Can Settings - Add New Cause', "donation_can"), __('Add New Cause', "donation_can"), "manage_options", "donation_can_add_goal.php", 'donation_can_add_goal_menu');
    add_submenu_page('donation_can_general_settings.php', __('Donation Can Settings - Browse Donations', "donation_can"), __('Donations', "donation_can"), "manage_options", "donation_can_donations.php", 'donation_can_donations_menu');
    add_submenu_page('donation_can_general_settings.php', __('Donation Can Settings - Add New Donation', "donation_can"), __('Add New Donation', "donation_can"), "manage_options", "donation_can_add_donation.php", 'donation_can_add_donation_menu');
    add_submenu_page('donation_can_general_settings.php', __('Donation Can Settings - Widget Styles', "donation_can"), __('Widget Styles', "donation_can"), "manage_options", "donation_can_widget_styles.php", 'donation_can_widget_styles_menu');
    add_submenu_page('donation_can_general_settings.php', __('Donation Can Settings - Add New Widget Style', "donation_can"), __('Add New Widget Style', "donation_can"), "manage_options", "donation_can_edit_widget_style.php", 'donation_can_edit_widget_style_menu');
}

function donation_can_top_menu() {
}

add_action('admin_menu', 'donation_can_setup_admin_menus');
?>