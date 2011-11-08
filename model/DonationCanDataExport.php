<?php

/**
 * A class that handles exporting data from Donation Can to an XML or CSV file.
 *
 * @author Jarkko Laine
 */
class DonationCanDataExport {

    // A data access object for accessing WP options
    var $options;

    function  __construct($options) {
        $this->options = $options;
    }

    // Function from StackOverflow...
    function zip($source, $destination) {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true)
        {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file)
            {
                $file = str_replace('\\', '/', realpath($file));

                if (is_dir($file) === true)
                {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                }
                else if (is_file($file) === true)
                {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        }
        else if (is_file($source) === true)
        {
            $zip->addFromString(basename($source), file_get_contents($source));
        }

        return $zip->close();
    }

    // TODO: format?
    function exportAll($path) {
        // TODO: what to do if an old version exists already?
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        // Export settings
        $settings = $this->exportSettings("\t");
        $fp = fopen($path . "/settings.txt", "w");
        fwrite($fp, $settings, strlen($settings));
        fclose($fp);

        // Export causes
        $causes = $this->exportCauses("CSV", "\t");
        $fp = fopen($path . "/causes.txt", "w");
        fwrite($fp, $causes, strlen($causes));
        fclose($fp);

        $this->zip($path, "$path.zip");

        if (file_exists($path . "/settings.txt")) {
            unlink($path . "/settings.txt");
        }
        if (file_exists($path . "/causes.txt")) {
            unlink($path . "/causes.txt");
        }
        
        rmdir($path);
    }

    function exportDonations() {
        // TODO!
    }

    function exportStyles() {
        // TODO!
    }

    // Settings can only be exported in CSV format
    function exportSettings($delimiter = ";") {
        $output = "";

        $settings = $this->options->get_option("donation_can_general");

        if (is_array($settings) && !empty($settings)) {
            ksort($settings);

            foreach ($settings as $key => $value) {
                if ($key == "donation_sums") {
                    $value = join(",", $value);
                }

                $output .= "$key" . $delimiter . "$value\n";
            }
        }

        return $output;
    }

    function exportCauses($format = "CSV", $separator = ";") {
        $causes = $this->options->get_option("donation_can_causes");

        $output = "";
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
                            $donation_options .= "\t\t<option>" . $sum . "</option>\n";
                        }
                        $donation_options .= "\t";
                    }

                    $notify_emails = "";
                    if (isset($cause["notify_email"]) && strlen($cause["notify_email"]) > 0) {
                        $notify_emails .= "\n";
                        $emails = explode(",", $cause["notify_email"]);
                        foreach ($emails as $email) {
                            $notify_emails .= "\t\t<email>" . $email . "</email>\n";
                        }
                        $notify_emails .= "\t";
                    }

                    $output .= "<cause id=\"" . $id . "\">\n"
                        . "\t<name>" . $cause["name"] . "</name>\n"
                        . "\t<description>" . $cause["description"] . "</description>\n"
                        . "\t<goal>" . $cause["donation_goal"] . "</goal>\n"
                        . "\t<currency>" . $cause["currency"] . "</currency>\n"
                        . "\t<return_page>" . $cause["return_page"] . "</return_page>\n"
                        . "\t<cancelled_page>" . $cause["cancelled_return_page"] . "</cancelled_page>\n"
                        . "\t<continue_link_text>" . $cause["continue_button_text"] . "</continue_link_text>\n"
                        . "\t<notify_email>" . $notify_emails . "</notify_email>\n"
                        . "\t<donation_options>" . $donation_options . "</donation_options>\n"
                        . "\t<allow_freeform_donation_sums>" . $cause["allow_freeform_donation_sums"] . "</allow_freeform_donation_sums>\n"
                        . "</cause>\n";
                }
            }
        }

        return $output;
    }

}
?>