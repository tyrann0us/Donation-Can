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

<?php 
	if ($target == 0) {
		$percentage = 0;
	} else {
		$percentage = ($current / $target) * 100; 
	}
?>
<div class="donation_meter ltr">
	<?php if ($target == "") : ?>
		$<?php echo $current; ?> <?php _e("raised", "donation_can");?>
	<?php else : ?> 
		<div class="donation_progress">
			<div class="donation_progress_container">
				<div class="donation_progress_bar" style="width: <?php echo $percentage; ?>%;"></div>
			</div>
		</div>
		$<?php echo $current; ?> / $<?php echo $target; ?> <?php _e("raised", "donation_can");?>
	<?php endif; ?>
</div>