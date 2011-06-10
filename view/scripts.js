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


function clearDonationGoal(checkBoxElement) {
    var checkBox = jQuery(checkBoxElement);
    if (checkBox.is(':checked')) {
        var goalInput = jQuery("#donation-goal");
        goalInput.val("");
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

    if (selectedCurrency == "USD") {
        selectedCurrency = "$";
    } else if (selectedCurrency == "EUR") {
        selectedCurrency = "&euro;";
    } else if (selectedCurrency == "GBP") {
        selectedCurrency = "&pound;";
    } else if (selectedCurrency == "JPY") {
        selectedCurrency = "&yen;";
    }


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
	removeElement.appendChild(document.createTextNode("Remove"));
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