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

function donation_can_sort_ints($a, $b) {
    if ($a < $b) {
        return -1;
    } else if ($a > $b) {
        return 1;
    }
        
    return 0;
}

function donation_can_sort_goals($a, $b) {
    $general_settings = donation_can_get_general_settings();
    $sort_field = $general_settings["sort_causes_field"];
    $sort_order = $general_settings["sort_causes_order"];

    $sort_result = 0;

    if ($a != null && $b != null) {
        switch ($sort_field) {
            case 1:
                $sort_result = strcasecmp($a["name"], $b["name"]);
                break;

            case 2:
                $sort_result = strcasecmp($a["author"], $b["author"]);
                break;

            case 3:
                $sort_result = donation_can_sort_ints(intval($a["donation_goal"]),
                        intval($b["donation_goal"]));
                break;

            case 4:
                $collected_a = donation_can_get_total_raised_for_cause($a["id"]);
                $collected_b = donation_can_get_total_raised_for_cause($b["id"]);

                $sort_result = donation_can_sort_ints($collected_a, $collected_b);
                break;

            case 5:
                $date_a = strtotime($a["created_at"]);
                $date_b = strtotime($b["created_at"]);

                $sort_result = donation_can_sort_ints($date_a, $date_b);
                break;

            default:
                break;
        }

    }

    if ($sort_order == "DESC") {
        $sort_result = -$sort_result;
    }

    return $sort_result;
}

function donation_can_goals_menu() {
    $causes = get_option("donation_can_causes");
    if ($causes == null) {
        $causes = array();
    }

    // TODO: make sorting happen depending on user setting (maybe in Javascript?)
    uasort($causes, "donation_can_sort_goals");

    // Edit cause
    if (isset($_POST["edit_cause"])) {
        $id = attribute_escape($_POST["edit_cause"]);
        $cause = donation_can_create_cause($_POST, false, $id);
        $causes[$id] = $cause;

        update_option("donation_can_causes", $causes);
        render_user_notification(__("Updated goal:", "donation_can") . " " . $id);
    }

    // Remove cause
    if (isset($_POST["remove_cause"])) {
        $id = attribute_escape($_POST["remove_cause"]);
        unset($causes[$id]);

        update_option("donation_can_causes", $causes);
        render_user_notification(__("Deleted goal:", "donation_can") . " " . $id);
    }

    // Reset cause (has to be through POST to make sure the user doesn't do this again by accident...)
    if (isset($_POST["reset"])) {
        $id = attribute_escape($_POST["reset"]);
        donation_can_reset_goal($id);

        render_user_notification(__("Donations reset for goal: ", "donation_can") . " " . $id);
    }

    if (isset($_GET["edit"])) {
        $id = $_GET["edit"];
        $goal = $causes[$id];
        $pages = get_pages();

        $donation_sums = $goal["donation_sums"];
        if ($donation_sums == null) {
            $general_settings = donation_can_get_general_settings();
            $donation_sums = $general_settings["donation_sums"];
        }

        require_donation_can_view('edit_goal_page', array(
            "causes" => $causes,
            "currency" => $goal["currency"],
            "currency_display" => donation_can_currency_defaults($goal["currency"], true),
            "id" => $id,
            "goal" => $goal,
            "pages" => $pages,
            "donation_sums" => $donation_sums,
            "edit" => true));
    } else {
        require_donation_can_view('goals_page', array("causes" => $causes));
    }
}
?>