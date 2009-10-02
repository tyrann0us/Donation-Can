<?php
/*
Copyright (c) 2009, Jarkko Laine.

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

function donation_can_goals_menu() {
	$causes = get_option("donation_can_causes");
	if ($causes == null) {
		$causes = array();
	}
		
	// Edit cause
	if (isset($_POST["edit_cause"])) {
		$id = attribute_escape($_POST["edit_cause"]);
		$cause = donation_can_create_cause($_POST, $id);
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
	
	if (isset($_GET["edit"])) {
		$id = $_GET["edit"];
		$goal = $causes[$id];
		$pages = get_pages();
		
		require(__FILE__ . "/../../../view/edit_goal_page.php");
	} else {
		require(__FILE__ . "/../../../view/goals_page.php");		
	}
}
?>