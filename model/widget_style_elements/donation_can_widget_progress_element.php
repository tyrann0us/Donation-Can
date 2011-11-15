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

class DonationCanWidgetProgressElement extends DonationCanWidgetStyleElement {

    function DonationCanWidgetProgressElement($element_data) {
        $this->element_data = $element_data;
    }

    function get_type() {
        return "progress-element";
    }

    function render_view($widget_options) {
        $goal = $widget_options["goal"];
        $past_goal = false;

        if ($widget_options["show_progress"]) {
            if ($goal["donation_goal"] == 0) {
                $percentage = 0;
            } else {
                if ($widget_options["show_past_goal"] == "1" && $widget_options["raised_so_far"] > $goal["donation_goal"]) {
                    $past_goal = true;
                    $percentage = ($goal["donation_goal"] / $widget_options["raised_so_far"]) * 100;
                } else {
                    $percentage = ($widget_options["raised_so_far"] / $goal["donation_goal"]) * 100;
                    if ($percentage > 100) {
                        $percentage = 100;
                    }
                }
            }

            $progress_text = $this->element_data["text-format"];

            $progress_text = str_replace("%PERCENTAGE%", intval($percentage), $progress_text);
            $progress_text = str_replace("%CURRENCY%", $widget_options["currency"], $progress_text);
            $progress_text = str_replace("%TARGET%", donation_can_number_format($goal["donation_goal"]), $progress_text);
            $progress_text = str_replace("%CURRENT%", donation_can_number_format($widget_options["raised_so_far"]), $progress_text);

            require_donation_can_view('widget_blocks/progress_bar',
                    array(
                        "element" => $this->element_data,
                        "target" => $goal["donation_goal"],
                        "current" => $widget_options["raised_so_far"],
                        "percentage" => $percentage,
                        "progress_text" => $progress_text,
                        "currency" => $widget_options["currency"],
                        "past_goal" => $past_goal));
        }
    }

    function get_admin_view($show_options, $id) {
        return get_donation_can_view_as_string('widget_blocks/progress_bar_options', array('data' => $this->element_data, 'show_options' => $show_options, "id" => $id));
    }

    function get_widget_options() {
        return array(
            "show_progress" => array(
                "type" => "checkbox",
                "value" => "1",
                "label" => __('Display progress', "donation_can")
            ),
            "show_past_goal" => array(
                "type" => "checkbox",
                "value" => "1",
                "label" => __("Display donations exceeding goal", "donation_can")
            )
        );
    }

}

?>
