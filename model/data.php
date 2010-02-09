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

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    $table_name = donation_can_get_table_name($wpdb);

    $db_version = get_option("donation_can_db_version");

    if (!$db_version) {
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

            dbDelta($query);
        }
        add_option("donation_can_db_version", "1.0");
        $db_version = "1.0";
    }

    // First update: Add fields for marking sandbox and offline donatios
    if ($db_version == "1.0") {
        $query = "ALTER TABLE " .$table_name
            . " ADD COLUMN sandbox TINYINT(1) DEFAULT '0' NOT NULL,"
            . " ADD COLUMN offline TINYINT(1) DEFAULT '0' NOT NULL;";

        $wpdb->query($query);
        update_option("donation_can_db_version", "2.0");
    }
}

function donation_can_get_total_raised_for_cause($cause_id) {
    global $wpdb;
    $query = "SELECT amount from " . donation_can_get_table_name($wpdb) . " where cause_code = '" . $wpdb->escape($cause_id) . "'";

    $general_settings = get_option("donation_can_general");
    if (!$general_settings["debug_mode"]) {
        if ($goal_id != null) {
            $query .= " AND ";
        } else {
            $query .= " WHERE ";
        }
        $query .= "sandbox = 0";
    }
    $query .= ";";

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
    $query = "SELECT amount from " . donation_can_get_table_name($wpdb);

    $general_settings = get_option("donation_can_general");
    if (!$general_settings["debug_mode"]) {
        if ($goal_id != null) {
            $query .= " AND ";
        } else {
            $query .= " WHERE ";
        }
        $query .= "sandbox = 0";
    }
    $query .= ";";

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

        $query = "SELECT cause_code, amount FROM " . donation_can_get_table_name($wpdb);

        $general_settings = get_option("donation_can_general");
        if (!$general_settings["debug_mode"]) {
            if ($goal_id != null) {
                $query .= " AND ";
            } else {
                $query .= " WHERE ";
            }
            $query .= "sandbox = 0";
        }

        $query .= ";";
        $donations = $wpdb->get_results($query);

        foreach ($donations as $donation) {
            $goals[$donation->cause_code]["collected"] += $donation->amount;
        }
    }

    return $goals;
}


function donation_can_get_donations($offset = 0, $limit = 0, $goal_id = null) {
    global $wpdb;

    $query = "SELECT * FROM " . donation_can_get_table_name($wpdb);
    if ($goal_id != null) {
        $query = "SELECT * FROM " . donation_can_get_table_name($wpdb) . " WHERE cause_code = \"" . $goal_id . "\"";
    }

    $general_settings = get_option("donation_can_general");
    if (!$general_settings["debug_mode"]) {
        if ($goal_id != null) {
            $query .= " AND ";
        } else {
            $query .= " WHERE ";
        }
        $query .= "sandbox = 0";
    }

    $query .= " ORDER BY time DESC";

    if ($limit  > 0) {
        $query .= " LIMIT $offset,$limit";
    }
    $query .= ";";

    $donations = $wpdb->get_results($query);

    return $donations;
}

function donation_can_currency_defaults($currency, $convert_to_symbols = true) {
        // Default to USD as that was the original currency
    if ($currency == "" || $currency == null) {
        $currency = "USD";
    }
    if ($convert_to_symbols) {
        if ($currency == "USD") {
            $currency = "$";
        }
        if ($currency == "EUR") {
            $currency = "€";
        }
    }

    return $currency;
}

function donation_can_get_current_currency($convert_to_symbols = true) {
    $general_settings = get_option("donation_can_general");
    return donation_can_currency_defaults($general_settings["currency"], $convert_to_symbols);
}


function donation_can_get_currency_for_goal($goal_data, $convert_to_symbols = true) {
    return donation_can_currency_defaults($goal_data["currency"], $convert_to_symbols);
}

function donation_can_has_multiple_currencies_in_use() {
    $goals = get_option("donation_can_causes");
    if ($goals == null) {
        return false;
    }

    // Check if there are multiple currencies
    $previous_currency = "";
    $multiple_currencies_found = false;
    foreach ($goals as $goal) {
        $goal_currency = donation_can_get_currency_for_goal($goal);

        if ($previous_currency != "" && $goal_currency != $previous_currency) {
            $multiple_currencies_found = true;
            break;
        }

        $previous_currency = $goal_currency;
    }
                    
    return $multiple_currencies_found;
}


