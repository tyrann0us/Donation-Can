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

/*
 * Methods related to supporting the MVC pattern in the Donation Can plugin.
 */

function require_donation_can_view($view_name, $args = null) {
    $plugin_path = WP_PLUGIN_DIR . "/donation-can";

    if (file_exists($plugin_path)) {
        $view_path = $plugin_path . "/view/" . $view_name . ".php";
        if (file_exists($view_path)) {
            if ($args != null) {
                extract($args);
            }

            require($view_path);
        } else {
            die ("View not found in " . $view_path);
        }
    } else {
        die("Donation Can installation not found in " . WP_PLUGIN_DIR . "/donation-can");
    }
}

function get_donation_can_view_as_string($view_name, $args = null) {
    $out = "";
    
    ob_start();

    require_donation_can_view($view_name, $args);

    $out .= ob_get_contents();
    ob_end_clean();

    return $out;
}

?>
