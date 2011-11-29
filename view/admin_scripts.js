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


//
// ADMIN SCRIPTS
//

var cssElementCounter = 0;
var itemCounter = 0;

function dc_initWidgetStyleEditor() {
    cssElementCounter = jQuery("#css-selector-count").val();
    itemCounter = jQuery("#widget-counter-initial").val();

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

    var ajaxUrl = DonationCanData.ajaxUrl + "?action=donation_can-style_autocomplete";
    jQuery("input[name=css-selector]").suggest(ajaxUrl);
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
    jQuery("#" + newId + " > input").suggest(DonationCanData.ajaxUrl + "?action=donation_can-style_autocomplete");
}

function removeStyleRow(id) {
    var styleRow = jQuery("#" + id);
    jQuery("input", styleRow).val("");
    jQuery("textarea", styleRow).val("");

    styleRow.hide();
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

    jQuery("#poststuff form").submit();
}

/**
 *  Loads style options for the selected widget style.
 */
function loadStyleOptions(styleElement) {
    styleElement = jQuery(styleElement);
    var styleId = styleElement.val();

    var parent = styleElement.closest("div.widget-inside");
    var number = jQuery("input[name=wn]", parent).val();
    var customizationDiv = jQuery(".donation-can-widget-customization", parent);

    // Do an AJAX call to get all members for a given team
    jQuery.ajax({
        url: DonationCanData.ajaxUrl,
        data: {
            action: "donation_can-get_style_options",
            nonce: DonationCanData.styleOptionsNonce,
            style: styleId,
            wn: number
        },
        success: function(data) {
            customizationDiv.html(jQuery(data));
        }
    });
}

function deleteStyle(id, name) {
    var confirmText = DonationCanData.text_deleteStyleConfirmation;
    confirmText = confirmText.replace("%STYLE_NAME%", name);

    var agree = confirm(confirmText);
    if (agree) {
        jQuery("#remove_style_form > input[name=remove_style]").val(id);
        jQuery("#remove_style_form").submit();
    }
    return false;
}

function cloneStyle(id) {
    var newName = prompt(DonationCanData.text_cloneStylePrompt, "");

    if (newName) {
        jQuery("#clone_style_form > input[name=clone_style]").val(id);
        jQuery("#clone_style_form > input[name=new_name]").val(newName);
        jQuery("#clone_style_form").submit();
    }

    return false;
}


function clearDonationGoal(checkBoxElement) {
    var checkBox = jQuery(checkBoxElement);
    if (checkBox.is(':checked')) {
        var goalInput = jQuery("#donation-goal");
        goalInput.val("");
    }
}

function toggleSandboxEmailField(checkBoxElement) {
    var checkBox = jQuery(checkBoxElement);
    var sandboxEmailRow = jQuery("#paypal-sandbox-email-row");

    if (checkBox.is(':checked')) {
        sandboxEmailRow.show();
    } else {
        sandboxEmailRow.hide();
    }
}

function togglePayPalNoteFields(checkBoxElement) {
    var checkBox = jQuery(checkBoxElement);
    var sandboxEmailRow = jQuery("#paypal-note-field-row");

    if (checkBox.is(':checked')) {
        sandboxEmailRow.show();
    } else {
        sandboxEmailRow.hide();
    }
}

function resetDonationGoalCheckbox() {
    jQuery('input[name=no_goal]').attr("checked", false);
}

function checkMoneyFormatting(field, allowDecimal) {
    var $fieldElement = jQuery(field);
    var value = $fieldElement.val();

    // If the field allows decimal numbers, remove all but the first dot in the field
    if (allowDecimal == true) {
        var values = value.split(".");
        if (values.length > 1) {
            value = values[0] + "." + values[1];
            if (values.length > 2) {
                for (var i = 2; i < values.length; i++) {
                    value += values[i];
                }
            }
        }
        value = value.replace(/[^\d\.]/g, "");
    } else {
        value = value.replace(/[^\d]/g, "");
    }

    $fieldElement.val(value);
    if (value == "" && $fieldElement == jQuery('input[name=donation_goal]')) {
        jQuery('input[name=no_goal]').attr("checked", true);
    } else {
        // TODO: Add the comma to separate thousands
    }
}

function createCauseIdFromName() {
    setTimeout("doCreateCauseId();", 2000);
}

function doCreateCauseId() {
    // Make sure we don't change the id if it has already been set'
    var currentIdValue = jQuery("input[name=id]").val();
    if (currentIdValue != null && currentIdValue != "" && currentIdValue != 0) {
        return false;
    }

    var name = jQuery("input[name=name]").val();

    var id = formatCauseId(name);

    // Put the id in place
    jQuery("input[name=id]").val(id);
    jQuery("#id-preview").html(id);
    jQuery("#edit-slug-box").show();

    return true;
}

function dc_deleteDonation(id) {
    var confirmText = DonationCanData.text_deleteDonationConfirm.replace("%DONATION_ID%", id);
    var agree = confirm(confirmText);

    if (agree) {
        document.delete_donation.remove_donation.value = id;
        document.delete_donation.submit();
    }
    return false;
}


