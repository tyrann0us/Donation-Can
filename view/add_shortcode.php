<html>
    <head>
        <?php
            wp_admin_css( 'global' );
            wp_admin_css();
            wp_admin_css( 'media' );
            wp_admin_css( 'colors' );
            wp_admin_css( 'ie' );

            do_action('admin_print_styles');
            do_action('admin_print_scripts');
        ?>

        <title><?php _e("Add Donation Form", "donation-can");?></title>
    </head>

    <body id="media-upload">
        <div id="media-upload-header">
            <ul id="sidemenu">
                <li id="tab-type"><a class="current"><?php _e("Choose Cause", "donation-can");?></a></li>
            </ul>
        </div>
        <form action="" id="insert-donation-can-form" class="media-upload-form type-form validate">
            <h3 class="media-title"><?php _e("Add donation form", "donation-can"); ?></h3>

            <?php if ($goals == null || count($goals) == 0) : ?>

                <!-- Blank slate -->
                <p>
                    <?php _e("You haven't created any fundraising causes yet.", "donation-can"); ?>
                </p>
                <p>
                    <a target="_top" href="<?php bloginfo("url");?>/wp-admin/admin.php?page=donation_can_add_goal.php"><?php _e("Click here to create one.", "donation_can");?></a>
                    (<?php _e("Note that clicking on the link will move you out of this post editor.", "donation-can"); ?>)
                </p>

            <?php else : ?>

                <div id="media-items">
                    <div class="media-item media-blank">

                        <table border="0" cellpadding="4" cellspacing="0" class="describe">
                            <tbody>
                            <tr>
                                <th valign="top" scope="row" class="label">
                                    <span class="alignleft"><label for="goal_id">Cause</label></span>
                                </th>
                                <td class="field">
                                    <select name="goal_id">
                                        <?php foreach($goals as $id => $goal) : ?>
                                            <option value="<?php echo $id;?>"><?php echo $goal["name"];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th valign="top" scope="row" class="label">
                                    <span class="alignleft"><label for="style_id"><?php _e("Widget style", "donation_can");?></label></span>
                                </th>
                                <td class="field">
                                    <select name="style_id">
                                        <?php foreach ($styles as $style) : ?>
                                            <option value="<?php echo $style["id"];?>" <?php if ($style["id"] == $style_id) { echo "selected"; }?>><?php echo $style["name"];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="field">
                                    <div style="display:none;" id="customize-options">
                                        <table>
                                            <tr>
                                                <td><input type="checkbox" name="show_progress" checked value="1"/> <label for="show_progress">Show progress</label></td>
                                            </tr>
                                            <tr>
                                                <td><input type="checkbox" name="show_description" checked value="1"/> <label for="show_description">Show description</label></td>
                                            </tr>
                                            <tr>
                                                <td><input type="checkbox" name="show_donations" value="1"/> <label for="show_donations">Show donations</label></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="show_title" checked value="1"/> <label for="show_title">Show title</label>
                                                    <a href="#" id="show-custom-title"><?php _e("Customize title", "donation-can");?></a>
                                                    <span id="custom-title-field" style="display: none;"><input type="text" name="title"/></span>
                                                </td>
                                           </tr>
   <!--                                    <tr>
                                            <th valign="top" scope="row" class="label">
                                                <span class="alignleft"><label for="title">Title</label></span>
                                            </th>
                                            <td class="field">
                                                <input type="text" name="title" onfocus="try{this.select();}catch(e){}" />
                                            </td>
                                        </tr>-->
                                        </table>
                                    </div>

                                </td>
                            </tr>

                            <tr class="submit">
                                <td></td>
                                <td class="savesend">
                                    <input type="submit" id="insert" value="<?php _e("Insert into Post"); ?>"/>
                                    or <a href="#" id="customize-form"><?php _e("Customize form", "donation-can");?></a>

                                </td>
                            </tr>

                            </tbody>


                        </table>


                    </div>
                </div>

            <?php endif; ?>
                    
        </form>

        <script type="text/javascript">
            jQuery("#insert").click(function() {
               var win = window.dialogArguments || opener || parent || top;

               var goalId = jQuery("select[name=goal_id]").val();
               var styleId = jQuery("select[name=style_id]").val();
               var showProgress = jQuery("input[name=show_progress]").is(":checked");
               var showDescription = jQuery("input[name=show_description]").is(":checked");
               var showDonations = jQuery("input[name=show_donations]").is(":checked");
               var showTitle = jQuery("input[name=show_title]").is(":checked");
               var title = jQuery("input[name=title]").val();

               // TODO: if goal id not selected, don't allow inserting
               // TODO: what if there are no goals to select?

               var shortcodeParams = "goal_id='" + goalId + "'" +
                   " style_id='" + styleId + "'" +
                   " show_progress=" + showProgress +
                   " show_description=" + showDescription +
                   " show_donations=" + showDonations +
                   " show_title=" + showTitle +
                   " title='" + title + "'";

               win.send_to_editor("[donation-can " + shortcodeParams + "]");
            });

            jQuery("#customize-form").click(function() {
                jQuery("#customize-options").show();
                return false;
            });

            jQuery("#show-custom-title").click(function() {
                jQuery("#custom-title-field").show();
                return false;
            });
        </script>
        
    </body>
    
</html>