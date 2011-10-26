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

function donation_can_get_table_name($wpdb) {
    $table_name = $wpdb->prefix . "donation_can_paypal_donations";
    return $table_name;
}

function donation_can_get_current_db_version() {
    return "6.0";
}

function donation_can_get_current_create_table_row() {
    global $wpdb;

    // Create the column structure separately in order
    // to give sub plugins a chance to make their own 
    // changes
    $columns = array(
        "id" => "mediumint(9) NOT NULL AUTO_INCREMENT",
        "item_number" => "VARCHAR(128) NOT NULL",
        "transaction_id" => "VARCHAR(255) NOT NULL",
        "payment_status" => "VARCHAR(255) NOT NULL",
        "time timestamp" => "NOT NULL",
        "payer_email" => "VARCHAR(128) NOT NULL",
        "payer_name" => "VARCHAR(255) NOT NULL",
        "anonymous" => "TINYINT(1) DEFAULT '0' NOT NULL",
        "cause_code" => "VARCHAR(64) NOT NULL",
        "amount" => "DECIMAL(10,2) DEFAULT '0.00' NOT NULL",
        "fee" => "DECIMAL(10,2) DEFAULT '0.00' NOT NULL",
        "note" => "TEXT",
        "sandbox" => "TINYINT(1) DEFAULT '0' NOT NULL",
        "offline" => "TINYINT(1) DEFAULT '0' NOT NULL",
        "deleted" => "TINYINT(1) DEFAULT '0' NOT NULL",
    );
    
    $columns = apply_filters('donation_can_db_structure', $columns);
    
    $table_name = donation_can_get_table_name($wpdb);
    $query = "CREATE TABLE " . $table_name . " ( ";
    
    foreach ($columns as $name => $definition) {
        $query .= $name . " " . $definition . ",\n";
    }
    
    $query .= "UNIQUE KEY id (id));";
    
    return $query;
}

/**
 * Run as the plugin gets activated.
 */
function donation_can_install() {
    donation_can_db_upgrade();
}

function donation_can_db_upgrade() {
    global $wpdb;
    
    $db_version = get_option("donation_can_db_version", "0.0");
    $update_needed = ($db_version != donation_can_get_current_db_version());
    $update_needed = apply_filters("donation_can_db_update_needed", $update_needed);
    
    if ($update_needed) {
        $query = donation_can_get_current_create_table_row();

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($query);
        
        update_option("donation_can_db_version", donation_can_get_current_db_version());
        do_action("donation_can_db_update_finished");
    }
}

function donation_can_get_general_settings() {
    return get_option("donation_can_general");
}

function donation_can_get_default_email_template() {
    $message = "A new donation was made to your cause, \"#CAUSE_NAME#\":\n\n"
                 . "#USER_NAME# (#USER_EMAIL#) donated #CURRENCY# #AMOUNT# (PayPal fee: #FEE#) to #CAUSE_NAME# #CAUSE_CODE#."
                 . "\n\nVisit the WordPress dashboard to see all donations to this goal:\n"
                 . "#DONATIONS_URL#\n\nThanks,\nDonation Can";

    return $message;
}

function donation_can_append_error($error_object, $error_code, $message) {
    if ($error_object == null) {
        $error_object = new WP_Error();
    }

    $error_object->add($error_code, $message);

    return $error_object;
}

