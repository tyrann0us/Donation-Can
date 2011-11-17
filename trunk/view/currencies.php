<select name="currency">
    <option value="USD" <?php if ($currency == 'USD') { echo "selected"; }?>><?php _e("U.S. Dollars (USD)", "donation_can");?></option>
    <option value="EUR" <?php if ($currency == 'EUR') { echo "selected"; }?>><?php _e("Euros (EUR)", "donation_can");?></option>
    <option value="GBP" <?php if ($currency == 'GBP') { echo "selected"; }?>><?php _e("Pounds Sterling (GBP)", "donation_can");?></option>

    <option value="AUD" <?php if ($currency == 'AUD') { echo "selected"; }?>><?php _e("Australian Dollars (AUD)", "donation_can");?></option>
    <option value="NZD" <?php if ($currency == 'NZD') { echo "selected"; }?>><?php _e("New Zealand Dollars (NZD)", "donation_can");?></option>
    <option value="CAD" <?php if ($currency == 'CAD') { echo "selected"; }?>><?php _e("Canadian Dollars (CAD)", "donation_can");?></option>
    <option value="JPY" <?php if ($currency == 'JPY') { echo "selected"; }?>><?php _e("Yen (JPY)", "donation_can");?></option>
    <option value="CHF" <?php if ($currency == 'CHF') { echo "selected"; }?>><?php _e("Swiss Franc (CHF)", "donation_can");?></option>
    <option value="HKD" <?php if ($currency == 'HKD') { echo "selected"; }?>><?php _e("Hong Kong Dollar (HKD)", "donation_can");?></option>
    <option value="SGD" <?php if ($currency == 'SGD') { echo "selected"; }?>><?php _e("Singapore Dollar (SGD)", "donation_can");?></option>
    <option value="SEK" <?php if ($currency == 'SEK') { echo "selected"; }?>><?php _e("Swedish Krona (SEK)", "donation_can");?></option>
    <option value="DKK" <?php if ($currency == 'DKK') { echo "selected"; }?>><?php _e("Danish Krone (DKK)", "donation_can");?></option>
    <option value="NOK" <?php if ($currency == 'NOK') { echo "selected"; }?>><?php _e("Norwegian Krone (NOK)", "donation_can");?></option>
    <option value="PLN" <?php if ($currency == 'PLN') { echo "selected"; }?>><?php _e("Polish Zloty (PLN)", "donation_can");?></option>
    <option value="HUF" <?php if ($currency == 'HUF') { echo "selected"; }?>><?php _e("Hungarian Forint (HUF)", "donation_can");?></option>
    <option value="CZK" <?php if ($currency == 'CZK') { echo "selected"; }?>><?php _e("Czech Koruna (CZK)", "donation_can");?></option>
    <option value="ILS" <?php if ($currency == 'ILS') { echo "selected"; }?>><?php _e("Israeli Shekel (ILS)", "donation_can");?></option>
    <option value="MXN" <?php if ($currency == 'MXN') { echo "selected"; }?>><?php _e("Mexican Peso (MXN)", "donation_can");?></option>
    <option value="BRL" <?php if ($currency == 'BRL') { echo "selected"; }?>><?php _e("Brazilian Real (only for Brazilian users) (BRL)", "donation_can");?></option>
    <option value="MYR" <?php if ($currency == 'MYR') { echo "selected"; }?>><?php _e("Malaysian Ringgits (only for Malaysian users) (MYR)", "donation_can");?></option>
    <option value="PHP" <?php if ($currency == 'PHP') { echo "selected"; }?>><?php _e("Philippine Pesos (PHP)", "donation_can");?></option>
    <option value="TWD" <?php if ($currency == 'TWD') { echo "selected"; }?>><?php _e("Taiwan New Dollars (TWD)", "donation_can");?></option>
    <option value="THB" <?php if ($currency == 'THB') { echo "selected"; }?>><?php _e("Thai Baht (THB)", "donation_can");?></option>
    <option value="TRY" <?php if ($currency == 'TRY') { echo "selected"; }?>><?php _e("Turkish Lira (TRY)", "donation_can");?></option>
</select>