function formatCauseId(name) {
    var id = "";
    if (name != null && name != "") {
        id = name.toLowerCase();
        id = id.replace(/[^a-z0-9- ]/g, "").replace(/ /g, "-").replace(/[-]+/g, '-');
    }

    return id;
}

function editCauseId() {
    jQuery("input[name=id]").show();
    jQuery("#save-id-button").show();

    jQuery("#id-preview").hide();
    jQuery("#edit-id-button").hide();
}

function saveCauseId() {
    var value = jQuery("input[name=id]").val();
    value = formatCauseId(value);
    jQuery("input[name=id]").val(value);
    jQuery("#id-preview").html(value);

    jQuery("input[name=id]").hide();
    jQuery("#save-id-button").hide();

    jQuery("#id-preview").show();
    jQuery("#edit-id-button").show();
}

function verifyAddCauseFormFields() {
    var id = jQuery("input[name=id]").val();
    if (id == null || id == "" || id == 0 || id == "0") {
        alert("Error: You need to define an id for the cause before saving.");
        return false;
    }
    return true;
}

function showCurrencyOptions() {
    jQuery("#goal-currency").hide();
    jQuery("#currency-options").show();

    return false;
}

function hideCurrencySelection() {
    var selectedCurrency = jQuery("select[name=currency]").val();
    selectedCurrency = getCurrencySymbol(selectedCurrency);
    jQuery("#goal-currency a").html(selectedCurrency);
    jQuery("#goal-currency").show();
    jQuery("#currency-options").hide();

    return false;
}

/**
 * Adds a new field for entering a donation sum.
 */
function addFormTextField(countElementId, parentId, elementNameBody, cssClass) {
    var countElement = document.getElementById(countElementId);
    var count = parseInt(countElement.value);
    var parent = document.getElementById(parentId);

    // Create a div to contain the input, remove link and line break
    var groupElement = document.createElement("div");
    groupElement.setAttribute("id", elementNameBody + count);

    var element = document.createElement("input");
    element.setAttribute("name", elementNameBody + count);

    if (cssClass == null) {
        cssClass = "regular-text";
    }
    element.setAttribute("class", cssClass);
    element.setAttribute("type", "text");
    element.setAttribute("size", 40);
    element.setAttribute("onblur", "checkMoneyFormatting(this, true);");

    var removeElement = document.createElement('a');
    removeElement.appendChild(document.createTextNode(DonationCanData.text_remove));
    removeElement.setAttribute("onclick", "return removeFormTextField('" + parentId + "', '" + elementNameBody + count + "');");
    removeElement.setAttribute("href", "#");

    groupElement.appendChild(element);
    groupElement.appendChild(removeElement);

    parent.appendChild(groupElement);

    countElement.value = (count + 1);

    return false;
}

function removeFormTextField(parentId, elementId) {
    var element = document.getElementById(elementId);
    var parent = document.getElementById(parentId);

    if (parent != null) {
            parent.removeChild(element);
    }

    return false;
}

function switchTab(link, tabId) {
    jQuery(".tab-contents").hide();
    jQuery("#" + tabId).show();

    jQuery("ul.tab-items li a.selected").removeClass("selected");
    jQuery(link).addClass("selected");

    return false;
}

function uploadImage(element) {
    uploadToField = jQuery(element).closest('div.element-options');

    tb_show('', DonationCanData.uploadImageUrl);
    return false;
}

function dc_deleteCause(id, name) {
    var prompt = DonationCanData.text_deleteCauseConfirm.replace('%CAUSE_NAME%', name);
    var agree = confirm(prompt);
    if (agree) {
        document.delete_cause.remove_cause.value = id;
        document.delete_cause.submit();
    }
    return false;
}

function dc_resetCause(id, name) {
    var prompt = DonationCanData.text_resetCauseConfirm.replace('%CAUSE_NAME%', name);
    var agree = confirm(prompt);
    if (agree) {
        document.reset_cause.reset.value = id;
        document.reset_cause.submit();
    }
    return false;
}

function showOtherFormat(element) {
    var value = (jQuery(element).val() == "radio");

    var parent = jQuery(element).closest(".element-options");
    var otherFormatP = jQuery("p.other-format", parent);

    if (value == true) {
        otherFormatP.show();
    } else {
        otherFormatP.hide();
    }
}

function dc_downloadDataDump() {
    alert("submitting...");
    // TODO: collect data from form...
    var form = jQuery("#dc-dump-data");

    //var data = form.serialize();
    var data = {
        action: "donation_can-export",
        nonce: DonationCanData.exportDataNonce
    };

    // Do an ajax call to get the path to file
    jQuery.post(
        DonationCanData.ajaxUrl,
        data,
        function(data) {
            alert("url received: " + data);

            // Then start download
        }

    );


    return false;
}

function dc_configurePaymentMethod(id, link) {
    // TODO: change the text of the button... (Show / Hide) 
    
    jQuery("#dcan-payment-method-configuration-" + id).toggle(200);

    var parent = jQuery(link).parent();
    jQuery("a", parent).toggle();

    return false;
}