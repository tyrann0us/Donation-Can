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

function render_user_notification($message) {
  echo "<div class='updated fade'><p>".$message."</p></div>";
}

function donation_can_render_error($message) {
  echo "<div class='error fade'><p>".$message."</p></div>";
}

/** 
 * Renders the settings page with the following features
 *
 * - set up PayPal account information
 * - add a new cause (or subgoal)
 * - modify a cause
 * - remove a cause
 */
function donation_can_settings_page() {	
    $general_settings = get_option("donation_can_general");
    $pages = get_pages();

    $style_options = array("default" => "Default", "custom" => "Customize");

    // Save general settings
    if ($_POST["edit_settings"] == "Y") {
        $paypal_email = attribute_escape($_POST["paypal_email"]);
        $require_shipping = attribute_escape($_POST["require_shipping"]);
        $ask_for_note = attribute_escape($_POST["ask_for_note"]);
        $return_page = attribute_escape($_POST["return_page"]);
        $continue_button_text = attribute_escape($_POST["continue_button_text"]);
        $cancel_return_page = attribute_escape($_POST["cancel_return_page"]);
        $logo_on_paypal_page = attribute_escape($_POST["logo_on_paypal_page"]);
        $notify_email = attribute_escape($_POST["notify_email"]);
        $style = attribute_escape($_POST["style"]);
        $custom = attribute_escape($_POST["custom"]);
        $currency = attribute_escape($_POST["currency"]);
        $debug_mode = attribute_escape($_POST["debug_mode"]) == "1";

        $donation_sum_num = attribute_escape($_POST["donation_sum_num"]);
        $donation_sums = array();
        for ($i = 0; $i < $donation_sum_num; $i++) {
            $sum_value = attribute_escape($_POST["donation_sum_" . $i]);
            if ($sum_value != null && $sum_value != "") {
                $donation_sums[] = $sum_value;
            }
        }

        $general_settings["paypal_email"] = $paypal_email;
        $general_settings["require_shipping"] = $require_shipping;
        $general_settings["ask_for_note"] = $ask_for_note;
        $general_settings["continue_button_text"] = $continue_button_text;

        $general_settings["return_page"] = $return_page;
        $general_settings["cancel_return_page"] = $cancel_return_page;

        $general_settings["logo_on_paypal_page"] = $logo_on_paypal_page;

        $general_settings["notify_email"] = $notify_email;

        $general_settings["currency"] = $currency;

        $general_settings["donation_sums"] = array();
        foreach ($donation_sums as $sum) {
            $general_settings["donation_sums"][] = $sum;
        }

        $general_settings["style"] = $style;
        $general_settings["custom"] = $custom;

        $general_settings["debug_mode"] = $debug_mode;


        update_option("donation_can_general", $general_settings);
        render_user_notification(__("Donation Can settings updated", "donation_can"));
    }


    require(WP_PLUGIN_DIR . "/donation-can/view/settings_page.php");
}

?>