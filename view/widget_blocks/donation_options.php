<div class="donation-options">

    <?php if ($donation_sums != null && count($donation_sums) > 0) : ?>
        <span class="donation-callout"><?php _e("Choose donation amount:", "donation_can"); ?></span>

        <?php if ($element["list-format"] == "buttons") : ?>

            <script type="text/javascript">
                function submitDonation(element, sum) {
                    var form = jQuery(element).closest('form');
                    form.find("input[name=amount]").val(sum);
                    form.submit();
                }
            </script>

            <div class="donation-button-list">
                <input type="hidden" name="amount"/>

                <?php foreach ($donation_sums as $sum) : ?>
                    <a class="button" onclick="return submitDonation(this, <?php echo $sum; ?>);">Donate <?php echo $currency; ?> <?php echo $sum; ?></a>
                <?php endforeach; ?>
                <?php if ($goal["allow_freeform_donation_sum"]) : ?>
                    <a class="button" onclick="return submitDonation(this, '');"><?php _e("Donate other amount", "donation_can");?></a>
                <?php endif; ?>
            </div>

        <?php elseif ($element["list-format"] == "radio") : $first = true; ?>

            <script type="text/javascript">
                function showOtherTextField(element, value) {
                    var parent = jQuery(element).closest(".donation-radio-button-list");
                    var span = jQuery("span.amount-span", parent);

                    var textField = jQuery("input.amount-text-field", span);

                    if (value == true) {
                        textField.attr("name", "amount");
                        span.show();
                    } else {
                        textField.removeAttr("name");
                        span.hide();
                    }
                }
            </script>

            <div class="donation-radio-button-list">

                <?php foreach ($donation_sums as $sum) : ?>
                    <div class="radio-button"><input type="radio" name="amount" onclick="showOtherTextField(this, false);" value="<?php echo $sum;?>" <?php if ($first) { echo "checked"; $first = false; } ?>> <label for="amount"><?php echo $currency; ?> <?php echo $sum; ?></label></div>
                <?php endforeach; ?>
                <?php if ($goal["allow_freeform_donation_sum"]) : ?>
                    <?php if ($element["other-format"] == "text-field") : ?>
                        <div class="radio-button"><input type="radio" name="amount" value="" onclick="showOtherTextField(this, true);"> <?php _e("Other", "donation_can");?><span class="amount-span" style="display:none;">: <?php echo $currency; ?> <input type="text" class="amount-text-field"/></span></div>
                    <?php else : ?>
                        <div class="radio-button"><input type="radio" name="amount" value=""> <?php _e("Other", "donation_can");?></div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>


        <?php else : ?>

            <select name="amount">
                <?php foreach ($donation_sums as $sum) : ?>
                    <option value="<?php echo $sum;?>"><?php echo $currency; ?> <?php echo $sum; ?></option>
                <?php endforeach; ?>
                <?php if ($goal["allow_freeform_donation_sum"]) : ?>
                    <option value=""><?php _e("Other", "donation_can");?></option>
                <?php endif; ?>
            </select>

        <?php endif; ?>
    <?php endif; ?>
</div>