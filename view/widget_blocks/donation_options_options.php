<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>


<li class="widget-element donation-options-element" id="<?php echo $id; ?>">
    <div class="widget-element-title">
        <h4><?php _e("Donation Options", "donation_can");?></h4>
    </div>

    <div class="element-options" <?php if (!$show_options) : ?>style="display:none;"<?php endif; ?>>
        <p>
            <label for="list-format">List format:</label><br/>
            <select name="list-format">
                <option value="list" <?php if (!isset($data['list-format']) || $data['list-format'] == "list") echo "selected"; ?>>Drop down list</option>
                <option value="buttons" <?php if (isset($data['list-format']) && $data['list-format'] == "buttons") echo "selected"; ?>>Options as links</option>
                <option value="radio" <?php if (isset($data['list-format']) && $data['list-format'] == "radio") echo "selected"; ?>>Options as radio buttons</option>
            </select>
        </p>
        
    </div>

</li>
