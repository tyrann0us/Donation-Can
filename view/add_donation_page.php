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

<script type="text/javascript">
    function updateCurrency() {
        var currencies = new Array();
        <?php
        // Generate some JavaScript :)
        foreach ($causes as $goal) {
            echo "currencies['" . $goal["id"] . "'] = '" . donation_can_get_currency_for_goal($goal) . "';";
        }
        ?>

        var selectedGoalId = jQuery("#cause-selection").val();
        var currency = currencies[selectedGoalId];

        jQuery("#goal-currency").text(currency);
    }
</script>

<div class="wrap">
    <h2><?php _e("Add New Donation", "donation_can"); ?></h2>
	
    <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="add_donation" value="Y"/>

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
                                        <input type="submit" class="button-primary" id="publish" value="<?php _e("Add Donation", "donation_can");?>"/>
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
                    <div class="stuffbox">
                        <h3><label for="cause_code"><?php _e("Cause", "donation_can");?></label></h3>
                        <div class="inside" id="goal-id-div">
                            <select name="cause_code" id="cause-selection" onchange="updateCurrency();">
                                <option value=""><?php _e("--Select cause--", "donation_can"); ?></option>
                                <?php foreach ($causes as $goal) : ?>
                                    <?php
                                        if ($cause_code == $goal["id"]) {
                                            $selected = " selected ";
                                        } else {
                                            $selected = "";
                                        }
                                    ?>
                                    <option value="<?php echo $goal["id"];?>" <?php echo $selected;?>><?php echo $goal["name"];?></option>
                                <?php endforeach; ?>
                            </select>
                            <p>
                                <?php _e('Select the cause you want to add the donation to.', "donation_can");?>
                            </p>
                        </div>
                    </div>
					
                    <div class="stuffbox">
                        <h3><label for="amount"><?php _e("Amount", "donation_can");?></label></h3>
                        <div class="inside" id="amount-div">
                            <span id="goal-currency"><?php echo donation_can_get_current_currency(); ?></span> <input type="text" name="amount" value="<?php echo $amount;?>" size="30"/>
                        </div>
                    </div>

                    <div class="stuffbox">
                        <h3><?php _e("From", "donation_can");?></h3>
                        <div class="inside" id="payer_name-div">
                            <p>
                                <label for="payer_name"><?php _e("Name:", "donation_can"); ?></label>
                                <input type="text" name="payer_name" value="<?php echo $payer_name; ?>" size="30"/>
                            </p>
                            <p>
                                <label for="payer_email"><?php _e("Email:", "donation_can");?></label>
                                <input type="text" name="payer_email" value="<?php echo $payer_email; ?>" size="30"/>
                            </p>
                        </div>
                    </div>
                    
                    <div class="stuffbox">
                        <h3><label for="note"><?php _e("Notes (optional)", "donation_can");?></label></h3>
                        <div class="inside" id="note-div">
                            <textarea name="note" cols="100" rows="10"><?php echo $note; ?></textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