function donation_can_get_widget_styles() {
    $widget_styles = get_option("donation_can_widget_styles");
    $widget_styles_version = get_option("donation_can_widget_styles_version", "0.0");

    if ($widget_styles == null || $widget_styles_version != "1.9") {
        if ($widget_styles == null) {
            $widget_styles = array();
        }

        // If nothing has been saved yet, return the default widget
        // and update it to options
        $widget_styles["default"] = array(
                "name" => __("Default", "donation_can"),
                "id" => "default",
                "locked" => true,
                "elements" => array(
                    "1" => array("type" => "title"),
                    "2" => array("type" => "description"),
                    "3" => array("type" => "progress", "text-format" => "<span class=\"currency\">%CURRENCY%</span><span class=\"raised\">%CURRENT%</span><span class=\"raised-label\">Raised</span><span class=\"goal\">%TARGET%</span><span class=\"goal-label\">Target</span>"),
                    "4" => array("type" => "cause-selection"),
                    "5" => array("type" => "donation-options"),
                    "6" => array("type" => "anonymous", "prompt" => __("Anonymous donation", "donation_can")),
                    "7" => array("type" => "submit"),
                    "8" => array("type" => "donation-list")
                ),
                "css" => array(
                    "" => "border: 1px #ddd solid; border-radius: 5px; -moz-border-radius: 5px; padding: 10px; background-color: #f5f5f5; color: #333;",
                    "h3" => "margin-top: 0px;",
                    ".description" => "margin: 10px 0px 0px 0px;",
                    ".donation_meter" => "background-color: #fafafa; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; margin: 10px -10px 10px -10px; padding: 10px;",
                    ".progress-meter" => "border: 0px; height: 10px;",
                    ".progress-meter .past-goal" => "background-color: #ddee00;",
                    ".progress-container" => "background-color: #ddd; height: 10px; border-radius: 4px; -moz-border-radius: 4px;",
                    ".progress-bar" => "background-color: #87C442; height: 10px; border-radius: 4px; -moz-border-radius: 4px;",
                    ".progress-text" => "position: relative; margin-top: 10px; font-size: 8pt; color: #444; height: 30px;",
                    ".currency" => "position: absolute; display: block; left: 0px; top: 0px;",
                    ".raised" => "position: absolute; top: 0px; left: 10px; font-weight: bold; display: block;",
                    ".raised-label" => "position: absolute; top: 15px; left: 0px; text-transform: uppercase; color: #777; display: block;",
                    ".goal" => "position: absolute; top: 0px; right: 0px; font-weight: bold; display: block;",
                    ".goal-label" => "position: absolute; top: 15px; right: 0px; text-transform: uppercase; color: #777; display: block;",
                    ".donation-options select" => "width: 100%;",
                    ".submit-donation" => "width: 100%;",
                    ".submit-donation input" => "margin: 10px auto 0px auto; width: 147px; display: block;",
                    ".backlink" => "text-align: center; margin-top: 15px;",
                    ".donations-list-container" => "margin: 10px -10px 0px -10px; padding: 10px; border-top: 1px solid #ddd;",
                    ".donations-list" => "margin: 0px; padding: 0px; font-size: 10pt; list-style: none;",
                    ".donations-list li" => "list-style: none; background: transparent; padding: 0px !important; margin: 5px 0px 5px 0px !important; font-size: 9pt;",
                    ".donation-date" => "color: #888; font-size: 8pt; display: block;",
                    ".donation-can-cause-selection select" => "width: 100%;"
                )
            );
        
        $widget_styles["default_2"] = array(
                "name" => __("Default Vertical", "donation_can"),
                "id" => "default_2",
                "locked" => true,
                "elements" => array(
                    "1" => array("type" => "progress", "direction" => "vertical", "text-format" => "<span class=\"percentage\">%PERCENTAGE% %</span> <span class=\"of-label\">of</span> <span class=\"currency\">%CURRENCY%</span><span class=\"goal\">%TARGET%</span>"),
                    "2" => array("type" => "title"),
                    "3" => array("type" => "description"),
                    "4" => array("type" => "donation-options", "list-format" => "buttons"),
                    "5" => array("type" => "anonymous", "prompt" => __("Anonymous donation", "donation_can")),
                    "6" => array("type" => "donation-list")
                ),
                "css" => array(
                    "" => "text-align: left; border: 1px solid #ccc; border-radius: 5px; -moz-border-radius: 5px; padding: 0px 10px 10px 0px; background-color: #f5f5f5; font-family: Verdana; font-size: 8pt; color: #333;",
                    "h3" => "margin: 10px auto 10px auto; text-align: left; font-family: Arial;",
                    ".description" => "text-align: left; margin: 10px 0px 0px 0px;",
                    ".donation-form" => "overflow: auto;",
                    ".donation_meter" => "width: 50px; float: left; margin: 0px 10px 0px 0px; text-align: center; background-color: #fff; border-top-left-radius: 5px; border-bottom-right-radius: 5px; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;",
                    ".progress-meter" => "border: 0px; height: 200px; width: 20px; margin: auto;",
                    ".progress-container" => "background-color: #eee; border: 0px; height: 200px; width: 20px; border-radius: 4px; -moz-border-radius: 4px; position: relative;",
                    ".progress-bar" => "background-color: #87C442; position: absolute; bottom: 0px; left: 0px; width: 20px; border-radius: 4px; -moz-border-radius: 4px;",
                    ".donation-options" => "margin: 10px 0px 10px 0px;",
                    ".donation-callout" => "display: none;",
                    ".donation-button-list" => "width: auto;",
                    ".button" => "display: block; padding: 5px; background-color: #e5e5e5; margin: 8px 0px 7px 0px; border: 0px; text-align: left; cursor: pointer;",
                    ".backlink" => "text-align: center; margin-top: 15px;",
                    ".progress-text" => "margin-top: 5px; font-size: 8pt;",
                    ".raised-label" => "display: none;",
                    ".percentage" => "display: block; text-align: center; font-weight: bold; color: #888;",
                    ".goal-label" => "display: none;",
                    ".of-label" => "display: block; text-align: center; color: #999; font-size: 8pt;",
                    ".currency" => "color: #999; font-size: 8pt;",
                    ".goal" => "color: #999; text-align: center; font-size: 8pt;",
                    ".donations-list-container" => "overflow: auto; clear: left; margin: 0px; padding: 0px;",
                    ".donations-list-inner" => "margin: 10px 0px 0px 0px; padding: 10px;",
                    ".donations-list" => "font-size: 10pt; list-style: none;",
                    ".donations-list li" => "list-style: none; background: transparent; padding: 0px !important; margin: 5px 0px 5px 0px !important; font-size: 9pt;",
                    ".donation-date" => "color: #888; font-size: 8pt; display: block;"
                )            
            );

        update_option("donation_can_widget_styles", $widget_styles);
        update_option("donation_can_widget_styles_version", "1.9");
    }

    return $widget_styles;
}

