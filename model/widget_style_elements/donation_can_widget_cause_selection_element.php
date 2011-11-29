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

class DonationCanWidgetCauseSelectionElement extends DonationCanWidgetStyleElement {

    function DonationCanWidgetTeamSelectionElement($element_data) {
        $this->element_data = $element_data;
    }

    function get_type() {
        return "cause-selection-element";
    }

    function render_view($widget_params) {
        // Only show cause selection if the summary option is selected
        if ($widget_params["goal_id"] == "__all__") {
            $causes = donation_can_get_goals();

            require_donation_can_view('widget_blocks/cause_selection', array("element" => $this->element_data, "causes" => $causes, "options" => $widget_params));
        }
    }

    function get_admin_view($show_options, $id) {
        return "<li class=\"widget-element cause-selection-element\" id=\"$id\"><h3>" . __("Cause Selection", "donation_can") . "</h3></li>";
    }

    function get_widget_options() {
        return array("select_cause_label" => array(
            "type" => "text",
            "label" => __("Title for cause selection:", "donation_can"),
            "default" => __("Select cause", "donation_can")
        ));
    }
}

?>