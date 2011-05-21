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

<div class="donation-can-widget <?php echo $widget_style_id; ?>">
    <form action="<?php echo $action_url; ?>" method="post" class="donation-form">
        <input type="hidden" name="cause" value="<?php echo $goal["id"]; ?>"/>

        <?php
            foreach ($elements as $id => $element) {
                $element_object = donation_can_get_style_element_from_data($element);

                if ($element_object) {
                    echo $element_object->get_view($widget_options);
                }
            }
        ?>

    </form>

    <?php if ($show_back_link) : ?>
        <div class="backlink">
            <?php printf(__("Powered by %s."), "<a href=\"http://treehouseapps.com/donation-can\">Donation Can</a>"); ?>
        </div>
    <?php endif; ?>


</div>
