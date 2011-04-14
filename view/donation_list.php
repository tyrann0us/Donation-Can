<ul class="donations-list">
    <?php foreach ($donation_strings as $donation) : ?>
        <li><span class="donation-date"><?php echo donation_can_nicedate($donation["date"]); ?></span> <?php echo $donation["text"]; ?></li>
    <?php endforeach; ?>
</ul>
