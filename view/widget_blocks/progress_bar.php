<?php
/*
Copyright (c) 2009-2011, Jarkko Laine.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
?>

<?php 
    if ($target == 0) {
        $percentage = 0;
    } else {
        $percentage = ($current / $target) * 100;

        if ($percentage > 100) {
            $percentage = 100;
        }
    }
?>
<div class="donation_meter progress-element ltr">
    <?php if ($goal_id == "__all__" && $multiple_currencies_in_use) : ?>
        <?php _e("Your donation goals use different currencies, so we cannot show an aggregated donation meter.", "donation-can"); ?>
    <?php else : ?>
	<?php if ($target == "") : ?>
            <?php echo $currency; ?> <?php echo $current; ?> <?php _e("raised", "donation_can");?>
	<?php else : ?> 
            <div class="donation_progress progress-meter">
                <div class="donation_progress_container progress-container">
                    <?php if (isset($element["direction"]) && $element["direction"] == "vertical") : ?>
                        <div class="donation_progress_bar progress-bar" style="height: <?php echo $percentage; ?>%;"></div>
                    <?php else : ?>
                        <div class="donation_progress_bar progress-bar" style="width: <?php echo $percentage; ?>%;"></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="progress-text">
                <?php if ($element["text-format"] == "%-and-total") : ?>
                    <span class="percentage"><?php echo intval($percentage); ?> %</span>
                    <span class="raised-label"><?php _e("Raised", "donation_can");?></span>
                    <span class="of-label"><?php _e("of", "donation_can");?></span>
                    <span class="currency"><?php echo $currency; ?></span>
                    <span class="goal"><?php echo $target; ?></span>
                    <span class="goal-label"><?php _e("Target", "donation_can");?></span>
                <?php else : ?>
                    <span class="currency"><?php echo $currency; ?></span>
                    <span class="raised"><?php echo $current; ?></span>
                    <span class="raised-label"><?php _e("Raised", "donation_can");?></span>
                    <span class="goal"><?php echo $target; ?></span>
                    <span class="goal-label"><?php _e("Target", "donation_can");?></span>
                <?php endif; ?>
            </div>
	<?php endif; ?>
    <?php endif; ?>
</div>