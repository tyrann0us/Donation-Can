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

function donation_can_add_donation_menu() {
    global $wpdb;
    
    $causes = get_option("donation_can_causes");
    if ($causes == null) {
        $causes = array();
    }

    // Add a new donation
    if ($_POST["add_donation"] == "Y") {
        $cause_code = $_POST["cause_code"];
        $amount = $_POST['amount'];
        $payer_email = $_POST['payer_email'];
        $payer_name = $_POST['payer_name'];
        $note = $_POST["note"];

        // Validate parameters
        $cause_code_valid = false;
        foreach ($causes as $cause) {
            if ($cause["id"] == $cause_code) {
                $cause_code_valid = true;
                break;
            }
        }

        $amount_valid = false;
        if ($amount != null) {
            $amount_valid = floatval($amount) > 0;
        }

        if (!$cause_code_valid) {
            donation_can_render_error(__("Select a goal to add the donation to.", "donation_can"));
        }
        
        if (!$amount_valid) {
            donation_can_render_error(__("Enter a donation sum greater than 0.", "donation_can"));            
        }
        
        if ($cause_code_valid && $amount_valid) {
            // Save data
            $data = array(
                "cause_code" => $cause_code,
                "payment_status" => "Completed",
                "amount" => $amount,
                "transaction_id" => "offline",
                "payer_email" => $payer_email,
                "payer_name" => $payer_name,
                "note" => $note,
                "fee" => 0,
                "time" => current_time('mysql'),
                "offline" => 1
            );

            $table_name = donation_can_get_table_name($wpdb);
            $wpdb->insert($table_name, $data, $types);

            render_user_notification(__("Added offline donation", "donation_can")
                . ". <a href=\"admin.php?page=donations.php\">" . __("Browse donations", "donation_can") . "</a>");
        }
    }
	
    require(WP_PLUGIN_DIR . "/donation-can/view/add_donation_page.php");
}
?>