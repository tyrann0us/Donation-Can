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

<div class="donation-can_donation-widget">
    <?php if ($show_title) : ?>
        <?php echo $before_title; ?><?php echo $title;?><?php echo $after_title; ?>
    <?php endif; ?>
	
    <?php if ($show_description) : ?>
        <div class="donation-can_goal-description">
            <?php echo $goal["description"];?>
        </div>
    <?php endif; ?>
	
    <?php if ($show_progress) : ?>
        <?php
            $target = $goal["donation_goal"];
            $current = $raised_so_far;
            require("progress_bar.php");
        ?>
    <?php endif; ?>

    <div class="donation-can_donation-form">
        <form action="<?php echo $action_url; ?>" method="post">
            <input type="hidden" name="cause" value="<?php echo $goal["id"]; ?>"/>
            <?php if ($donation_sums != null && count($donation_sums) > 0) : ?>
                <p>
                    Donate:
                    <select name="amount">
                        <?php foreach ($donation_sums as $sum) : ?>
                            <option value="<?php echo $sum;?>"><?php echo $currency; ?> <?php echo $sum; ?></option>
                        <?php endforeach; ?>
                        <?php if ($goal["allow_freeform_donation_sum"]) : ?>
                            <option value=""><?php _e("Other (enter amount on next page)", "donation_can");?></option>
                        <?php endif; ?>
                    </select>
                </p>
            <?php endif; ?>

            <input type="image" name="submit" border="0" src="http://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" alt="PayPal - The safer, easier way to pay online"/>
        </form>

        <?php if ($show_donations) : ?>
            <?php require_donation_can_view('donation_list', array("donation_strings" => $donation_strings)); ?>
        <?php endif; ?>

        <?php if ($show_back_link) : ?>
            <div class="donation-can_backlink"><?php printf(__("Powered by %s."), "<a href=\"http://treehouseapps.com/donation-can\">Donation Can</a>"); ?></div>
        <?php endif; ?>
    </div>
    
</div>