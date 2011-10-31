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

<script type="text/javascript">
jQuery(document).ready(
    function() {
        // If a name has been entered, create id
        var value = jQuery("input[name=name]").val();
        if (value != null && value.length > 1) {
            createCauseIdFromName();
        }
    }
);
</script>

<div class="wrap">
    <?php if ($edit) : ?>
        <h2><?php _e("Edit Cause", "donation_can"); ?></h2>
        <?php $save_button_text = __("Update Cause", "donation_can"); ?>
    <?php else : ?>
        <h2><?php _e("Add New Cause", "donation_can"); ?></h2>
        <?php $save_button_text = __("Add Cause", "donation_can"); ?>
    <?php endif; ?>
	
    <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <?php if ($edit) : ?>
            <?php wp_nonce_field('donation_can-edit_cause'); ?>
            <input type="hidden" name="edit_cause" value="<?php echo $id; ?>"/>
        <?php else : ?>
            <?php wp_nonce_field('donation_can-add_cause'); ?>
            <input type="hidden" name="add_cause" value="Y"/>
        <?php endif; ?>

        <div id="poststuff" class="metabox-holder has-right-sidebar">
            <div id="side-info-column" class="inner-sidebar">
                <div id="side-sortables" class="meta-box-sortables ui-sortable">
                    <div class="postbox" id="donation-submit-div">
                        <div class="handlediv" title="Click to toggle">
                            <br/>
                        </div>
                        <h3 class="hndle"><span><?php _e("Save", "donation_can");?></span></h3>
                        <div class="inside">
                            <div class="submitbox" id="submitlink">
                                <div id="major-publishing-actions">
                                    <div id="delete-action"></div>
                                    <div id="publishing-action">
                                        <input type="submit" onclick="return verifyAddCauseFormFields();" class="button-primary" id="publish"
                                               value="<?php echo $save_button_text; ?>"/>
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

                    <div id="titlediv">
                        <div id="titlewrap">
                            <label class="screen-reader-text" for="name">Title</label>
                            <input type="text" name="name"
                                   onblur="createCauseIdFromName();"
                                   size="30" tabindex="1" id="title" autocomplete="off"
                                   value="<?php echo $goal["name"];?>">
                        </div>
                        <div class="inside">
                            <div id="edit-slug-box" <?php if ($id == null) : ?>style="display:none;"<?php endif; ?>>
                                <strong><?php _e("Cause ID:", "donation_can");?></strong> <span id="id-preview"><?php echo $id; ?></span> <?php if (!$edit) : ?><a class="button" id="edit-id-button" onclick="editCauseId();"><?php _e("Edit", "donation_can");?></a><?php endif; ?>
                                <input type="text" name="id" value="<?php echo $id; ?>" style="display:none;">
                                <a class="button" id="save-id-button" onclick="saveCauseId();" style="display:none;">Save</a>
                            </div>
                        </div>
                    </div>

                    <div class="stuffbox">
                        <h3><label for="donation_goal"><?php _e("How much money do you need for this cause?", "donation_can");?></label></h3>
                        <div class="inside" id="goal-div">
                            <input type="text" name="donation_goal" class="donation-goal" id="donation-goal"
                                   onkeyup="resetDonationGoalCheckbox();"
                                   onblur="checkMoneyFormatting(this, false);"
                                   value="<?php echo $goal["donation_goal"];?>" size="30" /> <span id="goal-currency" title="Click to change currency"><?php if (!$edit) : ?><a href="#" onclick="return showCurrencyOptions();"><?php endif; ?><?php echo $currency_display; ?><?php if (!$edit) : ?></a><?php endif; ?></span>
                            <span id="currency-options" style="display:none;">
                                <?php require_donation_can_view('currencies', array('currency' => $currency)); ?>
                                <a href="#" class="button" onclick="return hideCurrencySelection();"><?php _e("Save"); ?></a>
                            </span>
                            <p>
                                <input type="checkbox" class="checkbox" name="no_goal" id="no-goal-checkbox" <?php if ($goal["donation_goal"] == null || $goal["donation_goal"] == "") { echo "checked"; }?> onclick="clearDonationGoal(this);"> <?php _e("Don't set a target for the fundraising. The amount raised so far is shown instead of a progress bar.", "donation_can");?>
                            </p>
                        </div>
                    </div>

                    <div class="stuffbox">
                        <h3><label for="donation-options"><?php _e("Donation options", "donation_can");?></label></h3>
                        <div class="inside" id="donation-options-div">
                            <p>
                                <?php _e("Decide the donation sums for your visitors to choose from. You can use the 'Add new' button to create as many options as you need.", "donation_can");?>
                            </p>

                            <div id="donation_sum_list">
                            <?php if ($donation_sums != null) : ?>
                                <?php $id = 0; ?>
                                <?php foreach($donation_sums as $sum) : ?>
                                    <div id="donation_sum_<?php echo $id; ?>">
                                        <input type="text" class="donation-option"
                                               onblur="checkMoneyFormatting(this, true);"
                                               name="donation_sum_<?php echo $id; ?>" value="<?php echo $sum; ?>" size="40"/><a href="#" onClick="return removeFormTextField('donation_sum_list', 'donation_sum_<?php echo $id; ?>')"><?php _e("Remove", "donation_can");?></a>
                                    </div>
                                    <?php $id++; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </div>
                            <input type="hidden" name="donation_sum_num"
                                    value="<?php echo count($donation_sums);?>" id="donation_sum_num"/>
                            <div id="add-new-donation-option-div">
                                <a class="button" href="#" onclick="return addFormTextField('donation_sum_num', 'donation_sum_list', 'donation_sum_', 'donation-option');"><?php _e("Add new", "donation_can");?></a>
                            </div>

                            <p>
                                <input type="checkbox" value="1" name="allow_freeform_donation_sum" <?php if ($goal["allow_freeform_donation_sum"]) { echo "checked"; } ?>> <?php _e("Include the option for your visitors to pick their donation sums freely.", "donation_can");?>
                            </p>
                        </div>
                    </div>

                    <div class="stuffbox">
                        <h3><label for="description"><?php _e("Describe the cause in a few words", "donation_can");?></label></h3>
                        <div class="inside" id="goal-description-div">
                            <textarea name="description" cols="100" rows="10"><?php echo $goal["description"];?></textarea>
                        </div>
                    </div>
					
                    <div class="stuffbox">
                        <h3><?php _e("Where should the visitor go after donating to this cause?", "donation_can");?></h3>
                        <div class="inside" id="thank-you-page-id-div">
                            <p>
                                <label for="return_page"><?php _e("Thank you page", "donation_can");?></label><br/>
                                <select name="return_page">
                                    <option value="-1">-- <?php _e("Use PayPal Default (no return link)", "donation_can");?> --</option>
                                    <?php foreach ($pages as $page) : ?>
                                        <option value="<?php echo $page->ID;?>"><?php echo $page->post_title;?></option>
                                    <?php endforeach; ?>
                                </select>
                            </p>

                            <p>
                                <label for="name"><?php _e("Cancelled page", "donation_can");?></label><br/>
                                <select name="cancelled_return_page">
                                    <option value="-1">-- <?php _e("Use PayPal Default (no return link)", "donation_can");?> --</option>
                                    <?php foreach ($pages as $page) : ?>
                                            <option value="<?php echo $page->ID;?>"><?php echo $page->post_title;?></option>
                                    <?php endforeach; ?>
                                </select>
                            </p>
                        </div>
                    </div>

					
                    <div class="stuffbox">
                            <h3><label for="name"><?php _e("Who should be notified of donations?", "donation_can");?></label></h3>
                            <div class="inside" id="notify-email-div">
                                    <input type="text" name="notify_email" size="30" value="<?php echo $goal["notify_email"];?>"]"/>
                                    <p>
                                            <?php _e("A comma separated list of email addresses that should be notified when a donation is made to this cause. In addition to these, the general email addresses defined in general settings are notified.", "donation_can");?>
                                    </p>
                            </div>
                    </div>
					

                </div>
            </div>
        </div>
    </form>
</div>