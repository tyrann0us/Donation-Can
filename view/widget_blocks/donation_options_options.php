<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<li class="widget-element donation-options-element" id="<?php echo $id; ?>">
    <div class="widget-element-title">
        <h3><?php _e("Donation Options", "donation_can");?></h3>
    </div>

    <div class="element-options" <?php if (!$show_options) : ?>style="display:none;"<?php endif; ?>>
        <p>
            <label for="list-format"><?php _e("List format:", "donation_can");?></label><br/>
            <select name="list-format" onchange="showOtherFormat(this);">
                <option value="list" <?php if (!isset($data['list-format']) || $data['list-format'] == "list") echo "selected"; ?>><?php _e("Drop down list", "donation_can");?></option>
                <option value="buttons" <?php if (isset($data['list-format']) && $data['list-format'] == "buttons") echo "selected"; ?>><?php _e("Options as links", "donation_can");?></option>
                <option value="radio" <?php if (isset($data['list-format']) && $data['list-format'] == "radio") echo "selected"; ?>><?php _e("Options as radio buttons", "donation_can");?></option>
            </select>
        </p>


        <?php
        $other_format_visible = (isset($data["list-format"]) && $data["list-format"] == "radio");
        ?>
        <p class="other-format" <?php if (!$other_format_visible) { echo "style=display:none;"; }?>>
            <input type="checkbox" name="other-format" value="text-field" <?php if (isset($data["other-format"]) && $data["other-format"] == "text-field") { echo "checked"; } ?>/>
            <label for="other-format"><?php _e("Show text field for entering a custom donation sum.", "donation_can");?></label><br/>
        </p>
    </div>

</li>