function donation_can_get_widget_style_by_id($style_id) {
    $styles = donation_can_get_widget_styles();

    if (isset($styles[$style_id])) {
        return $styles[$style_id];
    }

    // Default to default if the requested style is not found
    return $styles["default"];
}

function donation_can_save_widget_style($style_id, $style_definition) {
    $styles = donation_can_get_widget_styles();

    if ($styles[$style_id]["locked"]) {
        render_user_notification("Cannot update style " . $style_id);
        return false;
    }

    $styles[$style_id] = $style_definition;
    update_option("donation_can_widget_styles", $styles);

    return true;
}

function donation_can_clone_widget_style($id, $new_name) {
    $styles = donation_can_get_widget_styles();

    if (!isset($styles[$id])) {
        render_user_notification("No style found with id " . $id);
        return false;
    }

    $style = $styles[$id];

    $new_id = donation_can_create_cause_id_from_name($new_name);

    $style["name"] = $new_name;
    $style["id"] = $new_id;
    $style["locked"] = false;

    $styles[$new_id] = $style;
    update_option("donation_can_widget_styles", $styles);

    return true;
}

function donation_can_delete_widget_style($style_id) {
    $styles = donation_can_get_widget_styles();

    if ($styles[$style_id]["locked"]) {
        render_user_notification("Cannot delete style " . $style_id);
        return false;
    }

    unset($styles[$style_id]);
    update_option("donation_can_widget_styles", $styles);

    return true;
}

