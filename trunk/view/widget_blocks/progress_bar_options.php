<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<li class="widget-element progress-element" id="<?php echo $id; ?>">
    <h3><?php _e("Progress", "donation_can");?></h3>

    <div class="element-options" <?php if (!$show_options) : ?>style="display:none;"<?php endif; ?>>
        <p>
            <label for="direction"><?php _e("Progress bar orientation:", "donation_can");?></label><br/>
            <select name="direction">
                <option value="vertical" <?php if (isset($data['direction']) && $data['direction'] == "vertical") echo "selected"; ?>><?php _e("Vertical", "donation_can");?></option>
                <option value="horizontal" <?php if (!isset($data['direction']) || $data['direction'] == "horizontal") echo "selected"; ?>><?php _e("Horizontal", "donation_can");?></option>
            </select>
        </p>

        <p>
            <label for="text-format"><?php _e("Text format:", "donation_can");?></label><br/>
            <input class="widefat" type="text" name="text-format" value="<?php echo htmlentities($data['text-format'], ENT_COMPAT | ENT_HTML401, "UTF-8");?>"/>
        </p>
        <p>
            <em><?php _e("Use the following placeholders to mark variable data:<br/>%CURRENCY%, %TARGET%, %CURRENT%, %PERCENTAGE%", "donation_can");?></em>
        </p>
    </div>
    
</li>


