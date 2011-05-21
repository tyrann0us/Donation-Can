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

    function get_view($widget_options) {
        return get_donation_can_view_as_string('widget_blocks/donation_list', array("element" => $this->element_data, "donation_strings" => $widget_options["donation_strings"]));
    }

    function get_admin_view($show_options, $id) {
        return "<li class=\"widget-element donation-list-element\" id=\"$id\"><h3>Recent Donations</h3></li>";
    }

}

?>
