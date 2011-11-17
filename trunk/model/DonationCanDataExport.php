<?php

/**
 * A class that handles exporting data from Donation Can to an XML or CSV file.
 *
 * @author Jarkko Laine
 */
class DonationCanDataExport {

    // A data access object for accessing WP options
    protected $options;

    function  __construct($options) {
        $this->options = $options;
    }

    function exportDonations($format = "CSV", $separator = ",") {
        $output = "";

        // Right now, exports everything... Let's see if we want to filter later
        $donations = donation_can_get_donations(0, 0, null, true, 0, 0, true);

        if ($format == "CSV") {
            // Title row
            $output = "ID,Item number,Transaction ID,Status,Date,Email,First name,Last name,Anonymous,Cause code,Amount,Fee,Note,Sandbox,Offline donation,Deleted\n";

            foreach ($donations as $donation) {
                $row = array($donation->id,
                    $donation->item_number,
                    $donation->transaction_id,
                    $donation->payment_status,
                    $donation->time,
                    $donation->payer_email,
                    $donation->payer_name,
                    $donation->payer_lastname,
                    ($donation->anonymous == "1") ? "Yes" : "No",
                    $donation->cause_code,
                    $donation->amount,
                    $donation->fee,
                    $donation->note,
                    ($donation->sandbox == "1") ? "Yes" : "No",
                    ($donation->offline == "1") ? "Yes" : "No",
                    ($donation->deleted == "1") ? "Yes" : "No");

                $output .= join($separator, $row) . "\n";
            }
        } else {
            $charset = "UTF-8";
            if (function_exists('get_bloginfo')) {
                $charset = get_bloginfo('charset');
            }

            $output .= "<?xml version=\"1.0\" encoding=\"" . $charset . "\" ?>\n";

            $output .= "<donations>\n";
            foreach ($donations as $donation) {
                $output .= "\t<donation id=\"" . $donation->id . "\">\n";

                $output .= "\t\t<item_number>" . $donation->item_number . "</item_number>\n";
                $output .= "\t\t<transaction_id>" . $donation->transaction_id . "</transaction_id>\n";
                $output .= "\t\t<payment_status>" . $donation->payment_status . "</payment_status>\n";
                $output .= "\t\t<time>" . $donation->time . "</time>\n";
                $output .= "\t\t<payer_email>" . $donation->payer_email . "</payer_email>\n";
                $output .= "\t\t<payer_name>" . $donation->payer_name . "</payer_name>\n";
                $output .= "\t\t<payer_lastname>" . $donation->payer_lastname . "</payer_lastname>\n";
                $output .= "\t\t<anonymous>" . $donation->anonymous . "</anonymous>\n";
                $output .= "\t\t<cause_code>" . $donation->cause_code . "</cause_code>\n";
                $output .= "\t\t<amount>" . $donation->amount . "</amount>\n";
                $output .= "\t\t<fee>" . $donation->fee . "</fee>\n";
                $output .= "\t\t<note>" . $donation->note . "</note>\n";
                $output .= "\t\t<sandbox>" . $donation->sandbox . "</sandbox>\n";
                $output .= "\t\t<offline>" . $donation->offline . "</offline>\n";
                $output .= "\t\t<deleted>" . $donation->deleted . "</deleted>\n";

                $output .= "\t</donation>\n";
            }
            $output .= "</donations>";
        }

        return $output;
    }

    function exportStyles() {
        $output = "";

        $styles = $this->options->get_option("donation_can_widget_styles");

        $charset = "UTF-8";
        if (function_exists('get_bloginfo')) {
            $charset = get_bloginfo('charset');
        }

        $output .= "<?xml version=\"1.0\" encoding=\"" . $charset . "\" ?>\n";
        $output .= "<widget_styles>\n";

        foreach ($styles as $id => $style) {
            if (!$style["locked"]) {
                $output .= "\t<widget_style id=\"" . $id . "\" name=\"" . $style["name"] . "\">\n";

                $output .= "\t\t<elements>\n";
                if (isset($style["elements"]) && is_array($style["elements"])) {
                    foreach ($style["elements"] as $element) {
                        $output .= "\t\t\t<element type=\"" . $element["type"] . "\">\n";

                        foreach ($element as $option => $value) {
                            if ($option != "type") {
                                $output .= "\t\t\t\t<option name=\"" . $option . "\">" . htmlentities($value) . "</option>\n";
                            }
                        }

                        $output .= "\t\t\t</element>\n";
                    }
                }
                $output .= "\t\t</elements>\n";

                $output .= "\t\t<css_definitions>\n";

                if (isset($style["css"]) && is_array($style["css"])) {
                    foreach ($style["css"] as $selector => $definition) {
                        $output .= "\t\t\t<definition selector=\"" . $selector . "\">" . $definition . "</definition>\n";
                    }
                }

                $output .= "\t\t</css_definitions>\n";

                $output .= "\t</widget_style>\n";
            }
        }

        $output .= "</widget_styles>";

        return $output;
    }

