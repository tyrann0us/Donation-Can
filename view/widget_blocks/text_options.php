<li class="widget-element text-element" id="<?php echo $id; ?>">
    <h3>Text</h3>

    <div class="element-options" <?php if (!$show_options) : ?>style="display:none;"<?php endif; ?>>
        <p>
            <label for="text">Text:</label><br/>
            <textarea cols="10" rows="5" name="text"><?php echo $data["text"]; ?></textarea>
        </p>
    </div>

</li>


