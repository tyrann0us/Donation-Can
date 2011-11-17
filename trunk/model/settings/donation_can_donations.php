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

function donation_can_donations_menu() {
    $page = intval(esc_attr($_GET["paged"]));
    if (!$page) {
        $page = 1;
    }

    $p = $page - 1; // A zero based page

    $filter_goal = esc_attr($_GET["filter_goal"]);
    if ($filter_goal == null || $filter_goal == "") {
        $filter_goal = attribute_escape($_GET["filter_goal"]);
    }

    // Remove donation
    if (isset($_POST["remove_donation"]) && check_admin_referer('donation_can-remove_donation')) {
        $id = attribute_escape($_POST["remove_donation"]);

        donation_can_delete_donation($id);

        render_user_notification(__("Deleted donation:", "donation_can") . " " . $id);
    }

    $donations_per_page = 20;
	
    $start_index = $p * $donations_per_page;
    $total_donations = donation_can_get_donation_count($filter_goal);
    $total_pages =  intval($total_donations / $donations_per_page);

    if ($total_donations > $total_pages * $donations_per_page) {
        $total_pages++;
    }
		
    $donations = donation_can_get_donations($start_index, $donations_per_page, $filter_goal);
    $goals = donation_can_get_goals(true);

    require_donation_can_view('donations_page', array("donations_per_page" => $donations_per_page,
        "start_index" => $start_index, "total_donations" => $total_donations,
        "total_pages" => $total_pages, "donations" => $donations, "goals" => $goals,
        "filter_goal" => $filter_goal, "page" => $page));
}
?>