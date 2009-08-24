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

<script type="text/javascript" src="<?php echo bloginfo("url"); ?>/wp-content/plugins/donation-can/view/scripts.js"></script>

<div class="wrap">
	<h2>Donation Can Settings</h2>

	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="edit_settings" value="Y"/>

		<table class="form-table">
			<tr valign="top">
				<th scope="row" valign="center">PayPal Email:</th>
				<td><input type="text" class="regular-text" name="paypal_email" value="<?php echo $general_settings["paypal_email"];?>" size="40"/>
					<span class="description">Your PayPal account email. This is where the funds will be sent to.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center">Shipping:</th>
				<td>
					<input type="radio" name="require_shipping" value="0" <?php if ($general_settings["require_shipping"] == '0') { echo "checked"; }?>> Prompt for an address but do not require one<br/>
					<input type="radio" name="require_shipping" value="1" <?php if ($general_settings["require_shipping"] == '1') { echo "checked"; }?>> Do not prompt for an address<br/>
					<input type="radio" name="require_shipping" value="2" <?php if ($general_settings["require_shipping"] == '2') { echo "checked"; }?>> Prompt for an address and require one
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center">Prompt to include a note:</th>
				<td>
					<input type="radio" name="ask_for_note" value="0" <?php if ($general_settings["ask_for_note"] == '0') { echo "checked"; }?>> Provide a text box and prompt for the note<br/>
					<input type="radio" name="ask_for_note" value="1" <?php if ($general_settings["ask_for_note"] == '1') { echo "checked"; }?>> Hide the text box and the note
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center">Thank you page:</th>
				<td>
					<select name="return_page">
						<option value="-1" <?php if ("-1" == $general_settings["return_page"]) { echo "selected";}?>>-- Use PayPal Default --</option>
						<?php foreach ($pages as $page) : ?>
							<option value="<?php echo $page->ID;?>" <?php if ($page->ID == $general_settings["return_page"]) { echo "selected";}?>><?php echo $page->post_title;?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center">Text for continue button:</th>
				<td>
					<input type="text" class="regular-text" name="continue_button_text" value="<?php echo $general_settings["continue_button_text"];?>" size="40"/>
					<span class="description">Applies when thank you page URL is set to something else than "Use Paypal Default".</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center">Payment cancelled page:</th>
				<td>
					<select name="cancel_return_page">
						<option value="-1" <?php if ("-1" == $general_settings["cancel_return_page"]) { echo "selected";}?>>-- Use PayPal Default --</option>
						<?php foreach ($pages as $page) : ?>
							<option value="<?php echo $page->ID;?>" <?php if ($page->ID == $general_settings["cancel_return_page"]) { echo "selected";}?>><?php echo $page->post_title;?></option>
						<?php endforeach; ?>
					</select>				
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center">Logo to show on PayPal payment page:</th>
				<td>
					<input type="text" class="regular-text" name="logo_on_paypal_page" value="<?php echo $general_settings["logo_on_paypal_page"];?>" size="40"/>
					<span class="description">(max. 750 x 90 px)</span>
				</td>
				<!-- todo make uploadable -->
			</tr>
			<tr valign="top">
				<th scope="row" valign="center">Notify by Email:</th>
				<td>
					<input type="text" class="regular-text" name="notify_email" value="<?php echo $general_settings["notify_email"];?>" size="40"/>
					<br/><span class="description">A comma separated list of email addresses that should be notified whenever someone makes a donation to any of the goals.</span>
				</td>
			</tr>

			<tr valign="top">				
				<th scope="row" valign="center">Donation options:</th>
				<td>
					<div id="donation_sum_list">
						<?php $id = 0; ?>
						<?php if ($general_settings != null && isset($general_settings["donation_sums"])) : ?>
							<?php foreach($general_settings["donation_sums"] as $sum) : ?>
								<div id="donation_sum_<?php echo $id; ?>">
									<input type="text" class="regular-text" 
										name="donation_sum_<?php echo $id; ?>" value="<?php echo $sum; ?>" size="40"/>
									<a href="#" onClick="return removeFormTextField('donation_sum_list', 'donation_sum_<?php echo $id; ?>')">Remove</a>
								</div>
								<?php $id++; ?>
							<?php endforeach; ?>
						<?php else : ?>
							None yet. Click on "add new" below a few times to create some donation options (for example 5.00).
						<?php endif; ?>
					</div>
					<input type="hidden" name="donation_sum_num" 
						value="<?php echo count($general_settings["donation_sums"]);?>" id="donation_sum_num"/>
					<a href="#" onclick="return addFormTextField('donation_sum_num', 'donation_sum_list', 'donation_sum_');">Add new</a>					
				</td>
			</tr>
			
			
			<script type="text/javascript">
				function toggleCustomStyleField(selectField) {
					var styleField = document.getElementById('custom-style-field');
					if (selectField.options[selectField.selectedIndex].value == "custom") {
						styleField.removeAttribute("disabled");
					} else {
						styleField.setAttribute("disabled", "disabled");
					}
				}
			</script>
			
			<tr valign="top">				
				<th scope="row" valign="center">Donation widget style:</th>
				<td>
					<!--
					<select name="style" onchange="toggleCustomStyleField(this);">
						<?php foreach($style_options as $option => $label) : ?>
							<option value="<?php echo $option; ?>" <?php if ($option == $style) { echo "selected"; }?>><?php echo $label; ?></option>
						<?php endforeach; ?>
					</select>
					<br/>
				-->
					<textarea id="custom-style-field" name="custom" class="regular-text" style="width: 25em; height: 200px;"><?php echo $general_settings["custom"]; ?></textarea>
					<br/>
					<span class="description">You can use the following CSS classes to customize the looks of your donation widget:</span>
					<p>
					<ul class="description">
						<li><strong>.donation-can_donation-widget:</strong> the donation widget container</li>
						<li><strong>.widgettitle:</strong> The title of the donation widget (usually goal name)</li>
						<li><strong>.donation-can_goal-description:</strong> description of the goal in the donation widget</li>
						<li><strong>.donation_meter:</strong> Holder for the donation status display (contains the progress bar and textual presentation)</li>
						<li><strong>.donation_progress:</strong> A container for the donation progress bar item</li>
						<li><strong>.donation_progress_container:</strong> A container for the bar inside the progress bar item</li>
						<li><strong>.donation_progress_bar:</strong> The actual bar (fills .donation_progress_container according to current donation progress)</li>
					</ul>
					</p>
				</td>
			</tr>
						
		</table>
		<p class="submit"><input name="Submit" value="<?php _e('Save Changes'); ?>" type="submit" class="button-primary" /></span>
	</form>
</div>
