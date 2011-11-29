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

/**
 * The parent class for all payment method implementations supported
 * by Donation Can.
 *
 * @author Jarkko Laine, <jarkko@jarkkolaine.com>
 */
abstract class DonationCanPaymentMethod {

    abstract function getSortOrder();

    abstract function getId();

    abstract function getName();

    abstract function getDescription();

    //
    // DONATION HANDLING
    //

    /**
     * Common code for saving donations from payment methods.
     * 
     * @param <type> $donation 
     */
    function saveDonation($donation) {
        /*        $types = array('%s', '%s', '%s', "%f", "%s", "%s", "%s", "%s", "%f", "%s");

        foreach ($data as $k => $v) {
            w2log("$k: $v");
        }*/


        $table_name = donation_can_get_table_name($wpdb);
        w2log("Saving donation to $table_name");

        // Check if the transaction has already been saved
        // and update if payment_status has changed
        $saved_transaction = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE item_number = %s", $data["item_number"]));

        if ($saved_transaction == null) {
            w2log("Error, no transaction found with item_number " . $data["item_number"]);
        } else {
            // TODO Depending on the original status, do different updates...
            $data["cause_code"] = $saved_transaction->cause_code;

            if ($data["payment_status"] == "Refunded") {
                // Refunds send back the change in the donation amount
                $change = intval($data["amount"]);
                $fee_change = intval($data["fee"]);

                $data["amount"] = $saved_transaction->amount + $change;
                $data["fee"] = $saved_transaction->fee + $fee_change;
            }

            $wpdb->update($table_name,
                    $data,
                    array("item_number" => $data["item_number"]),
                    $types,
                    "%s");
        }

        w2log("OK");

        // Try to notify via email
        $goals = get_option("donation_can_causes");
        $goal = $goals[$data["cause_code"]];

        $emails = split(",", $general_settings["notify_email"]);
        $goal_emails = split(",", $goal["notify_email"]);

        if (!empty($emails) || !empty($goal_emails)) {
            $all_emails = array_merge($emails, $goal_emails);

            //TODO tässä välissä voisi varmistella vielä, että kaikki on oikein formatoitu...
            $to = join(",", $all_emails);
            w2log("Sending email to: " . $to);

            $message = $general_settings["email_template"];
            if ($message == null || $message == "") {
                // Default version
                $message = donation_can_get_default_email_template();
            }

            if ($data["payment_status"] == "Completed") {
                $subject = '[Donation Can] New Donation to ' . $goal["name"];
                donation_can_send_email($to, $subject, $message, $general_settings, $goal, $data);
            } else if ($data["payment_status"] == "Pending" || $data["payment_status"] == "Created") {
                $subject = '[Donation Can] Pending Donation to ' . $goal["name"];
                donation_can_send_email($to, $subject, $message, $general_settings, $goal, $data);
            }
        }

        // Send a receipt to donor (only if completed)
        if ($data["payment_status"] == "Completed") {
            if ($general_settings["send_receipt"]) {
                donation_can_send_receipt($data, $goal);
            }
        }
    }

    /**
     * Starts a donation according to payment method. Before reaching
     * this point, Donation Can has already saved the donation's data
     * and generated a unique identifier for it.
     *
     * @param $cause
     * @param $item_number
     * @param $amount
     */
    abstract function startDonation($cause, $item_number, $amount);

    /**
     * Processes a callback received from the payment provider, checking
     * that the payment has been really completed succesfully and the donation
     * can be safely added.
     *
     * If your payment method doesn't use a callback, just return true.
     *
     * TODO specify options!
     */
    abstract function processCallback();


    //
    // SETTINGS
    //

    /**
     * Returns an array with options to show for this payment method if the
     * user chooses it.
     */
    abstract function getSettingsForm($settings);

}
?>