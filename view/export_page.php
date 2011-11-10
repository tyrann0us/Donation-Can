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
<div class="wrap">

<div id="icon-tools" class="icon32"><br/></div>
<h2><?php _e("Export Data", "donation_can"); ?></h2>

<p><?php _e("On this page, you can download all Donation Can data from your blog to your own computer.", "donation_can");?></p>

<p><?php _e("Right-click on the file format you like, and use \"Save as...\" to choose the location for your file. Settings and styles are only available as XML.", "donation_can");?></p>

<table border="0" id="donation-can-export-download">
    <tr>
        <th><?php _e("Causes:", "donation_can");?></th>
        <td>
            <a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php'), "donation_can_ajax-export");?>&action=donation_can-export&type=causes&format=csv"><?php _e("CSV", "donation_can");?></a>
          | <a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php'), "donation_can_ajax-export");?>&action=donation_can-export&type=causes&format=xml"><?php _e("XML", "donation_can");?></a>
        </td>
    </tr>
    <tr>
        <th><?php _e("Donations:", "donation_can");?></th>
        <td>
            <a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php'), "donation_can_ajax-export");?>&action=donation_can-export&type=donations&format=csv"><?php _e("CSV", "donation_can");?></a>
          | <a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php'), "donation_can_ajax-export");?>&action=donation_can-export&type=donations&format=xml"><?php _e("XML", "donation_can");?></a>
        </td>
    </tr>
    <tr>
        <th><?php _e("Settings:", "donation_can");?></th>
        <td>
            <a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php'), "donation_can_ajax-export");?>&action=donation_can-export&type=settings&format=xml"><?php _e("XML", "donation_can");?></a>
        </td>
    </tr>
    <tr>
        <th><?php _e("Styles:", "donation_can");?></th>
        <td><a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php'), "donation_can_ajax-export");?>&action=donation_can-export&type=styles&format=xml"><?php _e("XML", "donation_can");?></a></td>
    </tr>
</table>

</div>