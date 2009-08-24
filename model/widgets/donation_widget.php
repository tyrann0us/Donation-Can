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

/**
 * The widget for a single donation form.
 */
class DonationWidget extends WP_Widget {
	function DonationWidget() {
		parent::WP_Widget(false, $name = 'Donation Form');	
	}

	function widget($args, $instance) {
		extract($args);

		$goal_id = esc_attr($instance["goal_id"]);
		$show_progress = esc_attr($instance["show_progress"]);
		$show_description = esc_attr($instance["show_description"]);
		$show_donations = esc_attr($instance["show_donations"]);
				
		$general_settings = get_option("donation_can_general");
		
		$goals = get_option("donation_can_causes");
		$goal = $goals[$goal_id];
		
		$show_title = esc_attr($instance["show_title"]);
		$title = esc_attr($instance["title"]);
		if ($title == null || $title == "") {
			$title = $goal["name"];
		}
		
		$donation_sums = $general_settings["donation_sums"];
		if ($goal["donation_sums"] != null && count($goal["donation_sums"]) > 0) {
			$donation_sums = $goal["donation_sums"];
		}
		
		$raised_so_far = donation_can_get_total_raised_for_cause($goal_id);	
			
		echo $before_widget;
		require(__FILE__ . "/../../../view/donation_form_single.php");
 		echo $after_widget;
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
						
						<?php foreach ($goals as $goal) : ?>
							<option value="<?php echo $goal["id"];?>" <?php if ($goal["id"] == $goal_id) { echo "selected"; }?>><?php echo $goal["name"]; ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</p>
			<p>
				<a href="" class="button">Edit goal settings</a>
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
				<label for="<?php echo $this->get_field_id('show_progress'); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id('show_progress'); ?>" <?php if ($show_progress) { echo "checked"; } ?> 
						name="<?php echo $this->get_field_name('show_progress'); ?>"/> <?php _e('Display progress'); ?> 
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('show_description'); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id('show_description'); ?>" <?php if ($show_description) { echo "checked"; } ?> 
						name="<?php echo $this->get_field_name('show_description'); ?>" /> <?php _e('Display description'); ?>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('show_donations'); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id('show_donations'); ?>" <?php if ($show_donations) { echo "checked"; } ?>
						name="<?php echo $this->get_field_name('show_donations'); ?>" /> <?php _e('Display latest donations'); ?>
				</label>
			</p>			
		<?php
	}
}
?>