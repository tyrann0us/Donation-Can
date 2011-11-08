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

class DonationCanGeneralSettings {

    // Data structure for holding settings
    var $general_settings;
    var $options;

    function  __construct($optionsStore) {
        $this->options = $optionsStore;
    }

    function load() {
        if ($this->options != null) {
            $this->general_settings = $this->options->get_option("donation_can_general");
        } else {
            die("Options store not set");
        }
    }

    function save() {
        if ($this->options != null) {
            $this->options->update_option("donation_can_general", $this->general_settings);
        } else {
            die("Options store not set");
        }
    }

    // SETTERS

    function setPayPalEmail($email) {
        $this->general_settings["paypal_email"] = $email;
    }

    function setPayPalSandboxEmail($email) {
        $this->general_settings["paypal_sandbox_email"] = $email;
    }

    function setRequireShipping($requireShipping) {
        $this->general_settings["require_shipping"] = $requireShipping;
    }

    function setAskForNote($askForNote) {
        $this->general_settings["ask_for_note"] = $askForNote;
    }

    function setNoteFieldLabel($label) {
        $this->general_settings["note_field_label"] = $label;
    }

    function setContinueButtonText($text) {
        $this->general_settings["continue_button_text"] = $text;
    }

    function setReturnPage($pageId) {
        $this->general_settings["return_page"] = $pageId;
    }

    function setCancelledPage($pageId) {
        $this->general_settings["cancel_return_page"] = $pageId;
    }

    function setLogoOnPayPalPage($logoUrl) {
        $this->general_settings["logo_on_paypal_page"] = $logoUrl;
    }

    function setHeaderOnPayPalPage($headerUrl) {
        $this->general_settings["header_on_paypal_page"] = $headerUrl;
    }

    function setBackgroundOnPayPalPage($bg) {
        $this->general_settings["bg_on_paypal_page"] = $bg;
    }

    function setHeaderBackgroundOnPayPalPage($bg) {
        $this->general_settings["header_bg_on_paypal_page"] = $bg;
    }

    function setHeaderBorderOnPayPalPage($border) {
        $this->general_settings["header_border_on_paypal_page"] = $border;
    }

    // TODO: refactor to add?
    function setNotifyEmail($emailList) {
        $this->general_settings["notify_email"] = $emailList;
    }

    function setDefaultCurrency($currency) {
        $this->general_settings["currency"] = $currency;
    }

    function addDonationOption($sum) {
        if (!isset($this->general_settings["donation_sums"]) || $this->general_settings["donation_sums"] == null) {
            $this->general_settings["donation_sums"] = array();
        }
        $this->general_settings["donation_sums"][] = $sum;
    }

    // TODO is this deprecated?
    function setStyle($style) {
        $this->general_settings["style"] = $style;
    }

    // TODO is this deprecated?
    function setCustom($custom) {
        $this->general_settings["custom"] = $custom;
    }

    function setDebugMode($debug) {
        $this->general_settings["debug_mode"] = $debug;
    }

    function setLoggingMode($logging) {
        $this->general_settings["enable_logging"] = $logging;
    }

    // TODO is this deprecated?
    function setSortCausesField($field) {
        $this->general_settings["sort_causes_field"] = $field;
    }

    // TODO is this deprecated?
    function setSortDonationsField($field) {
        $this->general_settings["sort_donations_field"] = $field;
    }

    // TODO is this deprecated?
    function setSortCausesOrder($order) {
        $this->general_settings["sort_causes_order"] = $order;
    }

    // TODO is this deprecated?
    function setSortDonationsOrder($order) {
        $this->general_settings["sort_donations_order"] = $order;
    }

    function setShowBackLink($value) {
        $this->general_settings["link_back"] = $value;
    }

    function setSubtractPayPalFees($subtractFees) {
        $this->general_settings["subtract_paypal_fees"] = $subtractFees;
    }

    function setUseHTMLEmails($useHtml) {
        $this->general_settings["use_html_emails"] = $useHtml;
    }

    function setEmailTemplate($template) {
        $this->general_settings["email_template"] = $template;
    }

    function setReceiptTemplate($template) {
        $this->general_settings["receipt_template"] = $template;
    }

    // TODO: should we merge some setters?
    function setSendReceipt($sendReceipt) {
        $this->general_settings["send_receipt"] = $sendReceipt;
    }

    function setReceiptSubject($subject) {
        $this->general_settings["receipt_subject"] = $subject;
    }

