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

function donation_can_widget_styles_menu() {
    
    // Remove style
    if (isset($_POST["remove_style"]) && check_admin_referer('donation_can-remove_style')) {
        $id = attribute_escape($_POST["remove_style"]);
        if (donation_can_delete_widget_style($id)) {
            render_user_notification(__("Deleted style:", "donation_can") . " " . $id);
        }
    } else if (isset($_POST["clone_style"]) && check_admin_referer('donation_can-clone_style')) {
        $id = attribute_escape($_POST["clone_style"]);
        $new_name = attribute_escape($_POST["new_name"]);
        if (donation_can_clone_widget_style($id, $new_name)) {
            render_user_notification(__("Cloned style:", "donation_can") . " " . $id);
        }
    }

    $widget_styles = donation_can_get_widget_styles();
    require_donation_can_view('widget_styles_page', array("widget_styles" => $widget_styles));
}
?>