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

class DonationCanWidgetDonationListElement extends DonationCanWidgetStyleElement {

    function DonationCanWidgetDonationListElement($element_data) {
        $this->element_data = $element_data;
    }

    function get_type() {
        return "donation-list-element";
    }

    function render_view($widget_options) {
        require_donation_can_view('widget_blocks/donation_list', array("element" => $this->element_data, "donation_strings" => $widget_options["donation_strings"], "show_donation_list_title" => $widget_options["show_donation_list_title"]));
    }

    function get_admin_view($show_options, $id) {
        return "<li class=\"widget-element donation-list-element\" id=\"$id\"><h3>" . __("Recent Donations", "donation_can") . "</h3></li>";
    }

    function get_widget_options() {
        return array(
            "show_donations" => array(
                "type" => "checkbox",
                "value" => "1",
                "label" => __('Display latest donations', "donation_can")
            ),
            "num_donations" => array(
                "type" => "text",
                "label" => __("Number of donations to display:", "donation_can")
            )
        );
    }

}

?>