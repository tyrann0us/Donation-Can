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

<script type="text/javascript" src="<?php echo bloginfo("url"); ?>/wp-content/plugins/donation_can/view/scripts.js"></script>

<div class="wrap">
	<h2><?php _e("Edit Donation Goal", "donation_can");?></h2>
	
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="edit_cause" value="<?php echo $id; ?>"/>

		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div id="side-info-column" class="inner-sidebar">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox" id="donation-submit-div">
						<div class="handlediv" title="<?php _e("Click to toggle");?>">
							<br/>
						</div>
						<h3 class="hndle"><span><?php _e("Save");?></span></h3>
						<div class="inside">
							<div class="submitbox" id="submitlink">
								<!--
								<div id="minor-publishing">
									TODO: we could add preview?
								</div>
								-->
								<div id="major-publishing-actions">
									<div id="delete-action"></div>
									<div id="publishing-action">
										<input type="submit" class="button-primary" id="publish" value="<?php _e("Save Changes");?>"/>
									</div>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>	
							</div>
						</div>
					</div>
				</div>
			</div>
		
			<div id="post-body">
				<div id="post-body-content">
					<div class="stuffbox">
						<h3><label for="id"><?php _e("Goal ID", "donation_can");?></label></h3>
						<div class="inside" id="goal-id-div">
							<input type="text" name="id" value="<?php echo $id;?>" size="30"/>
							<p>
								<?php _e('Pick a descriptive but short id for the goal. It will be used as the product code inside PayPal. For example "coffee" or "buymeamac".', "donation_can");?>
							</p>
						</div>
					</div>
					
					<div class="stuffbox">
						<h3><label for="name"><?php _e("Name", "donation_can");?></label></h3>
						<div class="inside" id="goal-name-div">
							<input type="text" name="name" value="<?php echo $goal["name"];?>" size="30"/>
						</div>
					</div>
					
					<div class="stuffbox">
						<h3><label for="description"><?php _e("Description", "donation_can");?></label></h3>
						<div class="inside" id="goal-description-div">
							<textarea name="description" cols="100" rows="10"><?php echo $goal["description"];?></textarea>
						</div>
					</div>

					<div class="stuffbox">
						<h3><label for="name"><?php _e("Fundraising Target (USD)", "donation_can");?></label></h3>
						<div class="inside" id="goal-div">
							<input type="text" name="donation_goal" value="<?php echo $goal["donation_goal"];?>" size="30"/>
							<p>
								<?php _e("If you don't want to set a target for the fundraising, you can leave this field empty. In that case, the amount raised so far is shown instead of a progress bar.", "donation_can");?>
							</p>
						</div>
					</div>	
					
					<div class="stuffbox">
						<h3><label for="name"><?php _e("Thank you page", "donation_can");?></label></h3>
						<div class="inside" id="thank-you-page-id-div">
							<select name="return_page">
								<option value="-1" <?php if ("-1" == $goal["return_page"]) { echo "selected"; }?>>-- <?php _e("Use Plugin Default", "donation_can");?> --</option>
								<?php foreach ($pages as $page) : ?>
									<option value="<?php echo $page->ID;?>" <?php if ($page->ID == $goal["return_page"]) { echo "selected"; }?>><?php echo $page->post_title;?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>			

					<div class="stuffbox">
						<h3><label for="name"><?php _e("Text for Continue button", "donation_can");?></label></h3>
						<div class="inside" id="continue-button-text-div">
							<input type="text" name="continue_button_text" value="<?php echo $goal["continue_button_text"];?>" size="30"/>
						</div>
					</div> 

					<div class="stuffbox">
						<h3><label for="name"><?php _e("Cancelled page", "donation_can");?></label></h3>
						<div class="inside" id="cancelled-page-id-div">
							<select name="cancelled_return_page">
								<option value="-1" <?php if ("-1" == $goal["cancelled_return_page"]) { echo "selected"; }?>>-- <?php _e("Use Plugin Default", "donation_can");?> --</option>
								<?php foreach ($pages as $page) : ?>
									<option value="<?php echo $page->ID;?>" <?php if ($page->ID == $goal["cancelled_return_page"]) { echo "selected"; }?>><?php echo $page->post_title;?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="stuffbox">
						<h3><label for="name"><?php _e("Notify by Email", "donation_can");?></label></h3>
						<div class="inside" id="notify-email-div">
							<input type="text" name="notify_email" value="<?php echo $goal["notify_email"];?>" size="30"/>
							<p>
								<?php _e("A comma separated list of email addresses that should be notified when a donation	is made to this cause. In addition to these, the general email addresses defined in general settings are notified.", "donation_can");?>
							</p>
						</div>
					</div>

					<div class="stuffbox">
						<h3><label for="donation-options"><?php _e("Donation Options", "donation_can");?></label></h3>
						<div class="inside" id="donation-options-div">
							<div id="donation_sum_list">
								<?php if ($goal["donation_sums"] != null) : ?>
									<?php $id = 0; ?>
									<?php foreach($goal["donation_sums"] as $sum) : ?>
										<div id="donation_sum_<?php echo $id; ?>">
											<input type="text" class="regular-text" 
												name="donation_sum_<?php echo $id; ?>" value="<?php echo $sum; ?>" size="40"/>
												<a href="#" onClick="return removeFormTextField('donation_sum_list', 'donation_sum_<?php echo $id; ?>')"><?php _e("Remove", "donation_can");?></a>
										</div>
										<?php $id++; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
							<input type="hidden" name="donation_sum_num" 
								value="<?php echo count($goal["donation_sums"]);?>" id="donation_sum_num"/>
							<a href="#" onclick="return addFormTextField('donation_sum_num', 'donation_sum_list', 'donation_sum_');"><?php _e("Add new", "donation_can");?></a>
							<p>
								<input type="checkbox" name="allow_freeform_donation_sum" value="1" <?php if ( $goal["allow_freeform_donation_sum"]) { echo "checked"; } ?>> <?php _e("Include freeform donation option", "donation_can");?>
							</p>
							<p>
								<?php _e("Enter the list of donation options you want to allow with this goal. Use the 'Add new' link above to create as many input fields as you need. If you don't specify any options here, the defaults from the general settings page are used.", "donation_can");?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</form>
</div>
