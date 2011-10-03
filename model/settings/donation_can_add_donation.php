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

function donation_can_add_donation_menu() {
    global $wpdb;
    
    $causes = get_option("donation_can_causes");
    if ($causes == null) {
        $causes = array();
    }

    $general_settings = donation_can_get_general_settings();
    $send_receipt = $general_settings["send_receipt"];

    $created_at = current_time('mysql');

    // Add a new donation
    if ($_POST["add_donation"] == "Y") {
        $cause_code = $_POST["cause_code"];
        $amount = $_POST['amount'];
        $payer_email = $_POST['payer_email'];
        $payer_name = $_POST['payer_name'];
        $note = $_POST["note"];

        $created_at = $_POST["created_at"];

        $send_receipt = esc_attr($_POST["send_receipt"]) == "1";

        // Validate parameters
        $cause_code_valid = false;
        $cause = donation_can_get_goal($cause_code);
        if ($cause != null) {
            $cause_code_valid = true;
        }

        $amount_valid = false;
        if ($amount != null) {
            $amount_valid = floatval($amount) > 0;
        }

        $date_valid = donation_can_is_valid_date($_POST["created_at"]);

        if (!$cause_code_valid) {
            donation_can_render_error(__("Select a goal to add the donation to.", "donation_can"));
        }
        
        if (!$amount_valid) {
            donation_can_render_error(__("Enter a donation sum greater than 0.", "donation_can"));            
        }

        if (!$date_valid) {
            donation_can_render_error(__("Time of donation is not a valid date.", "donation_can"));
        }
        
        if ($cause_code_valid && $amount_valid && $date_valid) {
            $item_number = donation_can_create_item_number($cause_code);

            $donation_data = donation_can_insert_donation($item_number, $cause_code, 'Completed', $amount, false,
                $created_at, 0, $payer_email, $payer_name, "offline", 1);

            if ($send_receipt) {
                donation_can_send_receipt($donation_data, $cause);
            }

            render_user_notification(__("Added offline donation", "donation_can")
                . ". <a href=\"" . get_bloginfo("url") . "/wp-admin/admin.php?page=donation_can_donations.php\">" . __("Browse donations", "donation_can") . "</a>");
        }
    }

    require_donation_can_view('add_donation_page', array("causes" => $causes, "send_receipt" => $send_receipt, "created_at" => $created_at));
    
}
?>