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
    $goal_name = __("Donations", "donation_can");
    if ($filter_goal) {
        $selected_goal = $goals[$filter_goal];
        $goal_name = sprintf(__("Donations to \"%s\"", "donation_can"), $selected_goal["name"]);
    }

    $url = admin_url("admin.php?page=donation_can_donations.php");
?>

<div class="wrap">
    <h2><?php echo $goal_name; ?></h2>

    <form method="post" name="delete_donation" action="<?php echo $url; ?>">
        <input type="hidden" name="remove_donation" value=""/>
    </form>

    <div class="tablenav">
	<form method="get" name="filter_donations" action="<?php echo $url; ?>">
            <input type="hidden" name="page" value="donation_can_donations.php"/>
            <div class="alignleft actions">
                <select class="postform" name="filter_goal">
                    <option value=""><?php _e("View all goals", "donation_can");?></option>
                    <?php foreach ($goals as $id => $goal) : ?>
                        <option value="<?php echo $id; ?>" <?php if ($filter_goal == $id) { echo "selected"; }?>><?php echo $goal["name"];?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" class="button-secondary" value="<?php _e("Filter", "donation_can");?>"/>
            </div>

            <div class="tablenav-pages">
                <span class="displaying-num"><?php echo sprintf(__("Displaying %d-%d of %d", "donation_can"), $start_index, $start_index + count($donations), $total_donations); ?></span>

                <?php if ($total_pages > 1) : ?>

                    <span class="pagination-links">
                        <a class="first-page <?php if ($page <= 1) { echo "disabled"; } ?>" title="<?php _e("Go to the first page");?>" href="<?php echo $url; ?>&paged=1">&laquo;</a>
                        <a class="prev-page <?php if ($page <= 1) { echo "disabled"; } ?>" title="<?php _e("Go to the previous page");?>" href="<?php echo $url;?>&paged=<?php echo $page - 1; ?>">&lsaquo;</a>
                
                        <span class="paging-input"><input class="current-page" title="<?php _e("Current page");?>" type="text" name="paged" value="<?php echo $page; ?>" size="2"> of <span class="total-pages"><?php echo $total_pages; ?></span></span>

                        <a class="next-page <?php if ($page >= $total_pages) { echo "disabled"; } ?>" title="<?php _e("Go to the next page"); ?>" href="<?php echo $url; ?>&paged=<?php echo $page + 1; ?>">&rsaquo;</a>
                        <a class="last-page <?php if ($page >= $total_pages) { echo "disabled"; } ?>" title="<?php _e("Go to the last page"); ?>" href="<?php echo $url; ?>&paged=<?php echo $total_pages; ?>">&raquo;</a>
                    </span>
                
                <?php endif; ?>
            </div>
	</form>	
    </div>

    <!--
    <div id="donation-stats">
        <div class="stats-box">
            <div class="graph">
                <?php
                    if ($selected_goal["donation_goal"] == 0) {
                        $percent = 0;
                    } else {
                        $percent = $selected_goal["collected"] / $selected_goal["donation_goal"] * 100;
                    }

                    $currency = donation_can_get_currency_for_goal($selected_goal);
                ?>                
                <div class="donation-progress">
                    <div style="width: <?php echo $percent; ?>%; background-color: #559955;">&nbsp;</div>
                </div>
                <div class="progress-as-text">
                    <?php echo $currency . " " . $selected_goal["collected"] . " / " . $selected_goal["donation_goal"] . " collected"; ?>
                </div>
            </div>
        </div>

 <div class="stats-box">
            <div class="graph">Graph goes here</div>
            <div class="title"><?php _e("Daily Progress", "donation-can");?></div>
        </div>

        <div class="stats-box">
            <div class="graph">Graph goes here</div>
            <div class="title"><?php _e("Numeric stats?", "donation-can");?></div>
        </div>
    </div>
       -->

    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-cb check-column"></th>
                <th scope="col" class="manage-column"><?php _e("Date", "donation_can");?></th>
                <th scope="col" class="manage-column goal-id-column"><?php _e("Goal", "donation_can");?></th>
                <th scope="col" class="manage-column"><?php _e("Donation type", "donation_can");?></th>
                <th scope="col" class="manage-column"><?php _e("From", "donation_can");?></th>
                <th scope="col" class="manage-column goal-sum-column"><?php _e("Amount", "donation_can");?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th scope="col" class="manage-column column-cb check-column"></th>
                <th scope="col" class="manage-column"><?php _e("Date", "donation_can");?></th>
                <th scope="col" class="manage-column goal-id-column"><?php _e("Goal", "donation_can");?></th>
                <th scope="col" class="manage-column"><?php _e("Donation type");?></th>
                <th scope="col" class="manage-column"><?php _e("From", "donation_can");?></th>
                <th scope="col" class="manage-column goal-sum-column"><?php _e("Amount", "donation_can");?></th>
            </tr>
        </tfoot>
        <tbody>
            <script type="text/javascript">
                function do_delete_donation(id) {
                    var agree = confirm("<?php _e("Are you sure you want to delete donation", "donation_can");?> "+id+"?");
                    if (agree) {
                        document.delete_donation.remove_donation.value = id;
                        document.delete_donation.submit();
                    }
                    return false;
                }
            </script>

            <?php foreach ($donations as $donation) : ?>
                <?php $currency = donation_can_get_currency_for_goal($goals[$donation->cause_code]); ?>
                <tr>
                    <th scope="row" class="check-column"></th>
                    <td>
                        <?php echo donation_can_nicedate(mysql2date(__('Y/m/d g:i:s A'), $donation->time)); ?>
                        <div class="row-actions">
                            <span class="delete"><a href="#" onclick="return do_delete_donation('<?php echo $donation->id; ?>');"><?php _e("Delete", "donation_can");?></a></span>
                        </div>
                    </td>
                    <td><?php echo $goals[$donation->cause_code]["name"]; ?></td>
                    <td><?php echo ($donation->offline ? "Offline" : "PayPal"); ?></td>
                    <td>                        
                        <strong><?php echo $donation->payer_name; ?></strong> <?php if ($donation->anonymous) { ?><span class="anonymous">(anonymous)</span><?php } ?><br/>
                        <?php echo $donation->payer_email; ?>

                    </td>
                    <td>
                        <?php echo $currency; ?> <?php echo $donation->amount ;?><br/>
                        <span class="donation_can-fee">(-<?php echo $donation->fee; ?>)</span>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>
