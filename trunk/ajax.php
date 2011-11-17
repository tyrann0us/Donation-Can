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

function donation_can_ajax_get_style_options() {
    $nonce = $_REQUEST['nonce'];

    if (!wp_verify_nonce($nonce, 'donation_can_ajax-style_options')) {
        die('Nonce verification failed.');
    }

    if (current_user_can('dc_styles')) {
        $widget = new DonationWidget();
        $instance = array();

        $style_id = esc_attr($_REQUEST['style']);
        $number = esc_attr($_REQUEST["wn"]);

        $widget->number = $number;
        $settings = $widget->get_settings();
        if (is_array($settings)) {
            $instance = $settings[$number];
        }

        // TODO: see if this is needed anymore...?
        // This is run before Donation Can has chance to load the texts properly, so we need to do it here manually...
        //load_plugin_textdomain("donation_can", false, "donation-can");

        echo $widget->get_widget_options($style_id, $instance);
    }

    exit;
}

function donation_can_ajax_style_autocomplete() {
    $q = strtolower($_REQUEST["q"]);
    if (!$q) exit;

    $items = array(
        ".backlink",
        ".backlink a",
        ".description",
        ".donations-list",
        ".donations-list li",
        ".donation-date",
        ".donation-options",
        ".donation-options .donation-callout",
        ".donation-options .donation-button-list",
        ".donation-options .donation-button-list a.button",
        ".donation-options select",
        ".progress-element",
        ".progress-element .progress-meter",
        ".progress-element .progress-meter .progress-container",
        ".progress-element .progress-meter .progress-container .progress-bar",
        ".progress-element .progress-text",
        ".progress-element .progress-text .percentage",
        ".progress-element .progress-text .raised-label",
        ".progress-element .progress-text .of-label",
        ".progress-element .progress-text .currency",
        ".progress-element .progress-text .goal",
        ".progress-element .progress-text .goal-label",
        ".progress-element .progress-text .raised",
        ".submit-donation",
        ".submit-donation input",
        ".custom-text",
        ".donation-widget-title",
        ".donation-can-cause-selection"
    );

    header("Content-type: text/plain");

    foreach ($items as $key) {
	if (strpos(strtolower($key), $q) !== false) {
            echo "$key\n";
	}
    }

    exit;
}

function donation_can_ajax_get_cause_data() {
    // Donation cause data
    $cause_code = esc_attr(esc_attr($_REQUEST['cause']));    
    $cause = donation_can_get_goal($cause_code);

    $filtered_cause = array(
        "donation_goal" => $cause["donation_goal"],
        "currency" => $cause["currency"],
        "description" => $cause["description"],
        "donation_options" => $cause["donation_sums"]
    );

    echo json_encode($filtered_cause);
    exit;
}

function donation_can_ajax_export() {
    $nonce = $_REQUEST['_wpnonce'];
    $type = esc_attr($_REQUEST['type']);

    $requested_format = esc_attr($_REQUEST['format']);
    if (!strcasecmp($requested_format, "csv") && !strcasecmp($requested_format, "xml")) {
        $requested_format = "csv";
    }

    if ($type != "causes" && $type != "donations" && $type != "settings" && $type != "styles") {
        die("Invalid export type: " . $type);
    }

    if (!wp_verify_nonce($nonce, 'donation_can_ajax-export')) {
        die('Nonce verification failed.');
    }

    if (current_user_can('dc_general_settings')) {
        $exporter = new DonationCanDataExport(donation_can_get_options_handler());

        $format = 'csv';

        if ($type == "causes") {
            $output = $exporter->exportCauses(strtoupper($requested_format));
            $format = strtolower($requested_format);
            $filename = 'donation_can-causes.' . date('Y-m-d') . '.' . $format;
        } else if ($type == "settings") {
            $output = $exporter->exportSettings();
            $filename = 'donation_can-settings.' . date('Y-m-d') . '.xml';
            $format = 'xml';
        } else if ($type == "styles") {
            $output = $exporter->exportStyles();
            $filename = 'donation_can-styles.' . date('Y-m-d') . '.xml';
            $format = 'xml';
        } else if ($type == "donations") {
            $output = $exporter->exportDonations(strtoupper($requested_format));
            $format = strtolower($requested_format);
            $filename = 'donation_can-donations.' . date('Y-m-d') . '.' . $format;
        }

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Type: text/' . $format . '; charset=' . get_option( 'blog_charset' ), true);

        echo $output;
    }
    
    exit;
}

add_action('wp_ajax_donation_can-get_style_options', 'donation_can_ajax_get_style_options');

add_action('wp_ajax_nopriv_donation_can-get_cause_data', 'donation_can_ajax_get_cause_data');
add_action('wp_ajax_donation_can-get_cause_data', 'donation_can_ajax_get_cause_data');

add_action('wp_ajax_donation_can-style_autocomplete', 'donation_can_ajax_style_autocomplete');

add_action('wp_ajax_donation_can-export', 'donation_can_ajax_export');
?>