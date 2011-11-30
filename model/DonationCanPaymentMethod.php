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
     * @param DonationCanDonation $donation
     */
    function saveDonation($donation) {
        $table_name = donation_can_get_table_name($wpdb);
        w2log("Updating donation to $table_name");

        // Check if the transaction has already been saved
        // and update if payment_status has changed
        $saved_transaction = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE item_number = %s", $donation->getItemNumber()));

        if ($saved_transaction == null) {
            w2log("Error, no transaction found with item_number " . $donation->getItemNumber());
            return;
        }

        // TODO Depending on the original status, do different updates...
        $donation->setCauseCode($saved_transaction->cause_code);

        if ($donation->isRefund()) {
            // Refunds send back the change in the donation amount
            $change = floatval($donation->getAmount());
            $fee_change = floatval($donation->getFee());

            $donation->setAmount($saved_transaction->amount + $change);
            $donation->setFee($saved_transaction->fee + $fee_change);
        }

        // Update the database row
        $data = $donation->getDataAsArray();
        $wpdb->update($table_name,
                $data["data"],
                array("item_number" => $donation->getItemNumber()),
                $data["types"],
                "%s");

        w2log("OK");

        // Try to notify via email
        $goals = get_option("donation_can_causes");
        $goal = $goals[$donation->getCauseCode()];

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
                donation_can_send_email($to, $subject, $message, $general_settings, $goal, $data["data"]);
            } else if ($data["payment_status"] == "Pending" || $data["payment_status"] == "Created") {
                $subject = '[Donation Can] Pending Donation to ' . $goal["name"];
                donation_can_send_email($to, $subject, $message, $general_settings, $goal, $data["data"]);
            }
        }

        // Send a receipt to donor (only if completed)
        if ($data["payment_status"] == "Completed") {
            if ($general_settings["send_receipt"]) {
                donation_can_send_receipt($data["data"], $goal);
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