function donation_can_get_total_raised_for_cause($cause_id, $include_before_reset = false,
        $start_time = 0, $end_time = 0) {
    $donations = donation_can_get_donations(0, 0, $cause_id, $include_before_reset,
            $start_time, $end_time);

    $general_settings = donation_can_get_general_settings();

    $total = 0;
    if ($donations != null && is_array($donations)) {
        foreach ($donations as $donation) {
            $amount = $donation->amount;
            if ($general_settings["subtract_paypal_fees"]) {
                $amount -= $donation->fee;
            }

            $total += $amount;
        }
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

function donation_can_get_total_raised_for_all_causes($include_before_reset = false) {
    $goals = donation_can_get_goals(true, $include_before_reset);

    $total = 0;
    if ($goals != null && is_array($goals)) {
        foreach ($goals as $goal) {
            $total += $goal["collected"];
        }
    }

    return $total;
}

function donation_can_get_goal($goal_id, $include_raised_data = false, $include_before_reset = false) {
    $goals = donation_can_get_goals(false);
    if ($goals != null && $goal_id != null) {
        if (isset($goals[$goal_id])) {
            $goal = $goals[$goal_id];
            if ($include_raised_data) {
                $goal["collected"] = donation_can_get_total_raised_for_cause($goal_id, $include_before_reset);
            }
            return $goal;
        }
    }
    return null;
}

// if include_raised_data, adds the raised money as a cell in the array
function donation_can_get_goals($include_raised_data = false, $include_before_reset = false,
        $start_time = 0, $end_time = 0) {
    $goals = get_option("donation_can_causes");
    if ($goals == null) {
        $goals = array();
    }

    if ($include_raised_data) {
        $general_settings = donation_can_get_general_settings();
        foreach ($goals as $goal) {
            // Retrieve each goal separately so that we can handle the reset properly
            $goal["collected"] = donation_can_get_total_raised_for_cause($goal["id"], $include_before_reset, $start_time, $end_time);
            $goals[$goal["id"]] = $goal;
        }
    }

    return $goals;
}

function donation_can_reset_goal($goal_id) {
    $goals = donation_can_get_goals(false);
    if ($goals != null && $goal_id != null && isset($goals[$goal_id])) {
        $goal = $goals[$goal_id];

        // Set the current time stamp as reset time
        $goal["reset_after_date"] = time();

        $goals[$goal_id] = $goal;
        update_option("donation_can_causes", $goals);
    }
}

function donation_can_goal_has_been_reset($goal_id) {
    $goal = donation_can_get_goal($goal_id);
    if (!$goal) {
        return false;
    }

    // Support both old and new method, at least for a few versions (since 1.5.6)
    return ((isset($goal["reset_after_id"]) && $goal["reset_after_id"] > 0) 
            || (isset($goal["reset_after_date"]) && $goal["reset_after_date"] > 0));
}

function donation_can_delete_donation($id) {
    global $wpdb;
    $query = "UPDATE " . donation_can_get_table_name($wpdb) . " SET deleted=1 WHERE id = '$id';";
    $wpdb->query($query);    
}

function donation_can_get_donations($offset = 0, $limit = 0,
        $goal_id = null, $include_donations_before_reset = false,
        $start_time = 0, $end_time = 0) {
    global $wpdb;

    $query = "SELECT * FROM " . donation_can_get_table_name($wpdb) . " WHERE deleted = 0";

    // For now, we simply exclude the started donations from every request.
    // In future versions, there will be a nice way to view started donations
    // as well in the UI...
    $query .= " AND payment_status <> \"dc_started\" ";

    if ($goal_id != null) {
        $query .= " AND cause_code = \"" . $goal_id . "\"";

        // IMPORTANT: NEVER ASK FOR "COLLECTED" HERE... IT WILL CAUSE A FOREVER LOOP.
        $goal = donation_can_get_goal($goal_id);
        if (!$include_donations_before_reset) {        
            // New reset method
            if (isset($goal["reset_after_date"])) {
                $reset_after_time = $goal["reset_after_date"];
                $query .= " AND time > \"" . date("Y-m-d H:i", $reset_after_time) . "\"";
            } 
            // Old reset method (before 1.5.6), kept for backwards compatibility
            else if (isset($goal["reset_after_id"])) {
                $query .= " AND id > \"" . $goal["reset_after_id"] . "\"";
            }
        }
    }
    
    $general_settings = donation_can_get_general_settings();
    if (!$general_settings["debug_mode"]) {
        $query .= " AND sandbox = 0";
    }

    // Only accept donations from existing causes
    $goals = get_option("donation_can_causes");
    if (!$goals) {
        $goals = array();
    }
    
    $goal_list = array();
    foreach ($goals as $goal) {
        $goal_list[] = '"' . $goal["id"] . '"';
    }

    $goal_list_string = implode(",", $goal_list);

    $query .= " AND cause_code IN (" . $goal_list_string . ")";

    // Limit by time of donation
    if ($start_time > 0) {
        $query .= " AND time >= \"" . date("Y-m-d H:i", $start_time) . "\"";
    }

    if ($end_time > 0) {
        $query .= " AND time <= \"" . date("Y-m-d H:i", $end_time) . "\"";
    }

    $query .= " ORDER BY time DESC";

    if ($limit  > 0) {
        $query .= " LIMIT $offset,$limit";
    }
    $query .= ";";
    
    return $wpdb->get_results($query);
}

function donation_can_currency_defaults($currency, $convert_to_symbols = true) {
    // Default to USD as that was the original currency
    if ($currency == "" || $currency == null) {
        $currency = "USD";
    }
    if ($convert_to_symbols) {
        if ($currency == "USD" || $currency == "CAD") {
            $currency = "$";
        }
        if ($currency == "EUR") {
            $currency = "&euro;";
        }
        if ($currency == "GBP") {
            $currency = "&pound;";
        }
        if ($currency == "JPY") {
            $currency = "&yen;";
        }
    }

    return $currency;
}

function donation_can_get_current_currency($convert_to_symbols = true) {
    $general_settings = donation_can_get_general_settings();
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

function donation_can_get_paypal_email() {
    $general_settings = donation_can_get_general_settings();
    if ($general_settings["debug_mode"]) {
        return $general_settings["paypal_sandbox_email"];
    }
    
    return $general_settings["paypal_email"];
}

function donation_can_get_donation_count($goal_id = null) {
    $donations = donation_can_get_donations(0, 0, $goal_id);
    return count($donations);
}

/**
 * Create id from name, by stripping away special characters and
 * replacing spaces with dashes (only one dash even if there are more spaces)
 *
 * @param String $name  The name of the cause
 * @return String       The created cause id
 */
function donation_can_create_cause_id_from_name($name) {
    $id = strtolower($name);
    $id = preg_replace('/[^a-z0-9 ]/', '', $id);
    $id = str_replace(" ", "-", $id);
    $id = preg_replace('/[-]+/', '-', $id);

    return $id;
}

/**
 * Creates a cause from submitted POST parameters.
 * 
 * @param Array $post
 * @param String $id
 * 
 * @return Array The newly created cause object or error object
 */
function donation_can_create_cause($post, $unique_id, $id = -1, $check_errors = true) {
    $error = null;
    
    $name = stripslashes(esc_attr($post["name"]));
    if (!$name || strlen($name) < 1) {
        echo "ERROR: name missing!";
        $error = donation_can_append_error($error, 'name-missing', __("No name specified for cause", "donation_can"));
    }

    if ($id == -1) {
        $id = esc_attr($post["id"]);

        if ($id == null || $id == "") {
            $id = donation_can_create_cause_id_from_name($name);
        }

        // If the id is already in use, append a rotating number
        if ($unique_id == true) {
            $causes = donation_can_get_goals();
            $rotating_number = 1;
            $id_body = $id . "-";
            while (isset($causes[$id])) {
                $id = $id_body . $rotating_number;
                $rotating_number++;
            }
        }
    }
    
    $description = stripslashes(esc_attr($post["description"]));
    $currency = esc_attr($post["currency"]); // TODO: should we validate the input so that paypal won't fail?

    $donation_goal = esc_attr($post["donation_goal"]);
    if ($donation_goal) {
        // Remove all non-numerical characters
        $donation_goal = preg_replace('/[^0-9]+/', "", $donation_goal);
    }

    $return_page = esc_attr($post["return_page"]);
    $cancelled_return_page = esc_attr($post["cancelled_return_page"]);
    $continue_button_text = stripslashes(esc_attr($post["continue_button_text"]));
    
    $notify_email = esc_attr($post["notify_email"]);
    if ($notify_email) {
        $email_addresses = split(',', $notify_email);
        foreach ($email_addresses as $email) {
            if (!is_email(trim($email))) {
                $error = donation_can_append_error($error, "notify-email", sprintf(__("Malformed email address %s", "donation_can"), trim($email)));
            }
        }
    }

    $allow_freeform_donation_sum = esc_attr($post["allow_freeform_donation_sum"]);

    $donation_sum_num = esc_attr($post["donation_sum_num"]);
    $donation_sums = array();
    
    for ($i = 0; $i < $donation_sum_num; $i++) {
        $sum_value = esc_attr($post["donation_sum_" . $i]);
        if ($sum_value != null && $sum_value != "") {
            $donation_sums[] = $sum_value;
        }
    }

    $cause = array();
    $cause["id"] = $id;
    $cause["name"] = $name;
    $cause["description"] = $description;
    $cause["donation_goal"] = $donation_goal;

    $cause["currency"] = $currency;

    $cause["return_page"] = $return_page;
    $cause["cancelled_return_page"] = $cancelled_return_page;
    $cause["continue_button_text"] = $continue_button_text;
    $cause["notify_email"] = $notify_email;
    $cause["donation_sums"] = $donation_sums;
    $cause["allow_freeform_donation_sum"] = $allow_freeform_donation_sum;

    if ($check_errors && $error != null) {
        return $error;
    }

    return $cause;
}

function donation_can_create_item_number($cause_id) {
    return $cause_id . "-" . time();
}

function donation_can_get_style_element_from_data($element) {
    if (!is_array($element)) {
        return null;
    }

    // Let sub plugins add their own style elements
    $element_object = apply_filters("donation_can_get_style_element", $element);
    if ($element_object && is_object($element_object)) {
        return $element_object;
    }

    switch ($element["type"]) {
        case "title":
            return new DonationCanWidgetTitleElement($element);

        case "description":
            return new DonationCanWidgetDescriptionElement($element);

        case "progress":
            return new DonationCanWidgetProgressElement($element);

        case "donation-options":
            return new DonationCanWidgetDonationOptionsElement($element);

        case "submit":
            return new DonationCanWidgetSubmitElement($element);

        case "donation-list":
            return new DonationCanWidgetDonationListElement($element);

        case "text":
            return new DonationCanWidgetTextElement($element);

        case "anonymous":
            return new DonationCanWidgetAnonymousElement($element);

        case "cause-selection":
            return new DonationCanWidgetCauseSelectionElement($element);
        
        default:
            break;
    }

    return null;
}


function w2log($msg) {
    $general_settings = donation_can_get_general_settings();
    if ($general_settings["enable_logging"] == true) {
        // TODO: add instructions for turning logging on
        $filename = dirname(__FILE__) . "/../log";

        if (!is_writable($filename)) {
            die("Can't write to Donation Can log file. Disable logging from settings or make the log file (plugin directory/log) writable to continue.");
        }

        $fd = fopen($filename, "a");
        $str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $msg;
        fwrite($fd, $str . "\n");
        fclose($fd);
    }
}

function donation_can_insert_donation($item_number, $cause_code, $status, $amount, $anonymous,
        $time = "", $fee = 0, $payer_email = "", $payer_name = "", $transaction_id = "", $offline = 0) {
    global $wpdb;

    if ($time == "") {
        $time = current_time('mysql');
    }

    // Save the request to database
    $data = array(
        "item_number" => $item_number,
        "cause_code" => $cause_code,
        "payment_status" => $status,
        "amount" => $amount,
        "transaction_id" => $transaction_id,
        "payer_email" => $payer_email,
        "payer_name" => stripslashes($payer_name),
        "fee" => $fee,
        "anonymous" => $anonymous,
        "time" => $time,
        "offline" => $offline
    );

    if ($general_settings["debug_mode"]) {
        $data["sandbox"] = 1;
    }

    // Let sub plugins add their own fields if necessary
    $data = apply_filters("donation_can_transaction_data", $data);

    $types = array('%s', '%s', '%s', "%f", "%s", "%s", "%s", "%f", "%s");
    $types = apply_filters("donation_can_transaction_types", $types);

    $table_name = donation_can_get_table_name($wpdb);

    $wpdb->insert($table_name, $data, $types);

    return $data;
}

function donation_can_process_start_donation($wp) {
    global $wpdb;

    // Extract parameters from $_POST
    $cause_id = $_POST["cause"];
    $cause = donation_can_get_goal($cause_id);
    $amount = $_POST["amount"];
    
    if (isset($_POST["anonymous"])) {
        $anonymous = (esc_attr($_POST["anonymous"]) == "checked");
    }

    if ($cause == null) {
        w2log("Error: donation_can_process_start_donation called without cause");
        die("No cause selected");
    }

    // Right now, we only support PayPal, but this method can be extended for
    // different payment providers if needed.

    $general_settings = donation_can_get_general_settings();

    // Generate the URL to redirect the payment to
    $action_url = "https://www.paypal.com/cgi-bin/webscr";
    if ($general_settings["debug_mode"]) {
        $action_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
    }

    global $wp_rewrite;
    $notify_url = "donation_can_ipn/paypal/";
    if ($wp_rewrite->using_index_permalinks()) {
        $notify_url = "index.php/" . $notify_url;
    }
    $notify_url = get_bloginfo("url") . "/" . $notify_url;

    // Generate item number unique to this donation so we can use it to track
    // the rest of the process
    $item_number = donation_can_create_item_number($cause_id);

    // Pick the right paypal email
    $paypal_email = donation_can_get_paypal_email();

    // Generate parameters to post
    $paypal_args = array(
        "business" => $paypal_email,
        "item_name" => apply_filters('donation_can_item_name', $cause['name']),
        "item_number" => $item_number,
        "cmd" => "_donations",
        "notify_url" => $notify_url,
        "currency_code" => donation_can_get_currency_for_goal($cause, false),
        "no_shipping" => $general_settings["require_shipping"],
        "no_note" => ($general_settings["ask_for_note"] == "0") ? "1" : "0",
        "amount" => $amount,
        "charset" => "utf-8",

        // Customization
        "cpp_payflow_color" => $general_settings["bg_on_paypal_page"],
        "cpp_headerback_color" => $general_settings["header_bg_on_paypal_page"],
        "cpp_headerborder_color" => $general_settings["header_border_on_paypal_page"],
        "cn" => $general_settings["note_field_label"]
    );

    // Custom logo (add if set)
    if ($general_settings["logo_on_paypal_page"] != "") {
        $paypal_args["image_url"] = $general_settings["logo_on_paypal_page"];
    }

    if ($general_settings["header_on_paypal_page"] != "") {
        $paypal_args["cpp_header_image"] = $general_settings["header_on_paypal_page"];
    }

    // Return pages (add if set)
    $return_page = $general_settings["return_page"];
    if ($cause["return_page"] != "" && $cause["return_page"] != "-1") {
        $return_page = $cause["return_page"];
    }

    $continue_button_text = $general_settings["continue_button_text"];
    if ($cause["continue_button_text"] != "" && $cause["continue_button_text"] != "-1") {
        $continue_button_text = $cause["continue_button_text"];
    }
            
    if ($return_page != "" && $return_page != "-1") {
        $return_page_url = get_permalink($return_page);

        $paypal_args["cbt"] = $continue_button_text;
        $paypal_args["return"] = $return_page_url;
    }

    $cancel_return_page = $general_settings["cancel_return_page"];
    if ($cause["cancel_return_page"] != "" && $cause["cancel_return_page"] != "-1") {
        $cancel_return_page = $cause["cancel_return_page"];
    }
                
    if ($cancel_return_page != "" && $cancel_return_page != "-1") {
        $cancel_return_page_url = get_permalink($cancel_return_page);
        
        $paypal_args["cancel_return"] = $cancel_return_page_url;
    }

    w2log("Donation started to " . $cause_id . " - Item Number: " . $item_number);

    donation_can_insert_donation($item_number, $cause_id, 'dc_started', $amount, $anonymous);

    w2log("Inserted to database. Redirecting to payment provider.");

    // Output the PayPal form and submit
    // TODO: move to a view?
    echo "<html><body onload=\"document.getElementById('paypal_form').submit();\">";

    echo "<form id=\"paypal_form\" action=\"" . $action_url . "\" method=\"POST\">";

    foreach ($paypal_args as $key => $value) {
        echo "<input type=\"hidden\" name=\"" . $key . "\" value=\"" . $value . "\"/>";
    }

    echo "</form>";
    echo "</body></html>";

    // Skip rendering the rest of the blog to save some time
    die();
}


/**
 * Processes PayPal IPN notification (= new version of callback.php)
 */
function donation_can_process_paypal_ipn($wp) {
    global $wpdb;
    $general_settings = donation_can_get_general_settings();

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
    $url = "ssl://www.paypal.com";
    if ($general_settings["debug_mode"]) {
        $url = "ssl://www.sandbox.paypal.com";
    }

    $fp = fsockopen ($url, 443, $errno, $errstr, 30);

    // Assign posted variables to data array for saving to database
    $payer_name = $_POST['first_name'] . " " . $_POST['last_name'];
    $payer_name = stripslashes($payer_name);

    $data = array(
        "item_number" => $_POST["item_number"],
        "cause_code" => "",
        "payment_status" => $_POST['payment_status'],
        "amount" => $_POST['mc_gross'],
        "transaction_id" => $_POST['txn_id'],
        "payer_email" => $_POST['payer_email'],
        "payer_name" => $payer_name,
        "fee" => $_POST['mc_fee'],
        "time" => current_time('mysql')
    );

    if ($general_settings["debug_mode"]) {
        $data["sandbox"] = 1;
    }

    $types = array('%s', '%s', '%s', "%f", "%s", "%s", "%s", "%f", "%s");

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
                w2log("Transaction verified --> ");
                $table_name = donation_can_get_table_name($wpdb);
                w2log("Saving donation to $table_name");

                // Check if the transaction has already been saved
                // and update if payment_status has changed
                $saved_transaction = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE item_number = %s", $data["item_number"]));

                if ($saved_transaction == null) {
                    w2log("Error, no transaction found with item_number " . $data["item_number"]);
                } else {                    
                    // TODO Depending on the original status, do different updates...
                    $data["cause_code"] = $saved_transaction->cause_code;

                    if ($data["payment_status"] == "Refunded") {
                        // Refunds send back the change in the donation amount
                        $change = intval($data["amount"]);
                        $fee_change = intval($data["fee"]);

                        $data["amount"] = $saved_transaction->amount + $change;
                        $data["fee"] = $saved_transaction->fee + $fee_change;
                    }

                    $wpdb->update($table_name,
                            $data,
                            array("item_number" => $data["item_number"]),
                            $types,
                            "%s");                
                }

                w2log("OK");

                // Try to notify via email
                $goals = get_option("donation_can_causes");
                $goal = $goals[$data["cause_code"]];

                $emails = split(",", $general_settings["notify_email"]);
                $goal_emails = split(",", $goal["notify_email"]);

                if (!empty($emails) || !empty($goal_emails)) {
                    $all_emails = array_merge($emails, $goal_emails);

                    //TODO tässä välissä voisi varmistella vielä, että kaikki on oikein formatoitu...
                    $to = join(",", $all_emails);
                    w2log("Sending email to: " . $to);

                    $message = $general_settings["email_template"];
                    if ($message == null || $message == "") {
                        // Default version
                        $message = donation_can_get_default_email_template();
                    }

                    if ($data["payment_status"] == "Completed") {
                        $subject = '[Donation Can] New Donation to ' . $goal["name"];
                        donation_can_send_email($to, $subject, $message, $general_settings, $goal, $data);
                    } else if ($data["payment_status"] == "Pending" || $data["payment_status"] == "Created") {
                        $subject = '[Donation Can] Pending Donation to ' . $goal["name"];
                        donation_can_send_email($to, $subject, $message, $general_settings, $goal, $data);
                    }
                }

                // Send a receipt to donor (only if completed)
                if ($data["payment_status"] == "Completed") {
                    if ($general_settings["send_receipt"]) {
                        donation_can_send_receipt($data, $goal);
                    }
                }
            } else if (strcmp ($res, "INVALID") == 0) {
                // TODO log more info on this into the db?
                w2log("Invalid");
            } else {
                //w2log("Unknown response: " . $res);
            }
	}
	fclose ($fp);
    }
}

