<div class="submit-donation" <?php if ($cause_id == "__all__") { echo "style=\"display:none;\""; } ?>>
    <?php
    $url = "http://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif";

    if (isset($data["button-image"]) && $data["button-image"] != "") {
        $url = $data["button-image"];
    }
    ?>

    <input type="image" name="submit" border="0" src="<?php echo $url; ?>" alt="PayPal - The safer, easier way to pay online"/>
</div>