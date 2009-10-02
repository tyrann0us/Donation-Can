<?php
/*
Copyright (c) 2009, Jarkko Laine.

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
	<h2>Donations</h2>

<div class="tablenav">
	<form method="post" name="filter_donations" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<div class="alignleft actions">
			<select class="postform" name="filter_goal">
				<option value="0"><?php _e("View all goals", "donation_can");?></option>
				<?php foreach ($goals as $id => $goal) : ?>
					<option value="<?php echo $id; ?>" <?php if ($filter_goal == $id) { echo "selected"; }?>><?php echo $goal["name"];?></option>
				<?php endforeach; ?> 
			</select>
			<input type="submit" class="button-secondary" value="Filter"/>
		</div>
		<div class="tablenav-pages">
		<?php 
			$url =  get_bloginfo("url") . "/wp-admin/admin.php?page=donations.php";
			
		?>
			<span class="displaying-num">Displaying <?php echo $start_index; ?>-<?php echo $start_index + count($donations); ?> of <?php echo $total_donations; ?></span>
			
			<?php if ($total_pages > 1) : ?>
				<?php if ($page > 0) : ?> 
					<a class="previous page-numbers" href="<?php echo $url; ?>&paged=<?php echo $page - 1; ?>">&laquo;</a>
				<?php endif; ?>
			
				<?php for ($i = 0; $i < $total_pages; $i++) : ?>
					<?php if ($i == $page) : ?>
						<span class="page-numbers current"><?php echo $i + 1; ?></span>
					<?php else : ?>
						<a class="page-numbers" href="<?php echo $url; ?>&paged=<?php echo $i; ?>"><?php echo $i + 1; ?></a>
					<?php endif; ?>
				<?php endfor; ?>
			
				<?php if ($page < $total_pages - 1) : ?>
					<a class="next page-numbers" href="<?php echo $url; ?>&paged=<?php echo ($page + 1);?>">&raquo;</a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</form>
	
</div>

	<table class="widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-cb check-column"><input type="checkbox"/></th>
				<th scope="col" class="manage-column"><?php _e("Date");?></th>
				<th scope="col" class="manage-column goal-id-column"><?php _e("Goal ID", "donation_can");?></th>
				<th scope="col" class="manage-column"><?php _e("Donor", "donation_can");?></th>
				<th scope="col" class="manage-column goal-sum-column"><?php _e("Donation Sum", "donation_can");?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col" class="manage-column column-cb check-column"><input type="checkbox"/></th>
				<th scope="col" class="manage-column"><?php _e("Date");?></th>
				<th scope="col" class="manage-column goal-id-column"><?php _e("Goal ID", "donation_can");?></th>
				<th scope="col" class="manage-column"><?php _e("Donor", "donation_can");?></th>
				<th scope="col" class="manage-column goal-sum-column"><?php _e("Donation Sum", "donation_can");?></th>
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

		    <?php foreach ($donations as $donation) : ?>
				<tr>
					<th scope="row" class="check-column"><input type="checkbox"/></th>
					<td><?php echo $donation->time; ?></td>
					<td><?php echo $donation->cause_code; ?></td>
					<td>
						<strong><?php echo $donation->payer_name; ?></strong><br/>
						<?php echo $donation->payer_email; ?>
					</td>
					<td>
						USD <?php echo $donation->amount ;?><br/>
						<?php echo $donation->fee; ?>
					</td>
				</tr>
			<?php endforeach; ?>
	
		</tbody>
	</table>
	
</div>
