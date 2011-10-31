<div class="donation-can-cause-selection">
    <?php
    $label = $options["select_cause_label"];
    if (!$label) {
        $label = __("Select cause", "donation_can");
    }
    ?>

    <?php echo $label; ?>:<br/>
    <select name="cause" onchange="donationCauseSelected(this);">
        <option value="">--<?php echo $label; ?>--</option>
        <?php foreach ($causes as $id => $cause) : ?>
            <option value="<?php echo $id; ?>"><?php echo $cause["name"]; ?></option>
        <?php endforeach; ?>
    </select>
</div>