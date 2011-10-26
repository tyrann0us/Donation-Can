<li class="widget-element text-element" id="<?php echo $id; ?>">
    <h3><?php _e("Text", "donation_can");?></h3>

    <div class="element-options" <?php if (!$show_options) : ?>style="display:none;"<?php endif; ?>>
        <p>
            <label for="text"><?php _e("Text:", "donation_can");?></label><br/>
            <textarea cols="10" rows="5" name="text"><?php echo $data["text"]; ?></textarea>
        </p>
    </div>

</li>


