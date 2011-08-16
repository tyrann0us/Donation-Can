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
    if ($general_settings["email_template"] == null || $general_settings["email_template"] == "") {
        $general_settings["email_template"] = donation_can_get_default_email_template();
    }

    $pages = get_pages();

    $style_options = array("default" => "Default", "custom" => "Customize");

    // Save general settings
    if ($_POST["edit_settings"] == "Y") {
        // Verify nonce
        $nonce = $_POST["nonce"];
        if (!wp_verify_nonce($nonce, "donation-can-general-settings-nonce")) die("Failed to verify NONCE.");

        $paypal_email = esc_attr($_POST["paypal_email"]);
        $paypal_sandbox_email = esc_attr($_POST["paypal_sandbox_email"]);

        $require_shipping = esc_attr($_POST["require_shipping"]);
        $ask_for_note = esc_attr($_POST["ask_for_note"]);
        $note_field_label = esc_attr($_POST["note_field_label"]);
        
        $return_page = esc_attr($_POST["return_page"]);
        $continue_button_text = esc_attr($_POST["continue_button_text"]);
        $cancel_return_page = esc_attr($_POST["cancel_return_page"]);

        $logo_on_paypal_page = esc_attr($_POST["logo_on_paypal_page"]);
        $header_on_paypal_page = esc_attr($_POST["header_on_paypal_page"]);
        $bg_on_paypal_page = esc_attr($_POST["bg_on_paypal_page"]);
        $header_bg_on_paypal_page = esc_attr($_POST["header_bg_on_paypal_page"]);
        $header_border_on_paypal_page = esc_attr($_POST["header_border_on_paypal_page"]);

        $notify_email = esc_attr($_POST["notify_email"]);
        $style = esc_attr($_POST["style"]);
        $custom = esc_attr($_POST["custom"]);
        $currency = esc_attr($_POST["currency"]);

        $debug_mode = esc_attr($_POST["debug_mode"]) == "1";
        $enable_logging = esc_attr($_POST["enable_logging"]) == "1";

        $sort_causes_field = esc_attr($_POST["sort_causes_field"]);
        $sort_causes_order = esc_attr($_POST["sort_causes_order"]);
        $sort_donations_field = esc_attr($_POST["sort_donations_field"]);
        $sort_donations_order = esc_attr($_POST["sort_donations_order"]);

        $show_back_link = esc_attr($_POST["link_back"]) == "1";

        $subtract_fees = esc_attr($_POST["subtract_fees"]) == "1";

        $donation_sum_num = esc_attr($_POST["donation_sum_num"]);
        $donation_sums = array();
        for ($i = 0; $i < $donation_sum_num; $i++) {
            $sum_value = esc_attr($_POST["donation_sum_" . $i]);
            if ($sum_value != null && $sum_value != "") {
                $donation_sums[] = $sum_value;
            }
        }

        $email_template = $_POST["email_template"];

        $general_settings["paypal_email"] = $paypal_email;
        $general_settings["paypal_sandbox_email"] = $paypal_sandbox_email;

        $general_settings["require_shipping"] = $require_shipping;
        $general_settings["ask_for_note"] = $ask_for_note;
        $general_settings["note_field_label"] = $note_field_label;
        $general_settings["continue_button_text"] = $continue_button_text;

        $general_settings["return_page"] = $return_page;
        $general_settings["cancel_return_page"] = $cancel_return_page;

        $general_settings["logo_on_paypal_page"] = $logo_on_paypal_page;
        $general_settings["header_on_paypal_page"] = $header_on_paypal_page;
        $general_settings["bg_on_paypal_page"] = $bg_on_paypal_page;
        $general_settings["header_bg_on_paypal_page"] = $header_bg_on_paypal_page;
        $general_settings["header_border_on_paypal_page"] = $header_border_on_paypal_page;

        $general_settings["notify_email"] = $notify_email;

        $general_settings["currency"] = $currency;

        $general_settings["donation_sums"] = array();
        foreach ($donation_sums as $sum) {
            $general_settings["donation_sums"][] = $sum;
        }

        $general_settings["style"] = $style;
        $general_settings["custom"] = $custom;

        $general_settings["debug_mode"] = $debug_mode;
        $general_settings["enable_logging"] = $enable_logging;

        $general_settings["sort_causes_field"] = $sort_causes_field;
        $general_settings["sort_donations_field"] = $sort_donations_field;
        $general_settings["sort_causes_order"] = $sort_causes_order;
        $general_settings["sort_donations_order"] = $sort_donations_order;

        $general_settings["link_back"] = $show_back_link;

        $general_settings["subtract_paypal_fees"] = $subtract_fees;

        $general_settings["email_template"] = $email_template;

        update_option("donation_can_general", $general_settings);
        render_user_notification(__("Donation Can settings updated", "donation_can"));
    }

    require_donation_can_view('settings_page', array("general_settings" => $general_settings,
        "pages" => $pages, "style_options" => $style_options));
    
}

?>