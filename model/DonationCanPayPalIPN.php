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
 * The payment method class for default PayPal payments.
 */
class DonationCanPayPalIPN extends DonationCanPaymentMethod {

    function getSortOrder() {
        return 0;
    }

    function getId() {
        return "paypal_ipn";
    }

    function getName() {
        return __("PayPal", "donation_can") . " (<a href=\"http://www.paypal.com\" target=\"_new\">www.paypal.com</a>)";
    }

    function getDescription() {
        return __("This is the default payment method in Donation Can. It is also the easiest to set up: just enter your PayPal email address and you are all set for receiving donations.", "donation_can");
    }

    function startDonation($cause, $item_number, $amount) {
        $settings = donation_can_get_general_settings();

        // Generate the URL to redirect the payment to
        $action_url = "https://www.paypal.com/cgi-bin/webscr";
        if ($settings["debug_mode"]) {
            $action_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        }

        global $wp_rewrite;
        $notify_url = "donation_can_ipn/paypal/";
        if ($wp_rewrite->using_index_permalinks()) {
            $notify_url = "index.php/" . $notify_url;
        }
        $notify_url = get_bloginfo("url") . "/" . $notify_url;

        // Pick the right paypal email
        $paypal_email = donation_can_get_paypal_email();

        // Generate parameters to post
        $paypal_args = array(
            "business" => $paypal_email,
            "item_name" => apply_filters('donation_can_item_name', $cause['name']),
            "item_number" => $item_number,
            "cmd" => "_donations",
            "notify_url" => $notify_url,
            "currency_code" => donation_can_get_currency_for_goal($cause, false),
            "no_shipping" => $general_settings["require_shipping"],
            "no_note" => ($general_settings["ask_for_note"] == "0") ? "1" : "0",
            "amount" => $amount,
            "charset" => "utf-8",

            // Customization
            "cpp_payflow_color" => $general_settings["bg_on_paypal_page"],
            "cpp_headerback_color" => $general_settings["header_bg_on_paypal_page"],
            "cpp_headerborder_color" => $general_settings["header_border_on_paypal_page"],
            "cn" => $general_settings["note_field_label"]
        );

        // Custom logo (add if set)
        if ($general_settings["logo_on_paypal_page"] != "") {
            $paypal_args["image_url"] = $general_settings["logo_on_paypal_page"];
        }

        if ($general_settings["header_on_paypal_page"] != "") {
            $paypal_args["cpp_header_image"] = $general_settings["header_on_paypal_page"];
        }

        // Return pages (add if set)
        $return_page = $general_settings["return_page"];
        if ($cause["return_page"] != "" && $cause["return_page"] != "-1") {
            $return_page = $cause["return_page"];
        }

        $continue_button_text = $general_settings["continue_button_text"];
        if ($cause["continue_button_text"] != "" && $cause["continue_button_text"] != "-1") {
            $continue_button_text = $cause["continue_button_text"];
        }

        if ($return_page != "" && $return_page != "-1") {
            $return_page_url = get_permalink($return_page);

            $paypal_args["cbt"] = $continue_button_text;
            $paypal_args["return"] = $return_page_url;
        }

        $cancel_return_page = $general_settings["cancel_return_page"];
        if ($cause["cancel_return_page"] != "" && $cause["cancel_return_page"] != "-1") {
            $cancel_return_page = $cause["cancel_return_page"];
        }

        if ($cancel_return_page != "" && $cancel_return_page != "-1") {
            $cancel_return_page_url = get_permalink($cancel_return_page);

            $paypal_args["cancel_return"] = $cancel_return_page_url;
        }

        w2log("Donation started to " . $cause["id"] . " - Item Number: " . $item_number);

        // Output the PayPal form and submit
        // TODO: move to a view?
        echo "<html><body onload=\"document.getElementById('paypal_form').submit();\">";
        echo "<form id=\"paypal_form\" action=\"" . $action_url . "\" method=\"POST\">";

        foreach ($paypal_args as $key => $value) {
            echo "<input type=\"hidden\" name=\"" . $key . "\" value=\"" . $value . "\"/>";
        }

        echo "</form>";
        echo "</body></html>";

        // Skip rendering the rest of the blog to save some time
        die();
    }

    function processCallback() {
        $general_settings = donation_can_get_general_settings();

        // TODO: write received params to log

        // Send a request back to PayPal to confirm the notification
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

        // Use Sandbox version if debug mode is on
        $url = "ssl://www.paypal.com";
        if ($general_settings["debug_mode"]) {
            $url = "ssl://www.sandbox.paypal.com";
        }

        $fp = fsockopen ($url, 443, $errno, $errstr, 30);
        if (!$fp) {
            w2log("Http error, can't connect to " + $url);
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                if (strcmp ($res, "VERIFIED") == 0) {
                    w2log("Transaction verified --> ");

                    $status = $this->convertStatusCodeToStatus($_POST["payment_status"]);

                    w2log("Status: $status");

                    $donation = new DonationCanDonation($_POST["item_number"], $_POST["txn_id"], "",
                            $status, $_POST["mc_gross"], $_POST["mc_fee"], $_POST["payer_email"],
                            stripslashes($_POST["first_name"]), stripslashes($_POST["last_name"]),
                            current_time('mysql'));

                    if ($general_settings["debug_mode"]) {
                        $donation->setSandbox(true);
                    }

                    // Let the super class save the donation (create or update)
                    $this->saveDonation($donation);
                } else if (strcmp ($res, "INVALID") == 0) {
                    // TODO log more info on this into the db?
                    w2log("Invalid");
                } else {
                    //w2log("Unknown response: " . $res);
                }
            }
            fclose ($fp);
        }
    }

    function convertStatusCodeToStatus($status) {
        switch ($status) {
            case "Completed":
                return DONATION_STATUS_COMPLETED;

            case "Pending":
                return DONATION_STATUS_PENDING;

            case "Refunded":
                return DONATION_STATUS_REFUNDED;

            default:
                return DONATION_STATUS_UNKNOWN;
        }
    }

    function getSettingsForm($settings) {
        return get_donation_can_view_as_string('paypal_ipn_settings', array("settings" => $settings));
    }
    
}
?>