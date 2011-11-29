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

<?php $style = $styles[$style_id]; ?>

<script type="text/javascript">
    var uploadToField = null;
    var dropped = false;
    var received = false;

    jQuery(document).ready(function() {
        dc_initWidgetStyleEditor();
    });
</script>

<div class="wrap">
    <?php if ($style["locked"]) : ?>
        <h2><?php _e("View Widget Style", "donation_can"); ?></h2>
    <?php else : ?>
        <?php if ($edit) : ?>
            <h2><?php _e("Edit Widget Style", "donation_can"); ?></h2>
            <?php $save_button_text = __("Update Style", "donation_can"); ?>
        <?php else : ?>
            <h2><?php _e("Create Widget Style", "donation_can"); ?></h2>
            <?php $save_button_text = __("Add Style", "donation_can"); ?>
        <?php endif; ?>
    <?php endif; ?>

    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <div id="side-info-column" class="inner-sidebar">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                <div class="postbox" id="donation-submit-div">
                    <div class="handlediv" title="Click to toggle">
                        <br/>
                    </div>
                    <h3 class="hndle"><span><?php _e("Save", "donation_can");?></span></h3>
                    <div class="inside">
                        <div class="submitbox" id="submitlink">
                            <div id="major-publishing-actions">
                                <div id="delete-action"></div>
                                <div id="publishing-action">
                                    <?php if ($style['locked']) : ?>
                                        <div class="donation_can_notice">
                                            <?php _e("You are viewing a default widget style that cannot be changed.", "donation_can"); ?>
                                        </div>
                                    <?php else : ?>
                                        <input type="button" onclick="storeJSONStructureAndSubmit();" class="button-primary"
                                               value="<?php echo $save_button_text; ?>"/>
                                    <?php endif; ?>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="post-body">
            <div id="post-body-content">

                <div id="titlediv">
                    <div id="titlewrap">
                        <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                            <?php wp_nonce_field('donation_can-update_style'); ?>

                            <input type="hidden" name="style_action" value="<?php echo ($edit) ? "update" : "add"; ?>"/>
                            <input type="hidden" name="widget-structure" value=""/>
                            <input type="hidden" name="widget-style" value=""/>
                            <input type="hidden" name="style_id" value="<?php echo $style_id; ?>"/>

                            <label class="screen-reader-text" for="name">Title</label>
                            <input type="text" name="name"
                                   size="30" tabindex="1" id="title" autocomplete="off"
                                   value="<?php echo $style["name"];?>">
                        </form>
                    </div>
                </div>


                <div class="donation-can-admin-tabs">

                    <ul class="tab-items">
                        <li class="first"><a href="#" class="selected" onclick="return switchTab(this, 'widget-structure');"><?php _e("Structure", "donation_can");?></a></li>
                        <li class="last"><a href="#" onclick="return switchTab(this, 'widget-style');"><?php _e("Stylesheet", "donation_can");?></a></li>
                    </ul>


                    <div id="widget-style" class="tab-contents" style="display:none;">

                        <div id="css-element-container">
                            <?php $css_counter = 0; if (isset($style["css"])) : foreach ($style["css"] as $selector => $css_element) : ?>
                                <div class="donation-can-css-element" id="css-element-<?php echo $css_counter; ?>">
                                    <a href="#" class="remove-css-row" onclick="removeStyleRow('css-element-<?php echo $css_counter;?>');"><?php _e("Remove", "donation_can");?></a>
                                    <input type="text" name="css-selector" value="<?php echo $selector;?>"><br/>
                                    <textarea name="css-definition" cols="60" rows="5"><?php echo str_replace('; ', ";\n", $css_element);?></textarea>
                                </div>
                                <?php $css_counter++; ?>
                            <?php endforeach; endif; ?>
                        </div>

                        <input type="hidden" id="css-selector-count" value="<?php echo $css_counter; ?>"/>

                        <div id="style-row-template" style="display:none;">
                            <div class="donation-can-css-element">
                                <a href="#" class="remove-css-row"><?php _e("Remove", "donation_can");?></a>
                                <input type="text" class="css-selector" name="css-selector" value=""><br/>
                                <textarea name="css-definition" cols="60" rows="5"></textarea>
                            </div>
                        </div>

                        <div id="add-style-row-button">
                            <a class="button" onclick="addStyleRow();"><?php _e("Add new CSS definition", "donation_can");?></a>
                        </div>

                    </div>

                    <div id="widget-structure" class="tab-contents">

                        <div class="donation-can-widget-left">
                            <div id="widget-list">
                                <ul>
                                <?php
                                    $available_elements = donation_can_get_available_widget_style_elements();
                                    foreach ($available_elements as $el) {
                                        $element_object = donation_can_get_style_element_from_data(array('type' => $el));
                                        echo $element_object->to_string(false);
                                    }
                                ?>
                                </ul>
                            </div>
                        </div>

                        <div class="donation-can-widget-right">
                            <div class="sidebar-name">
                                <h3><?php _e("Widget", "donation_can");?></h3>
                            </div>

                            <div id="widget-contents">
                                <ul>
                                    <?php if ($style != null && count($style["elements"]) > 0) : ?>
                                        <?php $counter = 0; foreach ($style["elements"] as $id => $element) : ?>
                                            <?php $element_object = donation_can_get_style_element_from_data($element); ?>
                                            <?php if ($element_object != null) { echo $element_object->to_string(true, $counter++); } ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="clean-slate"><?php _e("Drop elements from right side palette to this canvas.", "donation_can");?></div>
                                    <?php endif; ?>
                                </ul>

                                <input type="hidden" id="widget-counter-initial" value="<?php echo $counter;?>"/>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>