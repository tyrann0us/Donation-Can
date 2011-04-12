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
?>

<script type="text/javascript" src="<?php echo bloginfo("url"); ?>/wp-content/plugins/donation-can/view/scripts.js"></script>

<div class="wrap">
	<h2><?php _e("Donation Can Settings", "donation_can");?></h2>

	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="edit_settings" value="Y"/>

		<table class="form-table">
			<tr valign="top">
				<th scope="row" valign="center"><?php _e("PayPal Email:", "donation_can");?></th>
				<td><input type="text" class="regular-text" name="paypal_email" value="<?php echo $general_settings["paypal_email"];?>" size="40"/>
					<span class="description"><?php _e("Your PayPal account email. This is where the funds will be sent to.", "donation_can");?></span>
				</td>
			</tr>
			<tr valign="top">
                <th scope="row" valign="center"><?php _e("Currency:", "donation_can");?></th>
                <td>
                    <select name="currency">
                        <option value="USD" <?php if ($general_settings["currency"] == 'USD') { echo "selected"; }?>>U.S. Dollars (USD)</option>
                        <option value="EUR" <?php if ($general_settings["currency"] == 'EUR') { echo "selected"; }?>>Euros (EUR)</option>
                        <option value="GBP" <?php if ($general_settings["currency"] == 'GBP') { echo "selected"; }?>>Pounds Sterling (GBP)</option>

                        <option value="AUD" <?php if ($general_settings["currency"] == 'AUD') { echo "selected"; }?>>Australian Dollars (AUD)</option>
                        <option value="NZD" <?php if ($general_settings["currency"] == 'NZD') { echo "selected"; }?>>New Zealand Dollars (NZD)</option>
                        <option value="CAD" <?php if ($general_settings["currency"] == 'CAD') { echo "selected"; }?>>Canadian Dollars (CAD)</option>
                        <option value="JPY" <?php if ($general_settings["currency"] == 'JPY') { echo "selected"; }?>>Yen (JPY)</option>
                        <option value="CHF" <?php if ($general_settings["currency"] == 'CHF') { echo "selected"; }?>>Swiss Franc (CHF)</option>
                        <option value="HKD" <?php if ($general_settings["currency"] == 'HKD') { echo "selected"; }?>>Hong Kong Dollar (HKD)</option>
                        <option value="SGD" <?php if ($general_settings["currency"] == 'SGD') { echo "selected"; }?>>Singapore Dollar (SGD)</option>
                        <option value="SEK" <?php if ($general_settings["currency"] == 'SEK') { echo "selected"; }?>>Swedish Krona (SEK)</option>
                        <option value="DKK" <?php if ($general_settings["currency"] == 'DKK') { echo "selected"; }?>>Danish Krone (DKK)</option>
                        <option value="NOK" <?php if ($general_settings["currency"] == 'NOK') { echo "selected"; }?>>Norwegian Krone (NOK)</option>
                        <option value="PLN" <?php if ($general_settings["currency"] == 'PLN') { echo "selected"; }?>>Polish Zloty (PLN)</option>
                        <option value="HUF" <?php if ($general_settings["currency"] == 'HUF') { echo "selected"; }?>>Hungarian Forint (HUF)</option>
                        <option value="CZK" <?php if ($general_settings["currency"] == 'CZK') { echo "selected"; }?>>Czech Koruna (CZK)</option>
                        <option value="ILS" <?php if ($general_settings["currency"] == 'ILS') { echo "selected"; }?>>Israeli Shekel (ILS)</option>
                        <option value="MXN" <?php if ($general_settings["currency"] == 'MXN') { echo "selected"; }?>>Mexican Peso (MXN)</option>
                        <option value="BRL" <?php if ($general_settings["currency"] == 'BRL') { echo "selected"; }?>>Brazilian Real (only for Brazilian users) (BRL)</option>
                        <option value="MYR" <?php if ($general_settings["currency"] == 'MYR') { echo "selected"; }?>>Malaysian Ringgits (only for Malaysian users) (MYR)</option>
                        <option value="PHP" <?php if ($general_settings["currency"] == 'PHP') { echo "selected"; }?>>Philippine Pesos (PHP)</option>
                        <option value="TWD" <?php if ($general_settings["currency"] == 'TWD') { echo "selected"; }?>>Taiwan New Dollars (TWD)</option>
                        <option value="THB" <?php if ($general_settings["currency"] == 'THB') { echo "selected"; }?>>Thai Baht (THB)</option>

                    </select>
                </td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center"><?php _e("Prompt to include a note:", "donation_can");?></th>
				<td>
					<input type="radio" name="ask_for_note" value="0" <?php if ($general_settings["ask_for_note"] == '0') { echo "checked"; }?>> <?php _e("Provide a text box and prompt for the note", "donation_can");?><br/>
					<input type="radio" name="ask_for_note" value="1" <?php if ($general_settings["ask_for_note"] == '1') { echo "checked"; }?>> <?php _e("Hide the text box and the note", "donation_can");?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center"><?php _e("Thank you page:", "donation_can");?></th>
				<td>
					<select name="return_page">
						<option value="-1" <?php if ("-1" == $general_settings["return_page"]) { echo "selected";}?>>-- <?php _e("Use PayPal Default", "donation_can");?> --</option>
						<?php foreach ($pages as $page) : ?>
							<option value="<?php echo $page->ID;?>" <?php if ($page->ID == $general_settings["return_page"]) { echo "selected";}?>><?php echo $page->post_title;?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center"><?php _e("Text for continue button:", "donation_can");?></th>
				<td>
					<input type="text" class="regular-text" name="continue_button_text" value="<?php echo $general_settings["continue_button_text"];?>" size="40"/>
					<span class="description"><?php _e("Applies when thank you page URL is set to something else than 'Use Paypal Default'.", "donation_can");?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center"><?php _e("Payment cancelled page:", "donation_can");?></th>
				<td>
					<select name="cancel_return_page">
						<option value="-1" <?php if ("-1" == $general_settings["cancel_return_page"]) { echo "selected";}?>>-- <?php _e("Use PayPal Default", "donation_can");?> --</option>
						<?php foreach ($pages as $page) : ?>
							<option value="<?php echo $page->ID;?>" <?php if ($page->ID == $general_settings["cancel_return_page"]) { echo "selected";}?>><?php echo $page->post_title;?></option>
						<?php endforeach; ?>
					</select>				
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="center"><?php _e("Logo to show on PayPal payment page:", "donation_can");?></th>
				<td>
					<input type="text" class="regular-text" name="logo_on_paypal_page" value="<?php echo $general_settings["logo_on_paypal_page"];?>" size="40"/>
					<span class="description">(<?php _e("max. 750 x 90 px", "donation_can");?>)</span>
				</td>
				<!-- todo make uploadable -->
			</tr>
			<tr valign="top">
				<th scope="row" valign="center"><?php _e("Notify by Email:", "donation_can");?></th>
				<td>
					<input type="text" class="regular-text" name="notify_email" value="<?php echo $general_settings["notify_email"];?>" size="40"/>
					<br/><span class="description"><?php _e("A comma separated list of email addresses that should be notified whenever someone makes a donation to any of the goals.", "donation_can");?></span>
				</td>
			</tr>

            <tr valign="top">
				<th scope="row" valign="center"><?php _e("Subtract PayPal fees:", "donation_can");?></th>
				<td>
					<input type="checkbox" name="subtract_fees" value="1" <?php if ($general_settings["subtract_paypal_fees"]) { echo "checked"; }?>/>
					<br/><span class="description"><?php _e("Check this checkbox if you want the plugin to only show the amount you have actually received instead of the amount donated by people.", "donation_can");?></span>
				</td>
			</tr>            

            <tr valign="top">
				<th scope="row" valign="center"><?php _e("Sandbox mode:", "donation_can");?></th>
				<td>
					<input type="checkbox" name="debug_mode" value="1" <?php if ($general_settings["debug_mode"]) { echo "checked"; }?>/>
					<br/><span class="description"><?php _e("Check this checkbox if you want to use the PayPal sandbox to test the plugin.", "donation_can");?></span>
				</td>
			</tr>

            <tr valign="top">
                <th scope="row" valign="center"><?php _e("Hide Donation Can back link:", "donation_can");?></th>
                <td>
                    <input type="checkbox" name="link_back" value="1" <?php if ($general_settings["link_back"]) { echo "checked"; }?>/>
                    <br/><span class="description"><?php _e("Check the checkbox if you don't want to include a link to Donation Can in your donation widgets.", "donation_can");?></span>
                </td>
			</tr>

                        <!-- Temporary solution until we have time to put in the proper Javascript based sorting -->
                        <tr valign="top">
                            <th scope="row" valign="center"><?php _e("Sort donation causes by:", "donation_can");?></th>
                            <td>
                                <select name="sort_causes_field">
                                    <option value="1" <?php if ($general_settings["sort_causes_field"] == 1) { echo "selected"; } ?>><?php _e("Name", "donation-can");?></option>
                                    <option value="2" <?php if ($general_settings["sort_causes_field"] == 2) { echo "selected"; } ?>><?php _e("Added by", "donation-can");?></option>
                                    <option value="3" <?php if ($general_settings["sort_causes_field"] == 3) { echo "selected"; } ?>><?php _e("Fundraising goal", "donation-can");?></option>
                                    <option value="4" <?php if ($general_settings["sort_causes_field"] == 4) { echo "selected"; } ?>><?php _e("Raised so far", "donation-can");?></option>
                                    <option value="5" <?php if ($general_settings["sort_causes_field"] == 5) { echo "selected"; } ?>><?php _e("Date added", "donation-can");?></option>
                                </select>

                                <select name="sort_causes_order">
                                    <option value="ASC" <?php if ($general_settings["sort_causes_order"] == "ASC") { echo "selected"; } ?>><?php _e("Ascending", "donation-can");?></option>
                                    <option value="DESC" <?php if ($general_settings["sort_causes_order"] == "DESC") { echo "selected"; } ?>><?php _e("Descending", "donation-can");?></option>
                                </select>
                            </td>
                        </tr>

			<tr valign="top">				
				<th scope="row" valign="center"><?php _e("Donation options:", "donation_can");?></th>
				<td>
					<div id="donation_sum_list">
						<?php $id = 0; ?>
						<?php if ($general_settings != null && isset($general_settings["donation_sums"])) : ?>
							<?php foreach($general_settings["donation_sums"] as $sum) : ?>
								<div id="donation_sum_<?php echo $id; ?>">
									<input type="text" class="regular-text" 
										name="donation_sum_<?php echo $id; ?>" value="<?php echo $sum; ?>" size="40"/>
									<a href="#" onClick="return removeFormTextField('donation_sum_list', 'donation_sum_<?php echo $id; ?>')"><?php _e("Remove");?></a>
								</div>
								<?php $id++; ?>
							<?php endforeach; ?>
						<?php else : ?>
							<?php _e('None yet. Click on "add new" below a few times to create some donation options (for example 5.00).', "donation_can");?>
						<?php endif; ?>
					</div>
					<input type="hidden" name="donation_sum_num" 
						value="<?php echo count($general_settings["donation_sums"]);?>" id="donation_sum_num"/>
					<a href="#" onclick="return addFormTextField('donation_sum_num', 'donation_sum_list', 'donation_sum_');"><?php _e("Add new", "donation_can");?></a>					
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
				<th scope="row" valign="center"><?php _e("Donation widget style:", "donation_can");?></th>
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
					<span class="description"><?php _e("You can use the following CSS classes to customize the looks of your donation widget:", "donation_can");?></span>
					<p>
					<ul class="description">
						<li><strong>.donation-can_donation-widget:</strong> <?php _e("the donation widget container", "donation_can");?></li>
						<li><strong>.widgettitle:</strong> <?php _e("The title of the donation widget (usually goal name)", "donation_can");?></li>
						<li><strong>.donation-can_goal-description:</strong> <?php _e("description of the goal in the donation widget", "donation_can");?></li>
						<li><strong>.donation_meter:</strong> <?php _e("Holder for the donation status display (contains the progress bar and textual presentation)", "donation_can");?></li>
						<li><strong>.donation_progress:</strong> <?php _e("A container for the donation progress bar item", "donation_can");?></li>
						<li><strong>.donation_progress_container:</strong> <?php _e("A container for the bar inside the progress bar item", "donation_can");?></li>
						<li><strong>.donation_progress_bar:</strong> <?php _e("The actual bar (fills .donation_progress_container according to current donation progress)", "donation_can");?></li>
					</ul>
					</p>
				</td>
			</tr>
						
		</table>
		<p class="submit"><input name="Submit" value="<?php _e('Save Changes'); ?>" type="submit" class="button-primary" /></p>
	</form>
</div>
