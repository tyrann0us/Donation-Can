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

// Returns the autocomplete options for style designer UI
if (isset($_GET['donation_can_style_autocomplete'])) :
    $q = strtolower($_REQUEST["q"]);
    if (!$q) die();

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

    die();

elseif (isset($_GET['donation_can_get_style_options'])): 
    
    // Style options (TODO: verify nonce!)
    $widget = new DonationWidget();
    $instance = array();

    $style_id = esc_attr($_GET['donation_can_get_style_options']);
    $number = esc_attr($_GET["wn"]);
    
    $widget->number = $number;
    $settings = $widget->get_settings();
    if (is_array($settings)) {
        $instance = $settings[$number];
    }

    // This is run before Donation Can has chance to load the texts properly, so we need to do it here manually...
    load_plugin_textdomain("donation_can", false, "donation-can");

    echo $widget->get_widget_options($style_id, $instance);

    die();

elseif (isset($_GET['donation_can_get_cause_data'])):

    // Donation cause data
    $cause_code = esc_attr($_GET['donation_can_get_cause_data']);
    $field = esc_attr($_GET['field']);

    $cause = donation_can_get_goal($cause_code);

    $filtered_cause = array(
        "donation_goal" => $cause["donation_goal"],
        "currency" => $cause["currency"],
        "description" => $cause["description"],
        "donation_options" => $cause["donation_sums"]
    );

    echo json_encode($filtered_cause);

    die();
    
endif;
?>