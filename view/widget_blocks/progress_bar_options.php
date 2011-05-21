<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<li class="widget-element progress-element" id="<?php echo $id; ?>">
    <h3>Progress</h3>

    <div class="element-options" <?php if (!$show_options) : ?>style="display:none;"<?php endif; ?>>
        <p>
            <label for="direction">Progress bar orientation:</label><br/>
            <select name="direction">
                <option value="vertical" <?php if (isset($data['direction']) && $data['direction'] == "vertical") echo "selected"; ?>>Vertical</option>
                <option value="horizontal" <?php if (!isset($data['direction']) || $data['direction'] == "horizontal") echo "selected"; ?>>Horizontal</option>
            </select>
        </p>

        <p>
            <label for="text-format">Text format:</label><br/>
            <select name="text-format">
                <option value="%-and-total" <?php if (isset($data['text-format']) && $data['text-format'] == "%-and-total") echo "selected"; ?>>Percentage and total</option>
                <option value="collected-and-total" <?php if (!isset($data['text-format']) || $data['text-format'] == "collected-and-total") echo "selected"; ?>>Amount raised and total</option>
            </select>
        </p>
    </div>
    
</li>


