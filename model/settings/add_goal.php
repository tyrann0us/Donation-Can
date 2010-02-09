<?php
/*
Copyright (c) 2009-2010, Jarkko Laine.

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

function donation_can_add_goal_menu() {
	$causes = get_option("donation_can_causes");
	if ($causes == null) {
		$causes = array();
	}

        $currency = donation_can_get_current_currency();
        
	$pages = get_pages();
	  
	// Add a new cause
	if ($_POST["add_cause"] == "Y") {
		$cause = donation_can_create_cause($_POST);
		
		if (isset($causes[$cause["id"]])) {
			render_user_notification("<strong>". __("Error", "donation-can") . ":</strong> " . __("That goal id is already in use!", "donation_can"));
		} else {
			$causes[$cause["id"]] = $cause;
			update_option("donation_can_causes", $causes);
			render_user_notification(__("Added goal:", "donation_can") . " " . $cause["id"] 
				. ". <a href=\"admin.php?page=goals.php\">" . __("Browse and edit goals", "donation_can") . "</a>");
		}
	} 
	
	require(__FILE__ . "/../../../view/add_goal_page.php");
}
?>