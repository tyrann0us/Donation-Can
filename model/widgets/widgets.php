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

require("donation_widget.php");
require("donation_progress_widget.php");
require("donation_list_widget.php");

// Register the widgets
add_action('widgets_init', create_function('', 'return register_widget("DonationWidget");'));
add_action('widgets_init', create_function('', 'return register_widget("DonationProgressWidget");'));
add_action('widgets_init', create_function('', 'return register_widget("DonationListWidget");'));
?>