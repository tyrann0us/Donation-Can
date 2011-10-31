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

<script type="text/javascript">
    function delete_goal(id, name) {
        var agree = confirm("<?php _e("Are you sure you want to delete cause '", "donation_can");?>" + name + "'?");
        if (agree) {
            document.delete_cause.remove_cause.value = id;
            document.delete_cause.submit();
        }
        return false;
    }

    function confirm_reset(id, name) {
        var agree = confirm("<?php _e("Are you sure you want to reset the donation counter for cause '", "donation_can");?>" + name + "'?'");
        if (agree) {
            document.reset_cause.reset.value = id;
            document.reset_cause.submit();
        }
        return false;
    }
</script>

<div class="wrap">
    <h2><?php _e("Donation Causes", "donation_can"); ?></h2>

    <?php if ($causes == null || count($causes) == 0) : ?>
    
        <!-- Blank slate -->
        <div class="donation-can-blank-slate">
            <?php _e("Add a new cause to get started with collecting donations.", "donation-can"); ?>
        </div>

    <?php else : ?>

        <form method="post" name="delete_cause" action="<?php echo admin_url("admin.php?page=donation_can_goals.php");?>">
            <input type="hidden" name="remove_cause" value=""/>
            <?php wp_nonce_field('donation_can-remove_cause'); ?>
        </form>

        <form method="post" name="reset_cause" action="<?php echo admin_url("admin.php?page=donation_can_goals.php");?>">
            <input type="hidden" name="reset" value=""/>
            <?php wp_nonce_field('donation_can-reset_cause'); ?>
        </form>

        <table class="widefat fixed" cellspacing="0" style="margin-top: 8px;">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column"><!--<input type="checkbox"/>--></th>
                    <th scope="col" class="manage-column column-title"><?php _e("Cause", "donation_can");?></th>
                    <th scope="col" class="manage-column column-author"><?php _e("Added by", "donation_can");?></th>
                    <th scope="col" class="manage-column column-categories"><?php _e("Goal", "donation_can");?></th>
                    <th scope="col" class="manage-column column-tags"><?php _e("Collected", "donation_can");?></th>
                    <th scope="col" class="manage-column column-date"><?php _e("Date", "donation_can");?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column"><!--<input type="checkbox"/>--></th>
                    <th scope="col" class="manage-column column-title"><?php _e("Cause", "donation_can");?></th>
                    <th scope="col" class="manage-column column-author"><?php _e("Added by", "donation_can");?></th>
                    <th scope="col" class="manage-column column-categories"><?php _e("Goal", "donation_can");?></th>
                    <th scope="col" class="manage-column column-tags"><?php _e("Collected", "donation_can");?></th>
                    <th scope="col" class="manage-column column-date"><?php _e("Date", "donation_can");?></th>
                </tr>
            </tfoot>

            <tbody>
                <!-- List causes -->
                <?php foreach ($causes as $id => $cause) : ?>
                    <?php $currency = donation_can_get_currency_for_goal($cause); ?>
                    <tr id="cause-row-<?php echo $id; ?>">
                        <th scope="row" class="check-column"><!--<input type="checkbox"/>--></th>
                        <td>
                            <a class="row-title" href="<?php echo admin_url("admin.php?page=donation_can_goals.php&edit=" . $id); ?>"><?php echo $cause["name"]; ?></a><br/>
                            <?php echo $cause["description"]; ?>

                            <div class="row-actions">
                                <span class="edit"><a href="<?php echo admin_url("admin.php?page=donation_can_goals.php&edit=" . $id); ?>"><?php _e("Edit", "donation_can");?></a></span>
                                <span class="delete"> | <a href="#" onclick="return delete_goal('<?php echo $id; ?>', '<?php echo $cause['name'];?>');"><?php _e("Delete", "donation_can");?></a></span>
                                <span class="view-donations"> | <a href="<?php echo admin_url("admin.php?page=donation_can_donations.php&filter_goal=" . $id); ?>"><?php _e("View donations", "donation_can");?></a></span>
                                <span class="reset-counter"> | <a onclick="return confirm_reset('<?php echo $id; ?>', '<?php echo $cause['name'];?>');" href="#"><?php _e("Reset", "donation_can");?></a></span>
                            </div>
                        </td>
                        <td>
                            <?php echo $cause["author"]; ?>
                        </td>
                        <td>
                            <?php if ($cause["donation_goal"]) : ?>
                                <?php echo $currency . " " . $cause["donation_goal"];?>
                            <?php else : ?>
                                <?php _e("No goal", "donation-can"); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo $currency . " ". donation_can_get_total_raised_for_cause($id); ?>
                            <?php if (donation_can_goal_has_been_reset($id)) : ?>
                            <br/><span class="total-including-resets">(<?php echo $currency . " " . donation_can_get_total_raised_for_cause($id, true); ?>)</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo $cause["created_at"]; ?>
                        </td>
                    </tr>
		<?php endforeach; ?>
    	
            </tbody>
        </table>
        
    <?php endif; ?>

    <p>
        <a class="button" href="<?php echo admin_url("admin.php?page=donation_can_add_goal.php");?>"><?php _e("Add cause", "donation_can");?></a>
    </p>
</div>