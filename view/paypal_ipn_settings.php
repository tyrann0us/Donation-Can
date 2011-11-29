<h4><?php _e("Account", "donation_can");?></h4>

<table class="form-table">
    <tr>
        <th scope="row" valign="center"><label for="paypal_email"><?php _e("PayPal account email:", "donation_can"); ?></label></th>
        <td valign="center"><input type="text" class="regular-text"  name="paypal_email" value="<?php echo $settings["paypal_email"];?>" size="40"/></td>
    </tr>
    <tr id="paypal-sandbox-email-row">
        <th scope="row" valign="center"><label for="paypal_sandbox_email"><?php _e("Sandbox test account email:", "donation_can"); ?></label></th>
        <td>
            <input type="text" class="regular-text" name="paypal_sandbox_email" value="<?php echo $settings["paypal_sandbox_email"]; ?>" size="40"/><br/>
            <a href="https://developer.paypal.com/" target="_blank"><?php _e("Sign in to PayPal Sandbox to create a test account", "donation_can"); ?></a>
        </td>
    </tr>
</table>

<h4><?php _e("Checkout page settings", "donation_can");?></h4>

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
            <input type="text" class="regular-text" name="logo_on_paypal_page" value="<?php echo $settings["logo_on_paypal_page"];?>" size="40"/>
            <a href="#" class="button" title="Upload image" onclick="return uploadImage(this);"><?php _e("Upload image", "donation_can"); ?></a>

            <br/><span class="description">(<?php _e("max size: 150 x 150 px", "donation_can");?>)</span>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row" valign="center"><?php _e("Header image (optional):", "donation_can");?></th>
        <td>
            <input type="text" class="regular-text" name="header_on_paypal_page" value="<?php echo $settings["header_on_paypal_page"];?>" size="40"/>
            <a href="#" class="button" title="Upload image" onclick="return uploadImage(this);"><?php _e("Upload image", "donation_can"); ?></a>
            <br/><span class="description">(<?php _e("max size: 750 x 90 px", "donation_can");?>)</span>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row" valign="center"><?php _e("Background color:", "donation_can");?></th>
        <td>
            <input type="text" class="regular-text" name="bg_on_paypal_page" value="<?php echo $settings["bg_on_paypal_page"];?>" size="40"/>
            <br/><span class="description">(<?php _e("A six digit HTML hex value (e.g. FF0000 for red)", "donation_can");?>)</span>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row" valign="center"><?php _e("Header background color:", "donation_can");?></th>
        <td>
            <input type="text" class="regular-text" name="header_bg_on_paypal_page" value="<?php echo $settings["header_bg_on_paypal_page"];?>" size="40"/>
            <br/><span class="description">(<?php _e("A six digit HTML hex value (e.g. FF0000 for red)", "donation_can");?>)</span>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row" valign="center"><?php _e("Header border color:", "donation_can");?></th>
        <td>
            <input type="text" class="regular-text" name="header_border_on_paypal_page" value="<?php echo $settings["header_border_on_paypal_page"];?>" size="40"/>
            <br/><span class="description">(<?php _e("A six digit HTML hex value (e.g. FF0000 for red)", "donation_can");?>)</span>
        </td>
    </tr>

    <tr valign="top">
        <td scope="row" valign="center" colspan="2">
            <input type="checkbox" name="ask_for_note" value="1" <?php if ($settings["ask_for_note"] == '1') { echo "checked"; }?>
                   onclick="togglePayPalNoteFields(this);">
            <label for="ask_for_note"><?php _e("Ask the visitor leave a note with her donation.", "donation_can");?></label>

        </td>
    </tr>
    <tr valign="top" id="paypal-note-field-row" <?php if ($settings["ask_for_note"] != 1) { echo "style=\"display:none;\""; }?>>
        <th scope="row" valign="center"><?php _e("Label for the note field:", "donation_can");?></th>
        <td>
            <input type="text" class="regular-text" name="note_field_label" value="<?php echo $settings["note_field_label"];?>" size="40"/>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row" valign="center"><?php _e("Ask for shipping address:", "donation_can");?></th>
        <td>
            <select name="require_shipping">
                <option value="0" <?php if ($settings["require_shipping"] == 0) { echo "selected"; }?>><?php _e("Prompt for an address, but do not require one.", "donation_can");?></option>
                <option value="1" <?php if ($settings["require_shipping"] == 1) { echo "selected"; }?>><?php _e("Do not prompt for an address.", "donation_can"); ?></option>
                <option value="2" <?php if ($settings["require_shipping"] == 2) { echo "selected"; }?>><?php _e("Prompt for an address, and require one.", "donation_can"); ?></option>
            </select>
        </td>
    </tr>

</table>