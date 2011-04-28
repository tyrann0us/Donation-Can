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

class DonationProgressWidget extends WP_Widget {
    function DonationProgressWidget() {
        parent::WP_Widget(false, $name = __('Fundraising Progress', "donation_can"));
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

            echo $before_widget;

            $multiple_currencies_found = donation_can_has_multiple_currencies_in_use();
            if ($multiple_currencies && $goal_id == "__all__") {
                // TODO: design a nice view for multiple currencies (maybe displaying
                // the totals in all currencies separately?
                // 
                // For now, let's not support this
                _e("You have causes in multiple currencies. In this version, Donation Can can't yet display their totals.", "donation_can");
            } else {
                if ($goal_id == "__all__") {
                    if ($title == null || $title == "") {
                        $title = __("All Donations", "donation_can");
                    }

                    $total_target = donation_can_get_total_target_for_all_causes();
                    $raised = donation_can_get_total_raised_for_all_causes();
                } else {
                    $goals = get_option("donation_can_causes");
                    $goal = $goals[$goal_id];
                    if ($title == null || $title == "") {
                            $title = $goal["name"];
                    }

                    $raised = donation_can_get_total_raised_for_cause($goal_id);
                    $total_target = $goal["donation_goal"];

                    $currency = donation_can_get_currency_for_goal($goal);
                }

                if ($show_title) {
                    echo $before_title . $title . $after_title;
                }
                require_donation_can_view('widget_blocks/progress_bar', array("currency" => $currency, "current" => $raised, "target" => $total_target));
            }

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

        $goals = donation_can_get_goals(false);

        $multiple_currencies_found = donation_can_has_multiple_currencies_in_use();
	?>
		<p>
			<label for="<?php echo $this->get_field_id('goal_id'); ?>">
				<?php _e('Goal:', "donation_can"); ?>
				<select class="widefat" id="<?php echo $this->get_field_id('goal_id'); ?>" 
					name="<?php echo $this->get_field_name('goal_id'); ?>">

                                        <?php if (!$multiple_currencies_found) : ?>
                                            <option value="__all__" <?php if ("__all__" == $goal_id) { echo "selected"; }?>><?php _e("All goals (summary)", "donation_can");?></option>
                                        <?php endif; ?>
					
					<?php foreach ($goals as $goal) : ?>
						<option value="<?php echo $goal["id"];?>" <?php if ($goal["id"] == $goal_id) { echo "selected"; }?>><?php echo $goal["name"]; ?></option>
					<?php endforeach; ?>
				</select>
			</label>			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_title'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('show_title'); ?>" <?php if ($show_title) { echo "checked"; } ?> 
					name="<?php echo $this->get_field_name('show_title'); ?>"/> <?php _e('Display title', 'donation_can'); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title (leave empty for default):", "donation_can");?></label>
                        <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>"
                               name="<?php echo $this->get_field_name('title'); ?>"/>
		</p>
	<?php
    }
}
?>