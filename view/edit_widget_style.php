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

<style type="text/css">
    .donation-can-widget-structure-container {
        margin: 20px 0px 20px 0px;
    }

    .donation-can-widget-left {
        float: right;
        clear: right;
        width: 270px;
    }

    .donation-can-widget-right {
        margin: 20px 290px 0px 0px;
    }

    .donation-can-widget-right .sidebar-name, .donation-can-widget-left .sidebar-name {
        background-color: #aaa;
        background: url(images/ed-bg.gif);
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        border: 1px solid #DFDFDF;
        text-shadow: white 0px 1px 0px;
    }

    .donation-can-widget-right .sidebar-name h3, .donation-can-widget-left .sidebar-name h3 {
        margin: 0px;
        font-size: 13px;
        height: 19px;
        color: #333;
        margin: 0px;
        overflow: hidden;
        padding: 5px 12px;
        white-space: nowrap;
    }

    .donation-can-widget-right #widget-contents {
        border: 1px solid #DFDFDF;
        background-color: #F1F1F1;
        border-top: 0px;
        padding: 10px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .donation-can-widget-left #widget-list {
        border: 1px solid #DFDFDF;
        border-right: 0px;
        border-top: 0px;
        padding: 10px;
        border-bottom-left-radius: 10px;        
    }

    .donation-can-widget-left .widget {
        width: 250px;
    }

    .donation-can-widget-left .widget .widget-top {
        padding: 10px;
    }

    .donation-can-widget-left .widget .widget-top h4 {
        margin: 0px;
    }


    .widget-element {
        border: 1px solid #dfdfdf;
        border-radius: 8px;
        background: url("images/gray-grad.png");
        padding: 0px;
    }

    .widget-element .element-options {
        background-color: #fff;
        margin: 0px;
        padding: 5px 10px 5px 10px;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .widget-element .element-options textarea {
        width: 100%;
    }

    .widget-element h4, .widget-element h3 {
        padding: 5px;
        margin: 0px;
    }

    #widget-list ul {
        width: 250px;
    }

    .ready-to-delete {
        background-color: #c3cccc;
    }

    .donation-can-admin-tabs ul.tab-items {
        width: 100%;
        overflow: auto;
        margin: 0px 0px 0px 5px;
        padding: 0px;
    }

    .donation-can-admin-tabs ul.tab-items li {
        float: left;

        margin: 0px;
        margin-right: 5px;

        padding: 0px;
    }

    .donation-can-admin-tabs ul.tab-items li a {
        padding: 5px 10px 5px 10px;
        display: block;
    }

    .donation-can-admin-tabs ul.tab-items li a.selected {
        border-right: 1px solid #dfdfdf;
        border-top: 1px solid #dfdfdf;
        border-left: 1px solid #dfdfdf;

        border-top-left-radius: 3px;
        border-top-right-radius: 3px;

        background-color: #F1F1F1;

        color: #333;
        text-decoration: none;
        font-weight: bold;
    }

    .donation-can-admin-tabs .tab-contents {        
        border: 1px solid #dfdfdf;
        overflow: auto;
        border-radius: 5px;
    }

    .donation-can-admin-tabs #widget-structure {
        padding: 0px 0px 20px 20px;
    }

    .clean-slate {
        text-align: center;
        border: 1px dashed #d1d1d1;
        padding: 10px;
        color: #aaa;
        height: 20px;
    }

    #widget-style {
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .donation-can-css-element {
        padding: 10px;
        margin: 10px 10px 0px 10px;
    }

    .donation-can-css-element textarea, .donation-can-css-element input {
        width: 100%;
    }

    .donation-can-css-element input {
        font-weight: bold;
    }

    div#add-style-row-button {
        margin: 20px 0px 10px 20px;
    }

    input.css-selector {
        float: left;
        clear: left;

        margin-right: 150px;
        width: 100%;
    }

    .remove-css-row {
        float: right;
        clear: right;
        display: block;

        margin-left: 10px;
    }
</style>

<?php $style = $styles[$style_id]; ?>

