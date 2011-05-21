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
            <input type="text" name="text-format" value="<?php echo $data['text-format'];?>"/>
            <br/>
            <em>Use the following placeholders to mark variable data: %CURRENCY%, %TARGET%, %CURRENT%, %PERCENTAGE%</em>
        </p>
    </div>
    
</li>