function donation_can_send_receipt($donation_data, $cause) {
    if ($donation_data != null) {
        $general_settings = donation_can_get_general_settings();
        $email_template = $general_settings["receipt_template"];

        if ($general_settings["receipt_threshold"] > 0) {
            w2log("Receipt threshold for receipts set to " . $general_settings["receipt_threshold"]);
            if ($general_settings["receipt_threshold"] > intval($donation_data["amount"])) {
                w2log("Donation didn't exceed donation threshold. Not sending receipt.");
                return false;
            }
        }

        if ($email_template != null && $email_template != "") {
            $to = $donation_data["payer_name"] . " <" . $donation_data["payer_email"] . ">";

            $subject = $general_settings["receipt_subject"];
            $subject = donation_can_replace_attributes($subject, $donation_data, $cause);

            donation_can_send_email($to, $subject, $email_template, $general_settings, $cause, $donation_data);
        }
    }
}

function donation_can_replace_attributes($text, $data, $goal) {
    $text = str_replace('#CAUSE_NAME#', $goal["name"], $text);
    $text = str_replace('#USER_NAME#', $data["payer_name"], $text);
    $text = str_replace('#USER_EMAIL#', $data["payer_email"], $text);
    $text = str_replace('#CURRENCY#', donation_can_get_currency_for_goal($goal, false), $text);
    $text = str_replace('#AMOUNT#', $data["amount"], $text);
    $text = str_replace('#FEE#', $data["fee"], $text);
    $text = str_replace('#CAUSE_CODE#', $data["cause_code"], $text);
    $text = str_replace('#DONATIONS_URL#', admin_url("admin.php?page=donation_can_goals.php"), $text);

    $text = str_replace('#ITEM_NUMBER#', $data["item_number"], $text);
    $text = str_replace('#TRANSACTION_ID#', $data["transaction_id"], $text);

    $date = date("Y/m/d H:i", strtotime($data["time"]));
    $text = str_replace('#DONATION_TIME#', $date, $text);

    return $text;
}

