<?php if ($donation_strings) : ?>
    <div class="donations-list-container">
        <div class="donations-list-inner">
            <?php if ($show_donation_list_title) : ?>
                <span class="donation-list-title"><?php _e("Latest donations:", "donation_can"); ?></span>
            <?php endif; ?>
            <ul class="donations-list">
                <?php foreach ($donation_strings as $donation) : ?>
                    <li><span class="donation-date"><?php echo donation_can_nicedate($donation["date"]); ?></span> <?php echo $donation["text"]; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
