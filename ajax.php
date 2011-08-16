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

// Returns the autocomplete options for style designer UI
if (isset($_GET['donation_can_style_autocomplete'])) :
    $q = strtolower($_REQUEST["q"]);
    if (!$q) die();

    $items = array(
        ".backlink",
        ".backlink a",
        ".description",
        ".donations-list",
        ".donations-list li",
        ".donation-date",
        ".donation-options",
        ".donation-options .donation-callout",
        ".donation-options .donation-button-list",
        ".donation-options .donation-button-list a.button",
        ".donation-options select",
        ".progress-element",
        ".progress-element .progress-meter",
        ".progress-element .progress-meter .progress-container",
        ".progress-element .progress-meter .progress-container .progress-bar",
        ".progress-element .progress-text",
        ".progress-element .progress-text .percentage",
        ".progress-element .progress-text .raised-label",
        ".progress-element .progress-text .of-label",
        ".progress-element .progress-text .currency",
        ".progress-element .progress-text .goal",
        ".progress-element .progress-text .goal-label",
        ".progress-element .progress-text .raised",
        ".submit-donation",
        ".submit-donation input",
        ".custom-text",
        ".donation-widget-title"
    );

    header("Content-type: text/plain");

    foreach ($items as $key) {
	if (strpos(strtolower($key), $q) !== false) {
            echo "$key\n";
	}
    }

    die();
elseif (isset($_GET["donation_can_test_email"])) :
    $admin_email = get_option("admin_email");
    donation_can_send_email($admin_email, "Test email", donation_can_get_general_settings(), array(), array(), $admin_email);
endif;
?>