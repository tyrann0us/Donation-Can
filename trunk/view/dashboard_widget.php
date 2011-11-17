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

<?php global $wp_rewrite; ?>
<?php if (!$wp_rewrite->using_permalinks()) : ?>
    <!-- Error: Permalink structure not compatible with PayPal -->
    <div class="donation_can_notice">
        <?php _e("You haven't set up your WordPress permalink structure. Donation Can will not be able to receive payment notifications from PayPal.", "donation_can");?><br/>
        <a href="<?php echo admin_url("options-permalink.php"); ?>"><?php _e("Fix permalink settings now.", "donation_can");?></a>
    </div>
<?php endif; ?>
<?php if (empty($goals)) : ?>

	<!-- Empty Slate: Shown if no goals have been set up -->
	<div class="donation_can_notice">
            <?php _e("You haven't set up any causes yet.", "donation_can");?>
            <a href="<?php echo admin_url("admin.php?page=donation_can_add_goal.php");?>"><?php _e("Start by creating your first one.", "donation_can");?></a>
	</div>

<?php else : ?>

    <p class="sub"><?php _e("Latest Donations", "donation_can");?></p>

    <div class="table">
        <table>
            <tbody>
            <?php if (empty($donations)) : ?>
                <tr class="first">
                    <td class="first">
                        <?php _e("No donations yet.", "donation_can");?>
                    </td>
                </tr>
            <?php else : $first = true; ?>
                <?php foreach ($donations as $donation) : ?>
                    <?php $currency = donation_can_get_currency_for_goal($goals[$donation->cause_code]); ?>
                    <tr <?php if ($first) : $first = false; ?>class="first"<?php endif;?>>
                        <td class="first date"><?php echo donation_can_nicedate($donation->time); ?></td>
                        <td class="goal"><?php echo sprintf(__("To '%s'", "donation_can"), $goals[$donation->cause_code]["name"]); ?></td>
                        <td class="b"><?php echo donation_can_format_money($currency, $donation->amount); ?><br/><small style="color:red;">(-<?php echo donation_can_format_money(null, $donation->fee); ?>)</small></td>

                        <?php
                        $payment_status = $donation->payment_status;
                        if ($payment_status == "Completed") {
                            $payment_status = __("Completed", "donation_can");
                        } else if ($payment_status == "Pending") {
                            $payment_status = __("Pending", "donation_can");
                        }
                        ?>
                        <td class="last t"><span class="<?php if ($donation->payment_status == "Completed") { echo "approved"; } else { echo "waiting"; }?>"><?php echo $payment_status; ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p class="sub"><?php _e("Goal Progress", "donation_can");?></p>

    <div class="table">
        <table>
            <?php $first = true; $total_goal = 0; $total_collected = 0; ?>
            <?php foreach ($goals as $goal) : ?>
                <?php
                    $total_goal += $goal["donation_goal"];
                    $total_collected += $goal["collected"];

                    if ($goal["donation_goal"] == 0) {
                        $percent = 0;
                    } else {
                        $percent = $goal["collected"] / $goal["donation_goal"] * 100;
                        if ($percent > 100) {
                            $percent = 100;
                        }
                    }

                    $currency = donation_can_get_currency_for_goal($goal);
                ?>
                <tr <?php if ($first) : $first = false; ?>class="first"<?php endif; ?>>
                    <td class="b" style="width: 30%;"><?php echo $goal["name"]; ?></td>
                    <td style="width: 45%;">
                        <div style="width: <?php echo $percent; ?>%; background-color: #559955;">&nbsp;</div>
                    </td>
                    <td class="b last" style="width: 25%;"><?php echo donation_can_format_money($currency, $goal["collected"]); ?>
                        <?php if ($goal["donation_goal"]) : ?>
                            / <?php echo donation_can_format_money(null, $goal["donation_goal"]); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr class="total">
                <td class="b"><?php _e("TOTAL", "donation_can");?></td>
                <?php if (donation_can_has_multiple_currencies_in_use()) : ?>
                    <td class="b last" colspan="2"><small style="color: rgb(230, 111, 0);">Multiple currencies selected, cannot count total.</small></td>
                <?php else: ?>
                    <?php
                        if ($total_goal == 0) {
                            $percent = 0;
                        } else {
                            $percent = $total_collected / $total_goal;
                        }
                    ?>
                    <td><div style="width: <?php echo $percent; ?>%; background-color: #779955;">&nbsp;</div></td>
                    <td class="b last" style="font-size: 16pt;"><?php echo donation_can_format_money($currency, $total_collected); ?> / <?php echo donation_can_format_money(null, $total_goal); ?></td>
                <?php endif; ?>
            </tr>
        </table>
    </div>
	
<?php endif; ?>

<?php if ($paypal_account == null || $paypal_account == "") : ?>
    <!-- Empty Slate (PayPal Settings not defined) -->
    <div class="donation_can_notice">
        <?php _e("You haven't set up your PayPal account information yet.", "donation_can");?>
        <a href="<?php echo admin_url("admin.php?page=donation_can_general_settings.php");?>"><?php _e("Click here to do it now.", "donation_can");?></a>
    </div>
<?php else : ?>
    <p><?php _e("PayPal account:", "donation_can");?> <strong><?php echo $paypal_account; ?></strong></p>
<?php endif; ?>

<p>
    <?php
        $donation_can_version_string = __("You are using <strong><a href=\"%URL\">Donation Can</a> %VERSION.</strong>", "donation_can");
        $donation_can_version_string = str_replace("%URL", "http://treehouseapps.com/donation-can/", $donation_can_version_string);
        $donation_can_version_string = str_replace("%VERSION", $version, $donation_can_version_string);

        echo $donation_can_version_string;
    ?>
</p>

<p class="textright">
    <a href="<?php echo admin_url("admin.php?page=donation_can_general_settings.php");?>" class="button rbutton"><?php _e("Change Settings", "donation_can");?></a>
    <a href="<?php echo admin_url("admin.php?page=donation_can_goals.php");?>" class="button rbutton"><?php _e("Update Goals", "donation_can");?></a>
</p>