    function setReceiptThreshold($threshold) {
        $this->general_settings["receipt_threshold"] = $threshold;
    }

    function setEmailFrom($email, $name) {
        $this->general_settings["email_from"] = $email;
        $this->general_settings["email_from_name"] = $name;
    }
}

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

    // TODO: add parameter validation to general settings!

    // Save general settings
    if ($_POST["edit_settings"] == "Y" && check_admin_referer('donation_can-general_settings')) {
        $settings = new DonationCanGeneralSettings();
        $settings->setPayPalEmail(esc_attr($_POST["paypal_email"]));
        $settings->setPayPalSandboxEmail(esc_attr($_POST["paypal_sandbox_email"]));

        $settings->setAskForNote(esc_attr($_POST["ask_for_note"]));
        $settings->setNoteFieldLabel(stripslashes(esc_attr($_POST["note_field_label"])));
        $settings->setRequireShipping(esc_attr($_POST["require_shipping"]));

        $settings->setReturnPage(esc_attr($_POST["return_page"]));
        $settings->setCancelledPage(esc_attr($_POST["cancel_return_page"]));
        $settings->setContinueButtonText(esc_attr($_POST["continue_button_text"]));

        $settings->setLogoOnPayPalPage(esc_attr($_POST["logo_on_paypal_page"]));
        $settings->setHeaderOnPayPalPage(esc_attr($_POST["header_on_paypal_page"]));
        $settings->setBackgroundOnPayPalPage(esc_attr($_POST["bg_on_paypal_page"]));
        $settings->setHeaderBackgroundOnPayPalPage(esc_attr($_POST["header_bg_on_paypal_page"]));
        $settings->setHeaderBorderOnPayPalPage(esc_attr($_POST["header_border_on_paypal_page"]));

        // TODO parse better as list
        $settings->setNotifyEmail(esc_attr($_POST["notify_email"]));

        // TODO are style and custom used anymore?
        $settings->setStyle(esc_attr($_POST["style"]));
        $settings->setCustom(esc_attr($_POST["custom"]));

        // TODO validate currency
        $settings->setDefaultCurrency(esc_attr($_POST["currency"]));

        $settings->setDebugMode(esc_attr($_POST["debug_mode"]) == "1");
        $settings->setLoggingMode(esc_attr($_POST["enable_logging"]) == "1");

        $settings->setShowBackLink(esc_attr($_POST["link_back"]) == "1");
        $settings->setSubtractPayPalFees(esc_attr($_POST["subtract_fees"]) == "1");

        $settings->setEmailFrom(esc_attr($_POST["email_from"]), stripslashes($_POST["email_from_name"]));
        $settings->setEmailTemplate(stripslashes($_POST["email_template"]));
        $settings->setReceiptSubject(esc_attr(stripslashes($_POST["receipt_subject"])));
        $settings->setReceiptTemplate(stripslashes($_POST["receipt_template"]));
        $settings->setSendReceipt(esc_attr($_POST["send_receipt"]) == "1");
        $settings->setReceiptThreshold(intval(esc_attr($_POST["receipt_threshold"])));
        $settings->setUseHTMLEmails(esc_attr($_POST["use_html_emails"]) == "1");
        
        $settings->setSortCausesField(esc_attr($_POST["sort_causes_field"]));
        $settings->setSortCausesOrder(esc_attr($_POST["sort_causes_order"]));
        $settings->setSortDonationsField(esc_attr($_POST["sort_donations_field"]));
        $settings->setSortDonationsOrder(esc_attr($_POST["sort_donations_order"]));

        $donation_sum_num = esc_attr($_POST["donation_sum_num"]);
        for ($i = 0; $i < $donation_sum_num; $i++) {
            $sum_value = esc_attr($_POST["donation_sum_" . $i]);

            if ($sum_value != null && $sum_value != "") {
                $settings->addDonationOption($sum);
            }
        }

        if ($settings->save()) {
            render_user_notification(__("Donation Can settings updated", "donation_can"));
        }
    }

    // Default values for email
    if ($general_settings["email_from"] == null || $general_settings["email_from"] == "") {
        $general_settings["email_from"] = get_option('admin_email');
    }
    if ($general_settings["email_from_name"] == null || $general_settings["email_from_name"] == "") {
        $general_settings["email_from_name"] = "Donation Can";
    }


    require_donation_can_view('settings_page', array("general_settings" => $general_settings,
        "pages" => $pages, "style_options" => $style_options));
    
}

?>