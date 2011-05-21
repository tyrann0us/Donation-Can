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
    function delete_style(id, name) {
        var agree = confirm("<?php _e("Are you sure you want to delete style '", "donation_can");?>" + name + "'?");
        if (agree) {
            jQuery("#remove_style_form > input[name=remove_style]").val(id);
            jQuery("#remove_style_form").submit();
        }
        return false;
    }
</script>

<div class="wrap">
    <h2><?php _e("Donation Widget Styles", "donation_can"); ?></h2>

    <?php if ($widget_styles == null || count($widget_styles) == 0) : ?>

        <!-- Blank slate -->
        <div class="donation-can-blank-slate">
            <?php _e("Add a new style to get started with collecting donations.", "donation-can"); ?>
        </div>

    <?php else : ?>

        <form method="post" id="remove_style_form" action="<?php echo get_settings('siteurl'); ?>/wp-admin/admin.php?page=donation_can_widget_styles.php">
            <input type="hidden" name="remove_style" value=""/>
        </form>

        <table class="widefat fixed" cellspacing="0" style="margin-top: 8px;">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column"><!--<input type="checkbox"/>--></th>
                    <th scope="col" class="manage-column column-title"><?php _e("Style", "donation_can");?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column"><!--<input type="checkbox"/>--></th>
                    <th scope="col" class="manage-column column-title"><?php _e("Style", "donation_can");?></th>
                </tr>
            </tfoot>

            <tbody>
                <!-- List causes -->
                <?php foreach ($widget_styles as $id => $style) : ?>
                    <tr id="style-row-<?php echo $id; ?>" <?php if ($style["locked"]) : ?>class="donation-can-locked"<?php endif;?>>
                        <th scope="row" class="check-column"><!--<input type="checkbox"/>--></th>
                        <td>
                            <a class="row-title" href="<?php echo get_settings('siteurl'); ?>/wp-admin/admin.php?page=donation_can_edit_widget_style.php&style_id=<?php echo $id; ?>"><?php echo $style["name"]; ?></a>

                            <div class="row-actions">
                                <span class="edit"><a href="<?php echo get_settings('siteurl'); ?>/wp-admin/admin.php?page=donation_can_edit_widget_style.php&style_id=<?php echo $id; ?>"><?php echo $style["locked"] ? __("View", "donation_can") : __("Edit", "donation_can"); ?></a></span>

                                <?php if (!$style["locked"]) : ?>
                                    <span class="delete"> | <a href="#" onclick="return delete_style('<?php echo $id; ?>', '<?php echo $style['name'];?>');"><?php _e("Delete");?></a></span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
		<?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

    <p>
        <a class="button" href="<?php echo get_settings('siteurl'); ?>/wp-admin/admin.php?page=donation_can_edit_widget_style.php"><?php _e("Add style", "donation-can");?></a>
    </p>

</div>