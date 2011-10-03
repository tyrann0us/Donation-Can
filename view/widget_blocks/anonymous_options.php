<li class="widget-element anonymous-element" id="<?php echo $id; ?>">
    <div class="widget-element-title">
        <h3><?php _e("Anonymous Donation Checkbox", "donation_can");?></h3>
    </div>

    <div class="element-options" <?php if (!$show_options) : ?>style="display:none;"<?php endif; ?>>
        <p>
            <label for="prompt">Prompt:</label><br/>
            <input type="text" class="widefat" name="prompt" value="<?php echo $data["prompt"];?>"/>
        </p>
    </div>

</li>
