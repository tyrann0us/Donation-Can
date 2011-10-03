<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<script type="text/javascript">
    function showOtherFormat(element) {
        var value = (jQuery(element).val() == "radio");

        var parent = jQuery(element).closest(".element-options");
        var otherFormatP = jQuery("p.other-format", parent);

        if (value == true) {
            otherFormatP.show();
        } else {
            otherFormatP.hide();
        }
    }
</script>

<li class="widget-element donation-options-element" id="<?php echo $id; ?>">
    <div class="widget-element-title">
        <h3><?php _e("Donation Options", "donation_can");?></h3>
    </div>

    <div class="element-options" <?php if (!$show_options) : ?>style="display:none;"<?php endif; ?>>
        <p>
            <label for="list-format">List format:</label><br/>
            <select name="list-format" onchange="showOtherFormat(this);">
                <option value="list" <?php if (!isset($data['list-format']) || $data['list-format'] == "list") echo "selected"; ?>>Drop down list</option>
                <option value="buttons" <?php if (isset($data['list-format']) && $data['list-format'] == "buttons") echo "selected"; ?>>Options as links</option>
                <option value="radio" <?php if (isset($data['list-format']) && $data['list-format'] == "radio") echo "selected"; ?>>Options as radio buttons</option>
            </select>
        </p>


        <?php
        $other_format_visible = (isset($data["list-format"]) && $data["list-format"] == "radio");
        ?>
        <p class="other-format" <?php if (!$other_format_visible) { echo "style=display:none;"; }?>>
            <input type="checkbox" name="other-format" value="text-field" <?php if (isset($data["other-format"]) && $data["other-format"] == "text-field") { echo "checked"; } ?>/>
            <label for="other-format"><?php _e("Show text field for entering a custom donation sum", "donation_can");?></label><br/>
        </p>
    </div>

</li>
