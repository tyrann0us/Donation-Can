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

<script type="text/javascript">
    var uploadToField = null;

    jQuery(document).ready(function() {
        window.send_to_editor = function(html) {
            imgurl = jQuery('img', html).attr('src');
            jQuery('input', uploadToField).val(imgurl);
            tb_remove();
        }
    });

    function uploadImage(element) {
        uploadToField = jQuery(element).closest('td');
        tb_show('', '<?php echo admin_url("media-upload.php?type=image&amp;TB_iframe=true");?>');
        return false;
    };
</script>

<div class="wrap">
    <h2><?php _e("Donation Can Settings", "donation_can");?></h2>

    <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="edit_settings" value="Y"/>
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('donation-can-general-settings-nonce'); ?>"/>

        <div id="poststuff" class="metabox-holder">

            <div id="post-body">
                <div id="post-body-content">

                    <div class="stuffbox">
                        <h3><?php _e("Payment information", "donation_can");?></h3>
                        <div class="inside" id="payment-info-div">
                            <table class="form-table">
                                <tr>
                                    <th scope="row" valign="center"><label for="paypal_email"><?php _e("PayPal account email:", "donation_can"); ?></label></th>
                                    <td valign="center"><input type="text" class="regular-text"  name="paypal_email" value="<?php echo $general_settings["paypal_email"];?>" size="40"/></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-top: 10px;">
                                        <input type="checkbox" name="debug_mode" value="1"
                                            <?php if ($general_settings["debug_mode"]) { echo "checked"; }?>
                                            onclick="toggleSandboxEmailField(this);"
                                        />
                                        <?php _e("Enable PayPal sandbox mode for testing donations.", "donation_can"); ?>
                                    </td>
                                </tr>
                                <tr id="paypal-sandbox-email-row" <?php if ($general_settings["debug_mode"] != 1) { echo "style=\"display:none;\""; }?>>
                                    <th scope="row" valign="center"><label for="paypal_sandbox_email"><?php _e("Sandbox test account email:", "donation_can"); ?></label></th>
                                    <td>
                                        <input type="text" class="regular-text" name="paypal_sandbox_email" value="<?php echo $general_settings["paypal_sandbox_email"]; ?>" size="40"/><br/>
                                        <a href="https://developer.paypal.com/" target="_blank"><?php _e("Sign in to PayPal Sandbox to create a test account", "donation_can"); ?></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="stuffbox">
                        <h3><?php _e("Donation settings", "donation_can");?></h3>
                        <div class="inside" id="donation-settings-div">
                            <table class="form-table">
                                <tr>
                                    <th scope="row" valign="center"><label for="currency"><?php _e("Default currency:", "donation_can");?></label></th>
                                    <td valign="center">
                                        <?php require_donation_can_view('currencies', array('currency' => $general_settings["currency"])); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" valign="center"><label for="currency"><?php _e("Default donation options:", "donation_can");?></label></th>
                                    <td valign="center">
					<div id="donation_sum_list">
                                            <?php $id = 0; ?>
                                            <?php if ($general_settings != null && isset($general_settings["donation_sums"])) : ?>
                                                <?php foreach($general_settings["donation_sums"] as $sum) : ?>
                                                    <div id="donation_sum_<?php echo $id; ?>">
                                                        <input type="text" class="regular-text" 
                                                            name="donation_sum_<?php echo $id; ?>" value="<?php echo $sum; ?>" size="40"/>
                                                        <a href="#" onClick="return removeFormTextField('donation_sum_list', 'donation_sum_<?php echo $id; ?>')"><?php _e("Remove", "donation_can");?></a>
                                                    </div>
                                                    <?php $id++; ?>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <?php _e('None yet. Click on "add new" below a few times to create some donation options (for example 5.00).', "donation_can");?>
                                            <?php endif; ?>
					</div>
					<input type="hidden" name="donation_sum_num" 
						value="<?php echo count($general_settings["donation_sums"]);?>" id="donation_sum_num"/>
                                        <div id="add-new-donation-option-div">
                                            <a href="#" class="button" onclick="return addFormTextField('donation_sum_num', 'donation_sum_list', 'donation_sum_');"><?php _e("Add new", "donation_can");?></a>
                                        </div>
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
                                    <th scope="row" valign="center"><?php _e("Text for continue button:", "donation_can");?></th>
                                    <td>
					<input type="text" class="regular-text" name="continue_button_text" value="<?php echo $general_settings["continue_button_text"];?>" size="40"/>
					<br/><span class="description"><?php _e("Applies when thank you page URL is set to something else than 'Use Paypal Default'.", "donation_can");?></span>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Email addresses to notify of new donations:", "donation_can");?></th>
                                    <td>
                                        <input type="text" class="regular-text" name="notify_email" value="<?php echo $general_settings["notify_email"];?>" size="40"/>
                                        <br/><span class="description"><?php _e("A comma separated list of email addresses that should be notified whenever someone makes a donation to any of the goals.", "donation_can");?></span>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>


                    <div class="stuffbox">
                        <h3><?php _e("Display options", "donation_can");?></h3>
                        <div class="inside" id="payment-info-div">
                            <table class="form-table">
                                <tr valign="top">
                                    <td colspan="2" scope="row" valign="center">
                                        <input type="checkbox" name="subtract_fees" value="1" <?php if ($general_settings["subtract_paypal_fees"]) { echo "checked"; }?>/>
                                        <label for="subtract_fees"><?php _e("Subtract PayPal fees from donation amounts shown to site visitors.", "donation_can");?></label>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td scope="row" valign="center" colspan="2">
                                        <input type="checkbox" name="link_back" value="1" <?php if ($general_settings["link_back"]) { echo "checked"; }?>/>
                                        <label for="link_back"><?php _e("Hide Donation Can back link.", "donation_can");?></label>
                                    <td>
                                </tr>

                            </table>
                        </div>
                    </div>

                    <div class="stuffbox">
                        <h3><?php _e("PayPal checkout page settings", "donation_can");?></h3>
                        <div class="inside" id="payment-info-div">

                            <?php
                            $options = get_option("donation_can_general");
                            if ($options != null && $options["debug_mode"]) :
                            ?>
                                <div class='donation_can_notice'><?php _e("Customizations made to the PayPal checkout page are not visible in PayPal sandbox. To test the changes, turn off sandbox mode.", "donation_can");?></div>
                            <?php endif; ?>

                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Business logo (optional):", "donation_can");?></th>
                                    <td>
                                        <input type="text" class="regular-text" name="logo_on_paypal_page" value="<?php echo $general_settings["logo_on_paypal_page"];?>" size="40"/>
                                        <a href="#" class="button" title="Upload image" onclick="uploadImage(this);"><?php _e("Upload image", "donation_can"); ?></a>

                                        <br/><span class="description">(<?php _e("max size: 150 x 150 px", "donation_can");?>)</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Header image (optional):", "donation_can");?></th>
                                    <td>
                                        <input type="text" class="regular-text" name="header_on_paypal_page" value="<?php echo $general_settings["header_on_paypal_page"];?>" size="40"/>
                                        <a href="#" class="button" title="Upload image" onclick="uploadImage(this);"><?php _e("Upload image", "donation_can"); ?></a>
                                        <br/><span class="description">(<?php _e("max size: 750 x 90 px", "donation_can");?>)</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Background color:", "donation_can");?></th>
                                    <td>
                                        <input type="text" class="regular-text" name="bg_on_paypal_page" value="<?php echo $general_settings["bg_on_paypal_page"];?>" size="40"/>
                                        <br/><span class="description">(<?php _e("A six digit HTML hex value (e.g. FF0000 for red)", "donation_can");?>)</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Header background color:", "donation_can");?></th>
                                    <td>
                                        <input type="text" class="regular-text" name="header_bg_on_paypal_page" value="<?php echo $general_settings["header_bg_on_paypal_page"];?>" size="40"/>
                                        <br/><span class="description">(<?php _e("A six digit HTML hex value (e.g. FF0000 for red)", "donation_can");?>)</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Header border color:", "donation_can");?></th>
                                    <td>
                                        <input type="text" class="regular-text" name="header_border_on_paypal_page" value="<?php echo $general_settings["header_border_on_paypal_page"];?>" size="40"/>
                                        <br/><span class="description">(<?php _e("A six digit HTML hex value (e.g. FF0000 for red)", "donation_can");?>)</span>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td scope="row" valign="center" colspan="2">
                                        <input type="checkbox" name="ask_for_note" value="1" <?php if ($general_settings["ask_for_note"] == '1') { echo "checked"; }?>
                                               onclick="togglePayPalNoteFields(this);">
                                        <label for="ask_for_note"><?php _e("Ask the visitor leave a note with her donation.", "donation_can");?></label>

                                    </td>
                                </tr>
                                <tr valign="top" id="paypal-note-field-row" <?php if ($general_settings["ask_for_note"] != 1) { echo "style=\"display:none;\""; }?>>
                                    <th scope="row" valign="center"><?php _e("Label for the note field:", "donation_can");?></th>
                                    <td>
                                        <input type="text" class="regular-text" name="note_field_label" value="<?php echo $general_settings["note_field_label"];?>" size="40"/>
                                    </td>
        			</tr>
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Ask for shipping address:", "donation_can");?></th>
                                    <td>
                                        <select name="require_shipping">
                                            <option value="0" <?php if ($general_settings["require_shipping"] == 0) { echo "selected"; }?>><?php _e("Prompt for an address, but do not require one.", "donation_can");?></option>
                                            <option value="1" <?php if ($general_settings["require_shipping"] == 1) { echo "selected"; }?>><?php _e("Do not prompt for an address.", "donation_can"); ?></option>
                                            <option value="2" <?php if ($general_settings["require_shipping"] == 2) { echo "selected"; }?>><?php _e("Prompt for an address, and require one.", "donation_can"); ?></option>
                                        </select>
                                    </td>
        			</tr>

                            </table>
                        </div>
                    </div>

                    <div class="stuffbox">
                        <h3><?php _e("Email notification settings", "donation_can");?></h3>
                        <div class="inside" id="email-settings-div">
                            <div class="donation_can_notice">
                                <p>
                                    <?php _e("In the email templates below, you can use the following tags to represent data about the donation:", "donation_can"); ?>
                                </p>
                                <ul>
                                    <li><code>#USER_NAME#</code> <?php _e("The name of the person making the donation.", "donation_can");?></li>
                                    <li><code>#USER_EMAIL#</code> <?php _e("The email address of the person making the donation.", "donation_can");?></li>
                                    <li><code>#CURRENCY#</code> <?php _e("Currency used in the donation.", "donation_can");?></li>
                                    <li><code>#AMOUNT#</code> <?php _e("Amount donated.", "donation_can");?></li>
                                    <li><code>#FEE#</code> <?php _e("PayPal fee.", "donation_can");?></li>
                                    <li><code>#CAUSE_NAME#</code> <?php _e("Name of the cause.", "donation_can");?></li>
                                    <li><code>#CAUSE_CODE#</code> <?php _e("ID of the cause.", "donation_can");?></li>
                                    <li><code>#TRANSACTION_ID#</code> <?php _e("Unique ID for the donation, generated by <strong>PayPal</strong>.", "donation_can");?></li>
                                    <li><code>#ITEM_NUMBER#</code> <?php _e("Unique ID for the donation, generated by Donation Can.", "donation_can");?></li>
                                    <li><code>#DONATION_TIME#</code> <?php _e("Date and time of donation.", "donation_can");?></li>
                                </ul>
                            </div>
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Send email messages from:", "donation_can");?></th>
                                    <td>
                                        <input type="text" class="widefat" name="email_from" value="<?php echo $general_settings["email_from"];?>"/>
                                        <br/><span class="description"><?php _e("The email address to use as sender in email messages.", "donation_can");?></span>

                                        <input type="text" class="widefat" name="email_from_name" value="<?php echo $general_settings["email_from_name"];?>"/>
                                        <br/><span class="description"><?php _e("The name to use as sender in email messages.", "donation_can");?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td colspan="2">
                                        <input type="checkbox" name="use_html_emails" value="1" <?php if ($general_settings["use_html_emails"]) { echo "checked"; } ?>/> <?php _e("Use HTML in email messages.", "donation_can"); ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Email notification template:", "donation_can");?></th>
                                    <td>
                                        <textarea cols="50" rows="5" class="widefat" name="email_template"><?php echo $general_settings["email_template"]; ?></textarea>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td colspan="2">
                                        <input type="checkbox" name="send_receipt" value="1" <?php if ($general_settings["send_receipt"] == '1') { echo "checked"; }?>>
                                        <label for="send_receipt"><?php _e("Send a receipt to donors after receiving donation.", "donation_can");?></label>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Minimum donation for sending receipt:", "donation_can"); ?></th>
                                    <td>
                                        <input type="text" class="widefat" name="receipt_threshold" value="<?php echo $general_settings["receipt_threshold"];?>"/>
                                        <br/><span class="description"><?php _e("Receipts are not sent for donations lower than the value specified here.", "donation_can");?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Email receipt subject:", "donation_can");?></th>
                                    <td>
                                        <input type="text" name="receipt_subject" class="widefat" value="<?php echo $general_settings["receipt_subject"]; ?>"/>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" valign="center"><?php _e("Email receipt template:", "donation_can");?></th>
                                    <td>
                                        <textarea cols="50" rows="10" class="widefat" name="receipt_template"><?php echo $general_settings["receipt_template"]; ?></textarea>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>


                </div>
            </div>
        </div>


        <table class="form-table">

            <tr valign="top">
                <td colspan="2" scope="row" valign="center">
                    <input type="checkbox" name="enable_logging" value="1" <?php if ($general_settings["enable_logging"]) { echo "checked"; }?>/>
                    <?php _e("Write a log file to the plugin's home directory. (Make sure the directory is writeable.)", "donation_can");?>
                </td>
            </tr>



            <!-- Temporary solution until we have time to put in the proper Javascript based sorting -->
            <tr valign="top">
                <th scope="row" valign="center"><?php _e("Sort donation causes by:", "donation_can");?></th>
                <td>
                    <select name="sort_causes_field">
                        <option value="1" <?php if ($general_settings["sort_causes_field"] == 1) { echo "selected"; } ?>><?php _e("Name", "donation_can");?></option>
                        <option value="2" <?php if ($general_settings["sort_causes_field"] == 2) { echo "selected"; } ?>><?php _e("Added by", "donation_can");?></option>
                        <option value="3" <?php if ($general_settings["sort_causes_field"] == 3) { echo "selected"; } ?>><?php _e("Fundraising goal", "donation_can");?></option>
                        <option value="4" <?php if ($general_settings["sort_causes_field"] == 4) { echo "selected"; } ?>><?php _e("Raised so far", "donation_can");?></option>
                        <option value="5" <?php if ($general_settings["sort_causes_field"] == 5) { echo "selected"; } ?>><?php _e("Date added", "donation_can");?></option>
                    </select>

                    <select name="sort_causes_order">
                        <option value="ASC" <?php if ($general_settings["sort_causes_order"] == "ASC") { echo "selected"; } ?>><?php _e("Ascending", "donation_can");?></option>
                        <option value="DESC" <?php if ($general_settings["sort_causes_order"] == "DESC") { echo "selected"; } ?>><?php _e("Descending", "donation_can");?></option>
                    </select>
                </td>
            </tr>

												
        </table>
        
        <p class="submit"><input name="Submit" value="<?php _e('Save Changes', "donation_can"); ?>" type="submit" class="button-primary" /></p>
    </form>
</div>
