<?php
abstract class DonationCanWidgetStyleElement {

    function to_string($show_options = true, $counter = -1) {
        if ($counter == -1) {
            $id = $this->get_type();
        } else {
            $id = $this->get_type() . "-" . $counter;
        }

        return $this->get_admin_view($show_options, $id);
    }

    abstract function get_type();

    abstract function render_view($widget_options);
    abstract function get_admin_view($show_options, $id);

    /**
     * Returns settings that can be configured for the widget style
     * element. The settings are displayed on the "Widgets" settings tab.
     *
     * TODO: format?
     */
    abstract function get_widget_options();
}

function donation_can_get_available_widget_style_elements() {
    return array("title", "description", "cause-selection", "donation-options", "donation-list",
        "progress", "text", "anonymous", "submit");
}

require("donation_can_widget_title_element.php");
require("donation_can_widget_description_element.php");
require("donation_can_widget_submit_element.php");
require("donation_can_widget_donation_options_element.php");
require("donation_can_widget_progress_element.php");
require("donation_can_widget_donation_list_element.php");
require("donation_can_widget_text_element.php");
require("donation_can_widget_anonymous_element.php");
require("donation_can_widget_cause_selection_element.php");
?>