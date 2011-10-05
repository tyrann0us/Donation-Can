<?php
/*
Plugin Name: Donation Can
Version: 1.5.5
Plugin URI: http://treehouseapps.com/donation-can
Description: Donation Can lets you raise funds for multiple causes using your WordPress blog and PayPal account while tracking the progress of each cause separately. <a href="admin.php?page=donation-can/model/settings/donation_can_settings.php">Click here</a> to configure settings.
Author: Jarkko Laine
Author URI: http://jarkkolaine.com
*/

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

require("ajax.php");
require("mvc.php");

require("model/data.php");

require("model/widgets/widgets.php");
require("model/dashboard/dashboard.php");
require("model/settings/settings.php");
require("model/widget_style_elements/widget_style_elements.php");

// Helper methods for theme developers
require("theme_methods.php");

// Adds the style sheet definition to head
function donation_can_head_filter() {   
    echo "<style type=\"text/css\" media=\"screen\">\n";

    $styles = donation_can_get_widget_styles();
    foreach ($styles as $style) {
        if (isset($style["css"])) {
            foreach ($style["css"] as $element => $css_definition) {
                echo ".donation-can-widget." . $style["id"] . " " . $element . " {\n";
                echo $css_definition;
                echo "\n}";
            }
        }
    }
    
    // Deprecated, but let's keep it for a while in case people are relying on it
    $options = get_option("donation_can_general");
    if ($options != null && isset($options["custom"])) {
        echo $options["custom"];
    }

    echo "</style>";
}

/**
 * The content filter replaces all donation can quick tags with a donation box.
 */
function donation_can_shortcode_handler($attributes, $content = null) {
    $defaults = array(
        "style_id" => "default",
        "goal_id" => "",
        "show_progress" => true,
        "show_description" => true,
        "show_donations" => false,
        "show_title" => true,
        "title" => ""
    );

    $parameters = shortcode_atts($defaults, $attributes);

    foreach ($parameters as $key => $value) {
        if ($value == "true") {
            $value = true;
        } else if ($value == "false") {
            $value = false;
        }

        $parameters[$key] = $value;        
    }

    $widget_content = "<div class=\"donation-can_content-widget\">";

    $widget = new DonationWidget();
    $widget_content .= $widget->to_string(array(), $parameters);

    $widget_content .= "</div>";

    return $widget_content;
}


//
// Some WordPress magic to get the handle the callback (IPN) from PayPal
// (See http://www.james-vandyne.com/2009/08/process-paypal-ipn-requests-through-wordpress/
// for more info on how this works)
//

function donation_can_query_vars($vars) {
    $vars[] = 'donation_can_ipn';

    return $vars;
}

/**
 * Processing for different Donation Can callbacks made through
 * permalinks, e.g. PayPal IPN calls and starting a new donation.
 */
function donation_can_parse_request($wp) {
    if (array_key_exists('donation_can_ipn', $wp->query_vars)) {
        $query_type = $wp->query_vars['donation_can_ipn'];

        switch ($query_type) {
            case 'start':
                donation_can_process_start_donation($wp);
                break;

            case 'paypal':
                donation_can_process_paypal_ipn($wp);
                break;

            default:
                // TODO: should we log something?
                break;
        }
    }
}

/**
 * Rewrite rules for permalinks
 *
 * @param <type> $wp_rewrite
 */
function donation_can_rewrite_rules($wp_rewrite) {
    $new_rules = array(
        'donation_can_ipn/paypal' => 'index.php?donation_can_ipn=paypal', // callback from PayPal
        'donation_can_ipn/start_donation' => 'index.php?donation_can_ipn=start' // start donation process
    );

    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

function donation_can_flush_rules() {
    if (get_option("donation_can_rewrite_rule_version", "0.0") != "1.1") {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
        
        update_option("donation_can_rewrite_rule_version", "1.1");
    }
}

function donation_can_admin_notices() {
    if (current_user_can('manage_options')) {
        $options = get_option("donation_can_general");
        if ($options != null && $options["debug_mode"]) {
            echo '<div class="donation-can-admin-notice">';

            $settings_url = get_bloginfo("url") . '/wp-admin/admin.php?page=donation_can_general_settings.php';
            _e('Donation Can is in sandbox mode. Remember to <a href="' . $settings_url . '">switch back to normal mode</a> when done testing.');
            echo '</div>';
        }
    }
}

function donation_can_init(){
    load_plugin_textdomain("donation_can", false, "donation-can");

    // TODO: don't enqueue all scripts all the time...

    wp_enqueue_style('donation-can', plugins_url("/donation-can/view/style.css"), false,'1.0','all');

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');

    if (is_admin()) {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');

        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('json2');
        wp_enqueue_script('suggest'); // For autocompletes in admin
    }
    
    wp_enqueue_script('donation-can-scripts', plugins_url("/donation-can/view/scripts.js"));
}

function donation_can_add_tinymce_button($buttons) {
    array_push($buttons, "separator", "donationcanplugin");
    return $buttons;
}

function donation_can_tinymce_plugin_register($plugin_array) {
    $url = plugins_url("/donation-can/view/editor_plugin.js");

    $plugin_array["donationcanplugin"] = $url;
    return $plugin_array;
}

function donation_can_media_button_register() {
    $url = "media-upload.php?type=donation-can&tab=type_url&TB_iframe=true";
?>
    <a href="<?php echo $url; ?>" class="thickbox" title="<?php _e('Add Donation Form', 'donation-can');?>">
        <img src="<?php echo plugins_url("/donation-can/view/img/media-icon.png"); ?>"
             alt="<?php _e('Add Donation Form', 'donation-can');?>">
    </a>
<?php
}

function donation_can_media_button_form() {    
    require_donation_can_view("add_shortcode", array("goals" => donation_can_get_goals(), "styles" => donation_can_get_widget_styles()));
}

function donation_can_update_roles() {
    $roles_version = get_option("donation_can_roles_version", "0.0");
    if ($roles_version != "1.0") {
        $role = get_role('administrator');
        $role->add_cap('dc_general_settings');
        $role->add_cap('dc_causes');
        $role->add_cap('dc_donations');
        $role->add_cap('dc_styles');
        $role->add_cap('dc_dashboard');

        update_option("donation_can_roles_version", "1.0");
    }
}

// I develop the plugin outside wp-content using a symlink so I can't use __FILE__ here.
register_activation_hook(WP_PLUGIN_DIR . "/donation-can/donation_can.php", 'donation_can_install');

add_shortcode("donation-can", "donation_can_shortcode_handler");

add_filter("wp_head", "donation_can_head_filter");
add_filter("admin_head", "donation_can_head_filter");
add_filter('query_vars', 'donation_can_query_vars');
add_action('parse_request', 'donation_can_parse_request');
add_action('generate_rewrite_rules', 'donation_can_rewrite_rules');
add_filter('wp_loaded', 'donation_can_flush_rules');

add_action('admin_notices', 'donation_can_admin_notices');
add_filter('init', 'donation_can_init');

// Handle the button for inserting donation can shortcode into post.
// We use the media buttons row to make the button more visible and available
// in both the WYSIWYG and HTML editing modes.
add_action("media_buttons", "donation_can_media_button_register", 20);
add_action("media_upload_donation-can", "donation_can_media_button_form");

// Check that the database tables are up to date
add_action("plugins_loaded", 'donation_can_db_upgrade');
add_action("plugins_loaded", 'donation_can_update_roles');
?>