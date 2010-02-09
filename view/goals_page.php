<?php
/*
Copyright (c) 2009-2010, Jarkko Laine.

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

<div class="wrap">
	<h2>Donation Goals</h2>

	<form method="post" name="delete_cause" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="remove_cause" value=""/>
	</form>

	<table class="widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-cb check-column"><input type="checkbox"/></th>
				<th scope="col" class="manage-column goal-id-column"><?php _e("Goal ID", "donation_can");?></th>
				<th scope="col" class="manage-column"><?php _e("Goal info", "donation_can");?></th>
				<th scope="col" class="manage-column goal-sum-column"><?php _e("Goal", "donation_can");?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col" class="manage-column column-cb check-column"><input type="checkbox"/></th>
				<th scope="col" class="manage-column goal-id-column"><?php _e("Goal ID", "donation_can");?></th>
				<th scope="col" class="manage-column"><?php _e("Name", "donation_can");?></th>
				<th scope="col" class="manage-column"><?php _e("Goal", "donation_can");?></th>
			</tr>
		</tfoot>
		<tbody>
		
		<script type="text/javascript">
			function delete_goal(id) {
				var agree = confirm("<?php _e("Are you sure you want to delete goal", "donation_can");?> "+id+"?");
				if (agree) {
					document.delete_cause.remove_cause.value = id;
					document.delete_cause.submit();
				}
				return false;	
			}
		</script>		

	    <?php foreach ($causes as $id => $cause) : ?>
                <?php $currency = donation_can_get_currency_for_goal($cause); ?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox"/></th>
				<td><?php echo $id; ?></td>
				<td>
                                    <strong><?php echo $cause["name"]; ?></strong><br/>
                                    <?php echo $cause["description"]; ?>
                                    <div class="row-actions">
                                            <span class="edit"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&edit=<?php echo $id; ?>">Edit</a></span>
                                            <span class="delete"> | <a href="#" onclick="return delete_goal('<?php echo $id; ?>');"><?php _e("Delete");?></a></span>
                                    </div>
				</td>
				<td>
                                    <?php if ($cause["donation_goal"]) : ?>
					<?php echo $currency; ?> <?php echo $cause["donation_goal"];?>
                                    <?php endif; ?>
                                    <div class="row-actions">
                                            <a href="admin.php?page=donations.php&filter_goal=<?php echo $id; ?>"><?php _e("View donations", "donation_can");?></a>
                                    </div>
				</td>
			</tr>
		<?php endforeach; ?>
	
		</tbody>
	</table>
	
</div>
