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

<h1>Export</h1>

<p><?php _e("Include in dump:", "donation_can");?></p>

<ul>
    <li><a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php'), "donation_can_ajax-export");?>&action=donation_can-export&type=causes"><?php _e("Causes", "donation_can");?></a></li>
    <li><a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php'), "donation_can_ajax-export");?>&action=donation_can-export&type=donations"><?php _e("Donations", "donation_can");?></a></li>
    <li><a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php'), "donation_can_ajax-export");?>&action=donation_can-export&type=settings"><?php _e("Settings", "donation_can");?></a></li>
    <li><a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php'), "donation_can_ajax-export");?>&action=donation_can-export&type=styles"><?php _e("Styles", "donation_can");?></a></li>
</ul>

