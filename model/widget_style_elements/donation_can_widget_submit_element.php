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

class DonationCanWidgetSubmitElement extends DonationCanWidgetStyleElement {

    function DonationCanWidgetSubmitElement($element_data) {
        $this->element_data = $element_data;
    }

    function get_type() {
        return "submit-element";
    }

    function render_view($widget_options) {
        require_donation_can_view('widget_blocks/submit_button', array("data" => $this->element_data));
    }

    function get_admin_view($show_options, $id) {
        return get_donation_can_view_as_string('widget_blocks/submit_button_options', array('data' => $this->element_data, 'show_options' => $show_options, "id" => $id));
    }

    function get_widget_options() {
        return array();
    }

}

?>