function donation_can_send_email($to, $subject, $message, $general_settings, $goal, $data) {
    $message = donation_can_replace_attributes($message, $data, $goal);

    $from_email = $general_settings["email_from"];
    if (!$from_email) {
        $from_email = get_option('admin_email');
    }

    $from_name = $general_settings["email_from_name"];
    if (!$from_name) {
        $from_name = "Donation Can";
    }
   
    $headers = 'From: ' . $from_name . ' <'.$from_email . '>' . "\r\n" .
        'Reply-To: ' . $from_name . ' <' . $from_email . '>' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    
    if ($general_settings["use_html_emails"]) {
        $headers .= "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=UTF-8' . "\r\n";
    }
       
    w2log("Message: " . $message);
    mail($to, $subject, $message, $headers);
}

function donation_can_nicedate($date) {
    $date_string = mysql2date(__("Y/m/d g:i A", "donation_can"), $date);
    $time_string = mysql2date(__("g:i A", "donation_can"), $date);

    $date_data = date_parse($date_string);
    $now_data = getdate();

    if ($date_data["year"] == $now_data["year"]
            && $date_data["month"] == $now_data["mon"]) {
        $difference = $now_data["mday"] - $date_data["day"];

        if ($difference == 0) {
            return sprintf(__("Today at %s", "donation_can"), $time_string);
        } else if ($difference == 1) {
            return sprintf(__("Yesterday at %s", "donation_can"), $time_string);
        } else if ($difference < 7) {
            return sprintf(__("%d days ago at %s", "donation_can"),$difference, $time_string);
        }
    }

    return $date_string;

}

function donation_can_is_valid_date($date_string) {
    $x = strtotime($date_string);
    if ($x === false || $x == -1) {
        return false;
    }
    return true;
}


?>