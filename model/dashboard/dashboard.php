<?php
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

// Dashboard widget
function donation_can_dashboard_widget() {
    if (current_user_can('dc_dashboard')) {
        wp_add_dashboard_widget("donation_can_dashboard", __("Fundraising Status", "donation_can"), 'render_donation_can_dashboard_widget');
    }
}

function render_donation_can_dashboard_widget() {
    $donations = donation_can_get_donations(0, 5);
    $goals = donation_can_get_goals(false);
    
    foreach ($goals as $id => $goal) {
        $goal["collected"] = donation_can_get_total_raised_for_cause($id);
        $goals[$id] = $goal;
    }

    $options = donation_can_get_general_settings();
    $paypal_account =  $options["paypal_email"];

    $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/donation-can/donation_can.php');
    $version = $plugin_data["Version"];

    require_donation_can_view('dashboard_widget', array("donations" => $donations, "goals" => $goals,
        "paypal_account" => $paypal_account, "version" => $version));
}

add_action('wp_dashboard_setup', 'donation_can_dashboard_widget'); 
?>