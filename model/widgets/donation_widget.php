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

/**
 * The widget for a donation form.
 */
class DonationWidget extends WP_Widget {
    function DonationWidget() {
        parent::WP_Widget(false, $name = 'Donation Form');
    }

    function widget($args, $instance) {
        echo $this->to_string($args, $instance);
    }

    function to_string($args, $instance) {
        $before_widget = "";
        $after_widget = "";
        
        extract($args);

        $goal_id = esc_attr($instance["goal_id"]);

        $show_progress = esc_attr($instance["show_progress"]);
        $show_description = esc_attr($instance["show_description"]);
        $show_donations = esc_attr($instance["show_donations"]);

        $general_settings = get_option("donation_can_general");

        $goals = get_option("donation_can_causes");

        if ($goal_id == "__all__") {
            $goal = array(
                "id" => "__all__",
                "name" => "Summary (All goals)",
                "donation_goal" => donation_can_get_total_target_for_all_causes()
            );
        } else {
            $goal = $goals[$goal_id];
        }

        if ($goal_id == null || ($goal == null && $goal_id != "__all__")) {
            return get_donation_can_view_as_string("donation_form_no_cause");
        }

        $show_back_link = !$general_settings["link_back"];

        $show_title = esc_attr($instance["show_title"]);
        $title = esc_attr($instance["title"]);
        if ($title == null || $title == "") {
            $title = $goal["name"];
        }

        // The looks of the widget
        $widget_style_id = esc_attr($instance["style_id"]);
        if ($widget_style_id == null || $widget_style_id == '') {
            $widget_style_id = "default";
        }
        $style = donation_can_get_widget_style_by_id($widget_style_id);

        $donation_sums = $general_settings["donation_sums"];
        if ($goal != null) {
            if ($goal["donation_sums"] != null && count($goal["donation_sums"]) > 0) {
                $donation_sums = $goal["donation_sums"];
            }
        }

        if ($goal_id == "__all__") {
            $raised_so_far = donation_can_get_total_raised_for_all_causes();

            // Use the currency from general settings... --> update currency when the user chooses a cause
            $currency = donation_can_get_current_currency();
        } else {
            $raised_so_far = donation_can_get_total_raised_for_cause($goal_id);
            $currency = donation_can_get_currency_for_goal($goal);
        }

        global $wp_rewrite;
        $action_url = "/donation_can_ipn/start_donation";
        if ($wp_rewrite->using_index_permalinks()) {
            $action_url = get_bloginfo('url') . "/index.php" . $action_url;
        } else {
            $action_url = get_bloginfo('url') . $action_url;
        }

        $donation_strings = array();
        if ($show_donations) {
            $num_donations = $instance["num_donations"];
            if (!$num_donations) {
                $num_donations = 5;
            }
            if ($goal_id == "__all__") {
                $donations = donation_can_get_donations(0, $num_donations);
            } else {
                $donations = donation_can_get_donations(0, $num_donations, $goal_id);
                $donation_currency = donation_can_get_currency_for_goal($goals[$goal_id]);
            }

            // TODO: configure!
            $show_donor_name = true;
            if (isset($instance["show_donor_name"])) {
                $show_donor_name = $instance["show_donor_name"];
            }
            $show_donation_sum = true;
            if (isset($instance["show_donation_sum"])) {
                $show_donation_sum = $instance["show_donation_sum"];
            }

            foreach ($donations as $donation) {
                if ($donation->cause_code != $goal_id) {
                    $donation_goal = $goals[$donation->cause_code];
                    $donation_currency = donation_can_get_currency_for_goal($donation_goal);
                }

                $donation_string = "";

                if (($show_donor_name && !$donation->anonymous) && $show_donation_sum) {
                    if ($goal_id == "__all__") {
                        $donation_string = __("%NAME gave %CURRENCY %SUM to \"%CAUSE\"", "donation_can");
                    } else {
                        $donation_string = __("%NAME donated %CURRENCY %SUM", "donation_can");
                    }
                } else if ($show_donor_name && !$donation->anonymous) {
                    if ($goal_id == "__all__") {
                        $donation_string = __("%NAME donated to \"%CAUSE\"", "donation_can");
                    } else {
                        $donation_string = __("%NAME made a donation", "donation_can");
                    }
                } else if ($show_donation_sum) {
                    if ($goal_id == "__all__") {
                        $donation_string = __("%CURRENCY %SUM was donated to \"%CAUSE\"", "donation_can");
                    } else {
                        $donation_string = __("%CURRENCY %SUM donated", "donation_can");
                    }
                } else {
                    if ($goal_id == "__all__") {
                        $donation_string = __("An anonymous donation was made to \"%CAUSE\"", "donation_can");
                    } else {
                        $donation_string = "";
                    }
                }

                if ($donation_string != "") {
                    $amount = $donation->amount;
                    if ($general_settings["subtract_paypal_fees"]) {
                        $amount -= $donation->fee;
                    }

                    $donation_string = str_replace("%NAME", stripslashes($donation->payer_name), $donation_string);
                    $donation_string = str_replace("%SUM", $amount, $donation_string);
                    $donation_string = str_replace("%CURRENCY", $donation_currency, $donation_string);
                    $donation_string = str_replace("%CAUSE", $goals[$donation->cause_code]["name"], $donation_string);

                    $donation_strings[] = array("date" => $donation->time, "text" => $donation_string);
                }
            }
        }


        $out = "";

        $out .= $before_widget;

        // Use output buffering to get the widget HTML into the string rather than straight on screen
        ob_start();

        if ($wp_rewrite->using_permalinks()) {
            require_donation_can_view('donation_form_single',
                    array(
                        "widget_options" => array(
                            "currency" => $currency,
                            "raised_so_far" => $raised_so_far,
                            "donation_sums" => $donation_sums,
                            "title" => $title,
                            "show_title" => $show_title,
                            "show_progress" => $show_progress,
                            "show_description" => $show_description,
                            "show_donations" => $show_donations,
                            "goal" => $goal,
                            "donation_strings" => $donation_strings,
                            "show_donation_list_title" => true
                        ),
                        "show_back_link" => $show_back_link,
                        "action_url" => $action_url,
                        "goal" => $goal,
                        "elements" => $style["elements"],
                        "widget_style_id" => $widget_style_id
                    ));
        } else {
            require_donation_can_view('permalinks');
        }

        $out .= ob_get_contents();
        ob_end_clean();

        $out .= $after_widget;

        return $out;
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form($instance) {
        $goal_id = esc_attr($instance["goal_id"]);
        $show_progress = esc_attr($instance["show_progress"]);
        $show_description = esc_attr($instance["show_description"]);
        $show_donations = esc_attr($instance["show_donations"]);
        $show_title = esc_attr($instance["show_title"]);
        $title = esc_attr($instance["title"]);
        $style_id = esc_attr($instance["style_id"]);

        if (isset($instance["num_donations"])) {
            $num_donations = intval(esc_attr($instance["num_donations"]));
        }

        $goals = get_option("donation_can_causes");
        if ($goals == null) {
            $goals = array();
        }

        $widget_styles = donation_can_get_widget_styles();
            
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('goal_id'); ?>"><?php _e('Goal:'); ?></label><br/>
                <select class="widefat" id="<?php echo $this->get_field_id('goal_id'); ?>"
                        name="<?php echo $this->get_field_name('goal_id'); ?>">

                    <?php if (function_exists("donation_competition_init_scripts")) : ?>
                        <option value="__all__" <?php if ($goal_id == "__all__") { echo "selected"; } ?>><?php _e("Summary (All goals)", "donation_can");?></option>
                    <?php endif; ?>
                    <?php foreach ($goals as $goal) : ?>
                        <option value="<?php echo $goal["id"];?>" <?php if ($goal["id"] == $goal_id) { echo "selected"; }?>><?php echo $goal["name"]; ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
                    <!--<p>
                            <a href="" class="button">Edit goal settings</a>
                    </p>-->

            <p>
                <label for="<?php echo $this->get_field_id('style_id'); ?>"><?php _e("Widget style:", "donation_can");?></label><br/>
                <select class="widefat" name="<?php echo $this->get_field_name('style_id');?>">
                    <?php foreach ($widget_styles as $style) : ?>
                        <option value="<?php echo $style["id"];?>" <?php if ($style["id"] == $style_id) { echo "selected"; }?>><?php echo $style["name"];?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <a href="<?php bloginfo('url');?>/wp-admin/admin.php?page=donation_can_widget_styles.php" class="button"><?php _e("Edit widget styles", "donation_can");?></a>
            </p>

                    <h3 style="margin: 25px 0px 10px 0px;"><?php _e("Customize widget:", "donation_can");?></h3>
                    
                    <p>
                            <label for="<?php echo $this->get_field_id('show_title'); ?>">
                                    <input type="checkbox" id="<?php echo $this->get_field_id('show_title'); ?>" <?php if ($show_title) { echo "checked"; } ?>
                                            name="<?php echo $this->get_field_name('show_title'); ?>"/> <?php _e('Display title', "donation_can"); ?>
                            </label>
                    </p>
                    <p>
                            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title (leave empty for default):", "donation_can"); ?>
                                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>"
                                            name="<?php echo $this->get_field_name('title'); ?>"/>
                            </label>
                    </p>
                    <p>
                            <label for="<?php echo $this->get_field_id('show_progress'); ?>">
                                    <input type="checkbox" id="<?php echo $this->get_field_id('show_progress'); ?>" <?php if ($show_progress) { echo "checked"; } ?>
                                            name="<?php echo $this->get_field_name('show_progress'); ?>"/> <?php _e('Display progress', "donation_can"); ?>
                            </label>
                    </p>
                    <p>
                            <label for="<?php echo $this->get_field_id('show_description'); ?>">
                                    <input type="checkbox" id="<?php echo $this->get_field_id('show_description'); ?>" <?php if ($show_description) { echo "checked"; } ?>
                                            name="<?php echo $this->get_field_name('show_description'); ?>" /> <?php _e('Display description', "donation_can"); ?>
                            </label>
                    </p>
                    <p>
                            <label for="<?php echo $this->get_field_id('show_donations'); ?>">
                                    <input type="checkbox" id="<?php echo $this->get_field_id('show_donations'); ?>" <?php if ($show_donations) { echo "checked"; } ?>
                                            name="<?php echo $this->get_field_name('show_donations'); ?>" /> <?php _e('Display latest donations', "donation_can"); ?>
                            </label>
                    </p>
                    <p>
                            <label for="<?php echo $this->get_field_id('num_donations'); ?>">Number of donations to display:</label>
                            <input class="widefat" type="text" name="<?php echo $this->get_field_name('num_donations'); ?>" id="<?php echo $this->get_field_id('num_donations'); ?>" value="<?php echo $num_donations; ?>"/>
                    </p>

            <?php
    }
}
?>