<script type="text/javascript">
    var cssElementCounter = 0;
    var uploadToField = null;

    function switchTab(link, tabId) {
        jQuery(".tab-contents").hide();
        jQuery("#" + tabId).show();

        jQuery("ul.tab-items li a.selected").removeClass("selected");
        jQuery(link).addClass("selected");

        return false;
    }

    function getStructureAsJson() {
        var itemIdArray = jQuery("#widget-contents ul").sortable("toArray");

        var dataArray = new Array();

        // Iterate through the item ids and collect their data
        for (var i = 0; i < itemIdArray.length; i++) {
            var itemId = itemIdArray[i];

            if (itemId) {
                var item = jQuery("#" + itemId);
                var type = itemId.substring(0, itemId.lastIndexOf('-element'));

                var itemData = new Array();
                item.find(".element-options").find('select,input,textarea').each(function(index) {
                    var name = jQuery(this).attr('name');
                    var value = jQuery(this).val();

                    itemData.push({key: name, value: value});
                });

                dataArray.push({ type: type, data: itemData });
            }
        }

        return JSON.stringify(dataArray);
    }

    function getCssAsJson() {
        var dataArray = [];

        jQuery("#css-element-container > div.donation-can-css-element").each(function(index) {
            var selector = jQuery(this).find("input[name=css-selector]").val();
            var definition = jQuery(this).find("textarea[name=css-definition]").val();

            dataArray.push({ selector: selector, css: definition });
        });

        return JSON.stringify(dataArray);
    }

    function storeJSONStructureAndSubmit() {
        if (jQuery("input[name=name]").val() == "") {
            jQuery("input[name=name]").val("Untitled");
        }

        var structureAsJSON = getStructureAsJson();
        jQuery("input[name=widget-structure]").val(structureAsJSON);

        var cssAsJSON = getCssAsJson();
        jQuery("input[name=widget-style]").val(cssAsJSON);

        jQuery("form").submit();
    }

    function addStyleRow() {
        var styleRow = jQuery("#style-row-template > div.donation-can-css-element").clone();
        var newId = "css-element-" + cssElementCounter++;
        styleRow.attr("id", newId);

        jQuery("a.remove-css-row", styleRow).click(function() {
            removeStyleRow(newId);
        });

        jQuery("#css-element-container").append(styleRow);
        //styleRow.effect("highlight", {}, 3000);

        // Enable autocomplete for the new row
        jQuery("#" + newId + " > input").suggest("<?php bloginfo('url'); ?>?donation_can_style_autocomplete=1");
    }

    function removeStyleRow(id) {       
        var styleRow = jQuery("#" + id);
        jQuery("input", styleRow).val("");
        jQuery("textarea", styleRow).val("");

        styleRow.hide();
    }

    jQuery(document).ready(function() {
        var dropped = false;
        var received = false;

        var itemCounter = jQuery("#widget-counter-initial").val();

        cssElementCounter = jQuery("#css-selector-count").val();

        jQuery("#widget-list ul li").draggable( {
            connectToSortable: "#widget-contents ul",
            helper: "clone",
            revert: "invalid"
        });

        jQuery("#widget-list").droppable( {
            accept: '#widget-contents ul > li',
            drop: function(event, ui) {
                dropped = true;
                
                jQuery("#widget-list").removeClass("ready-to-delete");
                jQuery("#widget-contents ul").sortable("refresh");
            },
            over: function(event, ui) {
                jQuery("#widget-list").addClass("ready-to-delete");
            },
            out: function(event, ui) {
                jQuery("#widget-list").removeClass("ready-to-delete");
            }

        });

        jQuery("#widget-contents ul").sortable( {
            forcePlaceholderSize: true,
            placeholder: 'empty-list-state',
            connectToDroppable: '#widget-list ul',
            receive: function(event, ui) {
                received = true;

                jQuery(".clean-slate").hide();
            },
            deactivate: function(event, ui) {
                if (received) {
                    // Show options when an event has been dragged to the list
                    var item = ui.item;

                    var newId = item.attr("id") + "-" + itemCounter;
                    item.attr("id", newId);

                    var optionsElement = jQuery(".element-options", item);
                    optionsElement.show();

                    itemCounter++;

                    received = false;
                }
            },
            remove: function(event, ui) {
                alert("removed");
            },
            stop: function(event, ui) {
                if (dropped) {
                    dropped = false;
                    ui.item.remove();

                    // If this was the last item on the list, put back the empty slate
                    var size = jQuery(this).sortable("toArray").length - 1;
                    if (size == 0) {
                        jQuery(".clean-slate").show();
                    }
                }
            }
        });

        window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery('input[name=button-image]', uploadToField).val(imgurl);
            jQuery('img', uploadToField).attr("src", imgurl);

            tb_remove();
        }

        // CSS editor
        
        jQuery("input[name=css-selector]").suggest("<?php bloginfo('url'); ?>?donation_can_style_autocomplete=1");
    });


    function uploadImage(element) {
        uploadToField = jQuery(element).closest('div.element-options');

        tb_show('', '<?php echo bloginfo('url') ?>/wp-admin/media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    };

</script>

<div class="wrap">
    <?php if ($style["locked"]) : ?>
        <h2><?php _e("View Widget Style", "donation_can"); ?></h2>
    <?php else : ?>
        <?php if ($edit) : ?>
            <h2><?php _e("Edit Widget Style", "donation_can"); ?></h2>
            <?php $save_button_text = "Update Style"; ?>
        <?php else : ?>
            <h2><?php _e("Create Widget Style", "donation_can"); ?></h2>
            <?php $save_button_text = "Add Style"; ?>
        <?php endif; ?>
    <?php endif; ?>

    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <div id="side-info-column" class="inner-sidebar">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                <div class="postbox" id="donation-submit-div">
                    <div class="handlediv" title="Click to toggle">
                        <br/>
                    </div>
                    <h3 class="hndle"><span><?php _e("Save");?></span></h3>
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
                                        <input type="button" onclick="storeJSONStructureAndSubmit();" class="button-primary" id="publish"
                                               value="<?php _e($save_button_text, "donation_can");?>"/>
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
                        <li class="first"><a href="#" class="selected" onclick="return switchTab(this, 'widget-structure');">Structure</a></li>
                        <li class="last"><a href="#" onclick="return switchTab(this, 'widget-style');">Stylesheet</a></li>
                    </ul>


                    <div id="widget-style" class="tab-contents" style="display:none;">

                        <div id="css-element-container">
                            <?php $css_counter = 0; if (isset($style["css"])) : foreach ($style["css"] as $selector => $css_element) : ?>
                                <div class="donation-can-css-element" id="css-element-<?php echo $css_counter++; ?>">
                                    <a href="#" class="remove-css-row" onclick="removeElement('css-element-<?php echo $css_counter;?>');">Remove</a>
                                    <input type="text" name="css-selector" value="<?php echo $selector;?>"><br/>
                                    <textarea name="css-definition" cols="60" rows="5"><?php echo str_replace('; ', ";\n", $css_element);?></textarea>
                                </div>
                            <?php endforeach; endif; ?>
                        </div>

                        <input type="hidden" id="css-selector-count" value="<?php echo $css_counter; ?>"/>

                        <div id="style-row-template" style="display:none;">
                            <div class="donation-can-css-element">
                                <a href="#" class="remove-css-row">Remove</a>
                                <input type="text" class="css-selector" name="css-selector" value=""><br/>
                                <textarea name="css-definition" cols="60" rows="5"></textarea>
                            </div>
                        </div>

                        <div id="add-style-row-button">
                            <a class="button" onclick="addStyleRow();">Add new CSS definition</a>
                        </div>

                    </div>

                    <div id="widget-structure" class="tab-contents">

                        <div class="donation-can-widget-left">
                            <div id="widget-list">
                                <ul>
                                <?php
                                    $available_elements = array("title", "description", "donation-options", "donation-list", "progress", "text", "anonymous", "submit");
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
                                <h3>Widget</h3>
                            </div>

                            <div id="widget-contents">
                                <ul>
                                    <?php if ($style != null) : ?>
                                        <?php $counter = 0; foreach ($style["elements"] as $id => $element) : ?>
                                            <?php $element_object = donation_can_get_style_element_from_data($element); ?>
                                            <?php if ($element_object != null) { echo $element_object->to_string(true, $counter++); } ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="clean-slate">Drop elements from right side palette to this canvas</div>
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