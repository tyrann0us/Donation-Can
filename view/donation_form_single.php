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
?>

<div class="donation-can_donation-widget">
	<?php if ($show_title) : ?>
		<?php echo $before_title; ?><?php echo $title;?><?php echo $after_title; ?>
	<?php endif; ?>
	
	<?php if ($show_description) : ?>
		<div class="donation-can_goal-description">
			<?php echo $goal["description"];?>
		</div>
	<?php endif; ?>
	
	<?php if ($show_progress) : ?>
		<?php 
			$target = $goal["donation_goal"];
			$current = $raised_so_far;
			require("progress_bar.php");
		?>
	<?php endif; ?>

	<?php if ($goal["donation_goal"] == "" || $raised_so_far < $goal["donation_goal"]) : ?>
		<div class="donation-can_donation-form">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="business" value="<?php echo $general_settings["paypal_email"]; ?>" />
				<input type="hidden" name="item_name" value="<?php echo $goal['name']; ?>"/>
				<input type="hidden" name="item_number" value="<?php echo $goal['id']; ?>"/>
				<input type="hidden" name="cmd" value=" _donations"/>
				<input type="hidden" name="notify_url" value="<?php bloginfo('url');?>/wp-content/plugins/donation_can/callback.php"/>
				<input type="hidden" name="currency_code" value="<?php echo $general_settings["currency_code"];?>" />

				<!-- A set of fields that are defined in the plugin settings -->			
				<input type="hidden" name="no_shipping" value="<?php echo $general_settings["require_shipping"];?>"/>
				<input type="hidden" name="no_note" value="<?php echo $general_settings["ask_for_note"];?>"/>
			
				<!-- A thank you page url -->
				<?php
					$return_page = $general_settings["return_page"];
					if ($goal["return_page"] != "" && $goal["return_page"] != "-1") {
						$return_page = $goal["return_page"];
					}
				
					$continue_button_text = $general_settings["continue_button_text"];
					if ($goal["continue_button_text"] != "" && $goal["continue_button_text"] != "-1") {
						$continue_button_text = $goal["continue_button_text"];
					}
				?>
				<?php if ($return_page != "" && $return_page != "-1") : ?>
					<?php $return_page_url = get_permalink($return_page); ?>
				
					<input type="hidden" name="cbt" value="<?php echo $continue_button_text; ?>"/>
					<input type="hidden" name="return" value="<?php echo $return_page_url; ?>"/>
				<?php endif; ?>

				<!-- A cancel page url -->
				<?php
					$cancel_return_page = $general_settings["cancel_return_page"];
					if ($goal["cancel_return_page"] != "" && $goal["cancel_return_page"] != "-1") {
						$cancel_return_page = $goal["cancel_return_page"];
					}
				?>
				<?php if ($cancel_return_page != "" && $cancel_return_page != "-1") : ?>
					<?php $cancel_return_page_url = get_permalink($cancel_return_page); ?>
					<input type="hidden" name="cancel_return" value="<?php echo $cancel_return_page_url;?>"/>
				<?php endif; ?>

				<!-- Custom logo to show on PayPal page -->
				<?php if ($general_settings["logo_on_paypal_page"] != "") : ?>
					<input type="hidden" name="cpp_header_image" value="<?php echo $general_settings["logo_on_paypal_page"];?>"/>
				<?php endif; ?>
			
				<?php if ($donation_sums != null && count($donation_sums) > 0) : ?>
					<p>
						Donate: 
						<select name="amount"> 
							<?php foreach ($donation_sums as $sum) : ?>
								<option value="<?php echo $sum;?>">$<?php echo $sum; ?></option>
							<?php endforeach; ?>
							<?php if ($goal["allow_freeform_donation_sum"]) : ?>
								<option value=""><?php _e("Other (enter amount on next page)", "donation_can");?></option>
							<?php endif; ?>
						</select>
					</p>
				<?php endif; ?>

				<input type="image" name="submit" border="0" src="http://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" alt="PayPal - The safer, easier way to pay online"/>
			</form>
		</div>
	<?php else : ?>
		<?php _e("The donation goal has been reached. Thank you for your support!", "donation_can");?>
	<?php endif; ?>
</div>