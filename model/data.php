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

function donation_can_get_table_name($wpdb) {
  $table_name = $wpdb->prefix . "donation_can_paypal_donations";	
  return $table_name;
}

/**
 * Run as the plugin gets activated. Creates the 
 * table for storing all donations made through the plugin.
 */
function donation_can_install() {
	global $wpdb;
	$table_name = donation_can_get_table_name($wpdb);

	// If the table hasn't yet been created, create it
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$query = "CREATE TABLE " . $table_name . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			transaction_id VARCHAR(255) NOT NULL,
			payment_status VARCHAR(255) NOT NULL,
			time timestamp NOT NULL,
			payer_email VARCHAR(128) NOT NULL,
			payer_name VARCHAR(255) NOT NULL,
			anonymous TINYINT(1) DEFAULT '0' NOT NULL,
			cause_code VARCHAR(64) NOT NULL,
			amount DECIMAL(10,2) DEFAULT '0.00' NOT NULL,
			fee DECIMAL(10,2) DEFAULT '0.00' NOT NULL,
			note TEXT,
			UNIQUE KEY id (id)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($query);

		add_option("donation_can_db_version", "1.0");
	}
}

function donation_can_get_total_raised_for_cause($cause_id) {
	global $wpdb;
	$query = "SELECT amount from " . donation_can_get_table_name($wpdb) . " where cause_code = '" . $wpdb->escape($cause_id) . "';";
	$donations = $wpdb->get_results($query);

	$total = 0;
	foreach ($donations as $donation) {
	    $total += $donation->amount;
  	}

  return $total;
}

function donation_can_get_total_target_for_all_causes() {
	$total_target = 0;
	$causes = get_option("donation_can_causes");
	if ($causes == null) {
		$causes = array();
	}
	
	foreach ($causes as $id => $cause) {
		$total_target += $cause["donation_goal"];
	}
	
	return $total_target;
}

function donation_can_get_total_raised_for_all_causes() {
  global $wpdb;  
  $query = "SELECT amount from " . donation_can_get_table_name($wpdb) . ";";
  $donations = $wpdb->get_results($query);

  $total = 0;
  foreach ($donations as $donation) {
    $total += $donation->amount;
  }

  return $total;
}

// if include_raised_data, adds the raised money as a cell in the array
function donation_can_get_goals($include_raised_data = false) {
	global $wpdb;
	
	$goals = get_option("donation_can_causes");
	if ($goals == null) {
		$goals = array();
	}
	
	if ($include_raised_data) {
		foreach ($goals as $goal) {
			$goal["collected"] = 0;
			$goals[$goal["id"]] = $goal;
		}
		
		$query = "SELECT cause_code, amount FROM " . donation_can_get_table_name($wpdb) . ";";
		$donations = $wpdb->get_results($query);
				
		foreach ($donations as $donation) {
			$goals[$donation->cause_code]["collected"] += $donation->amount;
		}
	}
	
	return $goals;
}


function donation_can_get_donations($offset = 0, $limit = 0, $goal_id = null) {
	global $wpdb;

	$query = "SELECT * FROM " . donation_can_get_table_name($wpdb) . " ORDER BY time DESC";	
	if ($goal_id != null) {
		$query = "SELECT * FROM " . donation_can_get_table_name($wpdb) . " WHERE cause_code = \"" . $goal_id . "\" ORDER BY time DESC";
	}
	
	if ($limit  > 0) {
		$query .= " LIMIT $offset,$limit";
	}
	$query .= ";";
	
	$donations = $wpdb->get_results($query);
	
	return $donations;
}

function donation_can_get_donation_count($goal_id = null) {
	global $wpdb;

	$query = "SELECT count(*) FROM " . donation_can_get_table_name($wpdb);	
	if ($goal_id != null) {
		$query = "SELECT count(*) FROM " . donation_can_get_table_name($wpdb) . " WHERE cause_code = \"" . $goal_id . "\"";
	}

	$query .= ";";
	
	$donations = $wpdb->get_var($query);
	
	return $donations;	
}

function donation_can_create_cause($post, $id = -1) {
	if ($id == -1) {
		$id = attribute_escape($post["id"]);
	}
	$name = attribute_escape($post["name"]);
	$description = attribute_escape($post["description"]);
	$donation_goal = attribute_escape($post["donation_goal"]);
	$return_page = attribute_escape($post["return_page"]);
	$cancelled_return_page = attribute_escape($post["cancelled_return_page"]);
	$continue_button_text = attribute_escape($post["continue_button_text"]);
	$notify_email = attribute_escape($post["notify_email"]);
	$allow_freeform_donation_sum = attribute_escape($post["allow_freeform_donation_sum"]);

	$donation_sum_num = attribute_escape($post["donation_sum_num"]);
	$donation_sums = array();
	for ($i = 0; $i < $donation_sum_num; $i++) {
		$sum_value = attribute_escape($post["donation_sum_" . $i]);
		if ($sum_value != null && $sum_value != "") {
			$donation_sums[] = $sum_value;
		}
	}

	$cause = array();
	$cause["id"] = $id;
	$cause["name"] = $name;
	$cause["description"] = $description;
	$cause["donation_goal"] = $donation_goal;
	$cause["return_page"] = $return_page;
	$cause["cancelled_return_page"] = $cancelled_return_page;
	$cause["continue_button_text"] = $continue_button_text; 
	$cause["notify_email"] = $notify_email;
	$cause["donation_sums"] = $donation_sums;
	$cause["allow_freeform_donation_sum"] = $allow_freeform_donation_sum;

	return $cause;
}

?>