    // Settings can only be exported in XML format
    function exportSettings() {
        $output = "";

        $settings = $this->options->get_option("donation_can_general");

        if (is_array($settings) && !empty($settings)) {
            ksort($settings);

            $charset = "UTF-8";
            if (function_exists('get_bloginfo')) {
                $charset = get_bloginfo('charset');
            }

            $output .= "<?xml version=\"1.0\" encoding=\"" . $charset . "\" ?>\n";
            $output .= "<general_settings>\n";

            foreach ($settings as $key => $value) {
                if ($key == "donation_sums") {
                    $value = join(",", $value);
                }

                $output .= "\t<option name=\"" . $key . "\">" . $value . "</option>\n";

            }

            $output .= "</general_settings>";
        }

        return $output;
    }

    function exportCauses($format = "CSV", $separator = ";") {
        $causes = $this->options->get_option("donation_can_causes");

        $output = "";

        if ($format == "XML") {
            $charset = "UTF-8";
            if (function_exists('get_bloginfo')) {
                $charset = get_bloginfo('charset');
            }

            $output .= "<?xml version=\"1.0\" encoding=\"" . $charset . "\" ?>\n";
            $output .= "<causes>\n";
        }

        if (is_array($causes)) {
            foreach ($causes as $id => $cause) {
                if ($format == "CSV") {
                    // Format cause into CSV row
                    $donation_sums = "";
                    if (is_array($cause["donation_sums"])) {
                        $donation_sums = join(",", $cause["donation_sums"]);
                    }

                    $data = array(
                        $id,
                        $cause["name"],
                        $cause["description"],
                        $cause["donation_goal"],
                        $cause["currency"],
                        $cause["return_page"],
                        $cause["cancelled_return_page"],
                        $cause["continue_button_text"],
                        $cause["notify_email"],
                        $donation_sums,
                        $cause["allow_freeform_donation_sums"]
                    );

                    $output .= join($separator, $data) . "\n";
                }
                else if ($format == "XML") {
                    $donation_options = "";
                    if (is_array($cause["donation_sums"])) {
                        $donation_options .= "\n";
                        foreach ($cause["donation_sums"] as $sum) {
                            $donation_options .= "\t\t\t<option>" . $sum . "</option>\n";
                        }
                        $donation_options .= "\t\t";
                    }

                    $notify_emails = "";
                    if (isset($cause["notify_email"]) && strlen($cause["notify_email"]) > 0) {
                        $notify_emails .= "\n";
                        $emails = explode(",", $cause["notify_email"]);
                        foreach ($emails as $email) {
                            $notify_emails .= "\t\t\t<email>" . $email . "</email>\n";
                        }
                        $notify_emails .= "\t\t";
                    }


                    $output .= "\t<cause id=\"" . $id . "\">\n"
                        . "\t\t<name>" . $cause["name"] . "</name>\n"
                        . "\t\t<description>" . $cause["description"] . "</description>\n"
                        . "\t\t<goal>" . $cause["donation_goal"] . "</goal>\n"
                        . "\t\t<currency>" . $cause["currency"] . "</currency>\n"
                        . "\t\t<return_page>" . $cause["return_page"] . "</return_page>\n"
                        . "\t\t<cancelled_page>" . $cause["cancelled_return_page"] . "</cancelled_page>\n"
                        . "\t\t<continue_link_text>" . $cause["continue_button_text"] . "</continue_link_text>\n"
                        . "\t\t<notify_email>" . $notify_emails . "</notify_email>\n"
                        . "\t\t<donation_options>" . $donation_options . "</donation_options>\n"
                        . "\t\t<allow_freeform_donation_sums>" . $cause["allow_freeform_donation_sums"] . "</allow_freeform_donation_sums>\n"
                        . "\t</cause>\n";
                }
            }
        }

        if ($format == "XML") {
            $output .= "</causes>";
        }

        return $output;
    }

}
?>