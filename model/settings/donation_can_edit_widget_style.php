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

function donation_can_edit_widget_style_menu() {
    global $wpdb;

    $style_id = $_GET["style_id"];
    if ($style_id == null) {
        $style_id = $_POST["style_id"];
    }

    $edit = false;
    if ($style_id) {
        $edit = true;
    }

    if (isset($_POST["style_action"]) && check_admin_referer('donation_can-update_style')) {
        $style_action = esc_attr($_POST["style_action"]);

        if ($style_action == "update" || $style_action == "add") {
            $name = stripslashes(esc_attr($_POST["name"]));

            // STRUCTURE

            $structure = esc_attr($_POST["widget-structure"]);
            $structure = html_entity_decode($structure);
            $structure = stripslashes(str_replace(array("\n","\r","\0"), "", $structure));
            $structure = preg_replace('/([{,])(\s*)([^"]+?)\s*:/','$1"$3":',$structure);

            $structure = json_decode($structure);

            $new_elements = array();

            foreach ($structure as $element) {
                $element_array = array();
                if ($element->data) {
                    foreach ($element->data as $data_element) {
                        $element_array[$data_element->key] = $data_element->value;
                    }
                }

                $element_array["type"] = $element->type;

                $new_elements[] = $element_array;
            }

            // CSS

            $css = html_entity_decode(esc_attr($_POST["widget-style"]));
            $css = stripslashes(str_replace(array("\n","\r","\0"), "", $css));
            $css = preg_replace('/([{,])(\s*)([^"]+?)\s*:/','$1"$3":',$css);

            $css = json_decode($css);

            $css_array = array();
            foreach ($css as $css_definition) {
                if (trim($css_definition->selector) != "" || trim($css_definition->css != "")) {
                    $css_array[$css_definition->selector] = $css_definition->css;
                }
            }

            // Save

            if ($style_action == "update") {
                $style_definition = donation_can_get_widget_style_by_id($style_id);

                render_user_notification(__("Widget style updated", "donation_can"));
            } else {                
                // TODO rename method to be more generic...
                $style_id = donation_can_create_cause_id_from_name($name);

                // If the id is already in use, append a rolling number
                if ($unique_id == true) {
                    $styles = donation_can_get_widget_styles();
                    $rotating_number = 1;
                    $id_body = $style_id . "-";
                    while (isset($causes[$style_id])) {
                        $style_id = $id_body . $rotating_number;
                        $rotating_number++;
                    }
                }

                $style_definition = array(
                    "id" => $style_id
                );

                $edit = true;
                render_user_notification(__("Widget style added", "donation_can"));
            }

            // Replace elements with the new settings
            $style_definition["name"] = $name;
            $style_definition["elements"] = $new_elements;
            $style_definition["css"] = $css_array;
            
            donation_can_save_widget_style($style_id, $style_definition);
        }

    }

    $styles = donation_can_get_widget_styles();

    require_donation_can_view('edit_widget_style', array("styles" => $styles, "style_id" => $style_id, "edit" => $edit));
}
?>