function donation_can_get_donation_count($goal_id = null) {
    global $wpdb;

    $query = "SELECT count(*) FROM " . donation_can_get_table_name($wpdb);
    if ($goal_id != null) {
            $query = "SELECT count(*) FROM " . donation_can_get_table_name($wpdb) . " WHERE cause_code = \"" . $goal_id . "\"";
    }

    $general_settings = get_option("donation_can_general");
    if (!$general_settings["debug_mode"]) {
        if ($goal_id != null) {
            $query .= " AND ";
        } else {
            $query .= " WHERE ";
        }
        $query .= "sandbox = 0";
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

        // Get the current currency from general settings and save it as the currency of this goal
        $cause["currency"] = donation_can_get_current_currency(false);

	$cause["return_page"] = $return_page;
	$cause["cancelled_return_page"] = $cancelled_return_page;
	$cause["continue_button_text"] = $continue_button_text; 
	$cause["notify_email"] = $notify_email;
	$cause["donation_sums"] = $donation_sums;
	$cause["allow_freeform_donation_sum"] = $allow_freeform_donation_sum;

	return $cause;
}

function w2log($msg) {
    if (true) {
        $filename = dirname(__FILE__) . "/../log";

        $fd = fopen($filename, "a");
        $str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $msg;
        fwrite($fd, $str . "\n");
        fclose($fd);
    }
}

/**
 * Processes PayPal IPN notification (= new version of callback.php)
 */
function donation_can_proccess_paypal_ipn($wp) {
    global $wpdb;
    $general_settings = get_option("donation_can_general");

    w2log("IPN notification received");

    // Send a request back to PayPal to confirm the notification
    $req = 'cmd=_notify-validate';

    // TODO: if this doesn't work as is, let's add these parameters to wp_parameters or whatever)
    foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $req .= "&$key=$value";
    }

    $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

    // Use Sandbox version if debug mode is on
    $url = "www.paypal.com";
    if ($general_settings["debug_mode"]) {
        $url = "www.sandbox.paypal.com";
    }

    $fp = fsockopen ($url, 80, $errno, $errstr, 30);

    // Assign posted variables to data array for saving to database
    $data = array(
        "cause_code" => $_POST["item_number"],
        "payment_status" => $_POST['payment_status'],
        "amount" => $_POST['mc_gross'],
        "transaction_id" => $_POST['txn_id'],
        "payer_email" => $_POST['payer_email'],
        "payer_name" => $_POST['first_name'] . " " . $_POST['last_name'],
        "fee" => $_POST['mc_fee'],
        "time" => current_time('mysql')
    );

    if ($general_settings["debug_mode"]) {
        $data["sandbox"] = 1;
    }

    $types = array('%s', '%s', "%f", "%s", "%s", "%s", "%f", "%s");

    foreach ($data as $k => $v) {
        w2log("$k: $v");
    }

    if (!$fp) {
        w2log("Http error, can't connect to " + $url);
    } else {
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
            $res = fgets ($fp, 1024);
            if (strcmp ($res, "VERIFIED") == 0) {
                $table_name = donation_can_get_table_name($wpdb);
                w2log("Transaction verified --> saving donation to $table_name");

                $wpdb->insert($table_name, $data, $types);

                w2log("OK");

                // Try to notify via email
                $goals = get_option("donation_can_causes");
                $goal = $goals[$data["cause_code"]];

                $emails = split(",", $general_settings["notify_email"]);
                $goal_emails = split(",", $goal["notify_email"]);

                if (!empty($emails) || !empty($goal_emails)) {
                    $all_emails = array_merge($emails, $goal_emails);
                    //TODO tässä välissä voisi varmistella vielä, että kaikki on oikein formatoitu...
                    w2log("Sending email to: " . $all_emails);

                    $to = join(",", $all_emails);

                    $admin_email = get_option('admin_email');

                    $subject = '[Donation Can] New Donation to ' . $data["cause_code"];
                    $message = 'A new donation was made to your cause, "' . $goal["name"] . "\":\r\n\r\n"
                                      . $data["payer_name"] . ' (' . $data["payer_email"] . ') donated ' . donation_can_get_currency_for_goal($goal) . " " . $data["amount"] . ' (PayPal fee: ' . $data["fee"] . ') to "'. $goal["name"] . '" (' . $data["cause_code"] . ').'
                                      . "\r\n\r\nVisit the WordPress dashboard to see all donations to this goal: \r\n"
                                      . get_bloginfo("url") . "/wp-admin/admin.php?page=goals.php" . "\r\n\r\nThanks,\r\nDonation Can";
                    $headers = 'From: Donation Can <'.$admin_email.'>' . "\r\n" .
                        'Reply-To: Donation Can <'.$admin_email.'>' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

                    mail($to, $subject, $message, $headers);
                }
            } else if (strcmp ($res, "INVALID") == 0) {
                // TODO log more info on this into the db?
                w2log("Invalid");
            }
	}
	fclose ($fp);
    }
}

?>