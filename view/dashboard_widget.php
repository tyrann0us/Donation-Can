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

<?php if (empty($goals)) : ?>

	<!-- Empty Slate: Shown if no goals have been set up -->
	<div class="donation_can_notice">
		You haven't set up any goals yet. Start by <a href="<?php bloginfo("url");?>/wp-admin/admin.php?page=add_goal.php">creating your first one.</a>
	</div>

<?php else : ?>

	<p class="sub">Latest Donations</p>

	<div class="table">
		<table>
			<tbody>
				<?php if (empty($donations)) : ?>
					<tr class="first">
						<td class="first">
							No donations yet.
						</td>
					</tr>
				<?php else : ?>
					<?php $first = true; ?>
					<?php foreach ($donations as $donation) : ?> 	
						<tr <?php if ($first) : $first = false; ?>class="first"<?php endif;?>>
							<td class="first"><?php echo $donation->time; ?></td>
							<td class="b"><?php echo $donation->amount; ?><br/><small style="color:red;">(-<?php echo $donation->fee; ?>)</small></td>
							<td class="last t"><span class="<?php if ($donation->payment_status == "Completed") { echo "approved"; } else { echo "waiting"; }?>"><?php echo $donation->payment_status; ?></span></td>
						</tr>			
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>


	<p class="sub">Goal Progress</p>

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
					}
				?>
				<tr <?php if ($first) : $first = false; ?>class="first"<?php endif; ?>>
					<td class="b" style="width: 30%;"><?php echo $goal["name"]; ?></td>
					<td>
						<div style="width: <?php echo $percent; ?>%; background-color: #559955;">&nbsp;</div>
					</td>
					<td class="b last" style="width: 25%;">$<?php echo $goal["collected"]; ?> / <?php echo $goal["donation_goal"]; ?></td>
				</tr>
			<?php endforeach; ?>
		
			<tr class="total">
				<td class="b">TOTAL</td>
				<?php
					if ($total_goal == 0) {
						$percent = 0;
					} else {
						$percent = $total_collected / $total_goal; 
					}
				?>
				<td><div style="width: <?php echo $percent; ?>%; background-color: #779955;">&nbsp;</div></td>
				<td class="b last" style="font-size: 16pt;">$<?php echo $total_collected; ?> / <?php echo $total_goal; ?></td>
			</tr>
		</table>	
	</div>
	
<?php endif; ?>

<?php if ($paypal_account == null || $paypal_account == "") : ?>
	<!-- Empty Slate (PayPal Settings not defined) -->
	<div class="donation_can_notice">
		You haven't set up your PayPal account information yet. <a href="<?php echo bloginfo("url"); ?>/wp-admin/admin.php?page=donation-can/model/settings/settings.php">Click here to do it now</a>.
	</div>
<?php else : ?>
	<p>Using PayPal account: <strong><?php echo $paypal_account; ?></strong></p>
<?php endif; ?>

<p>
	You are using <strong>Donation Can (version 1.0)</strong>. Check out the <a href="http://jarkkolaine.com/plugins/donation-can">plugin home page</a> for more information or to give feedback.
</p>
<p>
	Donation Can is free software. The plugin was developed to support Train for Humanity, a non-profit organization aiming to promote 
	humanity through everyday athletes' training efforts. If you enjoy the plugin, consider 
	<a href="http://trainforhumanity.org/author/jarkko">sponsoring my training</a> in the Train for Humanity project.
</p>

<p class="textright">
	<a href="<?php echo bloginfo("url"); ?>/wp-admin/admin.php?page=donation-can/model/settings/settings.php" class="button rbutton">Change Settings</a>
	<a href="<?php echo bloginfo("url");?>/wp-admin/admin.php?page=goals.php" class="button rbutton">Update Goals</a>
</p>
