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

function getCurrencySymbol(currencyCode) {
    if (currencyCode == "USD" || currencyCode == "CAD") {
        currencyCode = "$";
    } else if (currencyCode == "EUR") {
        currencyCode = "&euro;";
    } else if (currencyCode == "GBP") {
        currencyCode = "&pound;";
    } else if (currencyCode == "JPY") {
        currencyCode = "&yen;";
    }

    return currencyCode;
}

function donationCauseSelected(select) {
    var causeId = jQuery(select).val();
    if (causeId) {
        var parent = jQuery(select).closest("form");

        var descriptionElement = jQuery(".description", parent);
        var donationOptionsElement = jQuery(".donation-options", parent);
        var submitDonationElement = jQuery(".submit-donation", parent);

        jQuery.ajax({
            url: DonationCanData.ajaxUrl,
            data: {
                action: "donation_can-get_cause_data",
                cause: causeId
            },
            success: function(dataAsJSON) {
                var data = jQuery.parseJSON(dataAsJSON);

                descriptionElement.html(jQuery("<p>" + data.description + "</p>"));

                // TODO: handle radio buttons and button list too!

                var donationListElement = jQuery("select[name=amount]", parent);
                if (donationListElement) {
                    jQuery(donationListElement).children().remove();
                }

                if (data.donation_options != null && data.donation_options.length > 0) {
                    if (donationListElement) {
                        // Append donation options
                        var currency = getCurrencySymbol(data.currency);
                        for (var i = 0; i < data.donation_options.length; i++) {
                            var currencyText = DonationCanData.text_currencyFormat.replace("%CURRENCY%", currency).replace("%SUM%", data.donation_options[i]);

                            donationListElement.append(jQuery("<option value=\"" + data.donation_options[i] + "\">" + currencyText + "</option>"));
                        }
                    }

                    donationOptionsElement.show();
                } else {
                    donationOptionsElement.hide();
                }

                descriptionElement.show();
                submitDonationElement.show();

                jQuery(".anonymous-prompt", parent).show();
            }
        });

    }
}

// Used for radio button donations with text field for entering "other" value
function showOtherTextField(element, value) {
    var parent = jQuery(element).closest(".donation-radio-button-list");
    var span = jQuery("span.amount-span", parent);

    var textField = jQuery("input.amount-text-field", span);

    if (value == true) {
        textField.attr("name", "amount");
        span.show();
    } else {
        textField.removeAttr("name");
        span.hide();
    }
}
