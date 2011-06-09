<li class="widget-element submit-element" id="<?php echo $id; ?>">
    <div class="widget-element-title">
        <h4><?php _e("Submit Button", "donation_can");?></h4>
    </div>

    <div class="element-options" <?php if (!$show_options) : ?>style="display:none;"<?php endif; ?>>
        <p>
            <label for="button-image"><?php _e("Button image URL (leave empty for default):", "donation_can");?></label><br/>
            <input type="text" name="button-image" class="widefat" value="<?php echo $data["button-image"];?>"/>
        </p>

        <p>
            <a href="#" class="button" title="Upload image" onclick="uploadImage(this);"><?php _e("Upload image", "donation_can"); ?></a>
        </p>


        <?php if ($data["button-image"]) : ?>
            <hr/>
            Current image:<br/>
            <img src="<?php echo $data["button-image"];?>"/>
        <?php endif; ?>

    </div>

</li>
