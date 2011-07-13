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


/*
 * The methods in this file are meant to be used by theme developers / plugin users
 * who don't want to use the widgets but would rather call the plugin methods in
 * the code.
 *
 * The following methods can be used:
 * 	- donation_can_donation_list();
 *  - donation_can_donation_progress();
 *  - donation_can_donation_form();
 */


/**
 * Renders a list of recent donations.
 *
 * @param goal_id			The id of the goal to render or "__all__" if you want to list donations made
 *							to all goals. Defaults to "__all__"
 * @param num_donations		Number of donations to list. Defaults to 5.
 * @param show_title		Toggles showing the title of the list. Defaults to true.
 * @param title				An optional custom title for the list. If empty, defaults to "Latest Donations to %goalname%"
 * @param show_donor_name 	Toggles showing the names of donors in the list of donations. Defaults to true.
 * @param show_donation_sum	Toggles showing the sums donated. Defaults to true.
 */
function donation_can_donation_list($goal_id = "__all__", $num_donations = 5, $show_title = true, 
	$title = "", $show_donor_name = true, $show_donation_sum = true) {
		
	$params = array(
		"goal_id" => $goal_id, 
		"show_title" => $show_title, 
		"title" => $title, 
		"show_donor_name" => $show_donor_name, 
		"show_donation_sum" => $show_donation_sum,
		"num_donations" => $num_donations
	);
	
	$args = array(
		"before_widget" => "",
		"after_widget" => "",
		"before_title" => "<h2>",
		"after_title" => "</h2>"
	);

	$widget = new DonationListWidget();
	$widget->widget($args, $params);
}


/**
 * Renders a progress bar for the given goal.
 *
 * @param $goal_id		The id of the goal to which a progress bar should be drawn, or "__all__" if you
 *						want to chart the progress of all goals as a whole. Defaults to "__all__".
 * @param $show_title	Toggles showing the title of the progress bar. Defaults to true.
 * @param $title		A custom title. If left empty, the name of the goal is used.
 */
function donation_can_donation_progress($goal_id = "__all__", $show_title = true, $title = "") {
	$params = array(
		"goal_id" => $goal_id,
		"show_title" => $show_title,
		"title" => $title
	);
	
	$args = array(
		"before_widget" => "",
		"after_widget" => "",
		"before_title" => "<h2>",
		"after_title" => "</h2>"
	);

	$widget = new DonationProgressWidget();
	$widget->widget($args, $params);
}


/**
 * Renders a donation form for the given donation goal.
 *
 * @param $goal_id			The id of the goal to render. No default value.
 * @param $style_id             The style id to use for the donation goal
 * @param $show_progress	Toggles showing a progress bar. Defaults to true.
 * @param $show_description	Toggles showing goal description. Defaults to true.
 * @param $show_donations	Toggles showing a list of donations. Defaults to false.
 * @param $show_title		Toggles showing the title. Defaults to true.
 * @param $title			A custom title. If left empty, the name of the goal is used instead.
 */
function donation_can_donation_form($goal_id, $style_id = "default", $show_progress = true, $show_description = true, 
	$show_donations = false, $show_title = true, $title = "", $return = false) {
		
	$params = array(
		"goal_id" => $goal_id,
		"show_progress" => $show_progress,
		"show_description" => $show_description,
		"show_donations" => $show_donations,
		"show_title" => $show_title,
		"title" => $title
	);

	$args = array(
		"before_widget" => "",
		"after_widget" => "",
		"before_title" => "<h2>",
		"after_title" => "</h2>"
	);

	$widget = new DonationWidget();
	
	if ($return) {
		return $widget->to_string($args, $params);
	}
	
	$widget->widget($args, $params);
}

/**
 * Returns the donation form as a string. 
 *
 * See donation_can_donation_form() above for documentation.
 */
function get_donation_can_donation_form($goal_id, $show_progress = true, $show_description = true, 
	$show_donations = false, $show_title = true, $title = "") {
	return donation_can_donation_form($goal_id, $show_progress, $show_description, $show_donations, $show_title, $title, true);
}


?>