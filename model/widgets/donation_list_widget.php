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

class DonationListWidget extends WP_Widget {
	function DonationListWidget() {
		parent::WP_Widget(false, $name = 'Latest Donations');	
	}

	function widget($args, $instance) {
            global $wp_rewrite;
            if (!$wp_rewrite->using_permalinks()) {
                require_donation_can_view('permalinks');
            } else {
                extract($args);

                $goal_id = esc_attr($instance["goal_id"]);
                $show_title = esc_attr($instance["show_title"]);
                $title = esc_attr($instance["title"]);

                $show_donor_name = esc_attr($instance["show_donor_name"]);
                $show_donation_sum = esc_attr($instance["show_donation_sum"]);

                $goals = get_option("donation_can_causes");
                $general_settings = get_option("donation_can_general");

                $num_donations = esc_attr($instance["num_donations"]);
                if ($num_donations == null || $num_donations == "") {
                        $num_donations = 5;
                }

                if ($goal_id == "__all__") {
                    if ($title == null || $title == "") {
                            $title = "Latest Donations";
                    }
                    $donations = donation_can_get_donations(0, $num_donations);
                } else {
                    $goal = $goals[$goal_id];
                    if ($title == null || $title == "") {
                            $title = "Latest Donations for " . $goal["name"];
                    }
                    $donations = donation_can_get_donations(0, $num_donations, $goal_id);
                }

                echo $before_widget;
                if ($show_title) {
                    echo $before_title . $title . $after_title;
                }

                $donation_strings = array();
                foreach ($donations as $donation) {
                    $donation_goal = $goals[$donation->cause_code];
                    $donation_currency = donation_can_get_currency_for_goal($donation_goal);

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
                            $donation_string = __("A donation was made to \"%CAUSE\"", "donation_can");
                        } else {
                            $donation_string = "";
                        }
                    }

                    if ($donation_string != "") {
                        $amount = $donation->amount;
                        if ($general_settings["subtract_paypal_fees"]) {
                            $amount -= $donation->fee;
                        }

                        $donation_string = str_replace("%NAME", $donation->payer_name, $donation_string);
                        $donation_string = str_replace("%SUM", $amount, $donation_string);
                        $donation_string = str_replace("%CURRENCY", $donation_currency, $donation_string);
                        $donation_string = str_replace("%CAUSE", $goals[$donation->cause_code]["name"], $donation_string);

                        $donation_strings[] = array("date" => $donation->time, "text" => $donation_string);
                    }
                }

                require_donation_can_view('widget_blocks/donation_list', array("donation_strings" => $donation_strings));

                echo $after_widget;
            }
        }

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) {
		$goal_id = esc_attr($instance["goal_id"]);
		$show_title = esc_attr($instance["show_title"]);
		$title = esc_attr($instance["title"]);
		$show_donor_name = esc_attr($instance["show_donor_name"]);
		$show_donation_sum = esc_attr($instance["show_donation_sum"]);
		
		$num_donations = esc_attr($instance["num_donations"]);
		if ($num_donations == null || $num_donations == "") {
			$num_donations = 5;
		}
		
		$goals = get_option("donation_can_causes");
		if ($goals == null) {
			$goals = array();
		}
	?>
		<p>
			<label for="<?php echo $this->get_field_id('goal_id'); ?>">
				<?php _e('Goal:'); ?> 
				<select class="widefat" id="<?php echo $this->get_field_id('goal_id'); ?>" 
					name="<?php echo $this->get_field_name('goal_id'); ?>">
					
					<option value="__all__" <?php if ("__all__" == $goal_id) { echo "selected"; }?>>All goals (summary)</option>
					
					<?php foreach ($goals as $goal) : ?>
						<option value="<?php echo $goal["id"];?>" <?php if ($goal["id"] == $goal_id) { echo "selected"; }?>><?php echo $goal["name"]; ?></option>
					<?php endforeach; ?>
				</select>
			</label>			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_title'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('show_title'); ?>" <?php if ($show_title) { echo "checked"; } ?> 
					name="<?php echo $this->get_field_name('show_title'); ?>"/> <?php _e('Display title'); ?> 
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title (leave empty for default):
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>" 
					name="<?php echo $this->get_field_name('title'); ?>"/>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_donor_name'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('show_donor_name'); ?>" <?php if ($show_donor_name) { echo "checked"; } ?> 
					name="<?php echo $this->get_field_name('show_donor_name'); ?>"/> <?php _e('Display names of donors'); ?> 
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_donation_sum'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('show_donation_sum'); ?>" <?php if ($show_donation_sum) { echo "checked"; } ?> 
					name="<?php echo $this->get_field_name('show_donation_sum'); ?>"/> <?php _e('Display donation sums'); ?> 
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('num_donations'); ?>">Number of donations to list:
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('num_donations'); ?>" value="<?php echo $num_donations; ?>" 
					name="<?php echo $this->get_field_name('num_donations'); ?>"/>
			</label>
		</p>

	<?php
	}
}
?>