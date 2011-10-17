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

function donation_can_add_goal_menu() {
    $causes = get_option("donation_can_causes");
    if ($causes == null) {
        $causes = array();
    }

    $currency = donation_can_get_current_currency(false);
    $currency_display = donation_can_get_current_currency(true);
        
    $pages = get_pages();

    // Get the default donation options from general settings
    $general_settings = donation_can_get_general_settings();
    $donation_sums = $general_settings["donation_sums"];

    $edit = false;

    // Add a new cause
    if ($_POST["add_cause"] == "Y") {
        $cause = donation_can_create_cause($_POST, true);

        if (is_wp_error($cause)) {
            // Print out errors
            ?>
                <div class="error">
                    <p><?php _e("Please fix the following errors to continue:", "donation_can");?></p>
                    <ul>
                    <?php
                        $errors = $cause->get_error_messages();

                        foreach ($errors as $error) {
                            echo "<li>$error</li>";
                        }
                    ?>
                    </ul>
                </div>
            <?php

            require_donation_can_view('edit_goal_page', array(
                "currency" => $currency,
                "currency_display" => $currency_display,
                "donation_sums" => $donation_sums,
                "id" => 0,
                "pages" => $pages,
                "edit" => $edit,
                "goal" => donation_can_create_cause($_POST, true, -1, false)
            ));

        } else {
            $cause["created_at"] = date("Y/m/d");

            global $current_user;
            get_currentuserinfo();
            $cause["author"] = $current_user->user_login;

            $causes[$cause["id"]] = $cause;
            update_option("donation_can_causes", $causes);
            render_user_notification(__("Added goal:", "donation_can") . " " . $cause["id"]);

            require_donation_can_view('goals_page', array("causes" => $causes, "last_added_id" => $cause["id"]));
        }
    } else {
        require_donation_can_view('edit_goal_page', array(
            "currency" => $currency,
            "currency_display" => $currency_display,
            "donation_sums" => $donation_sums,
            "id" => 0,
            "pages" => $pages,
            "edit" => $edit,
            "goal" => $cause
        ));
    }
}
?>