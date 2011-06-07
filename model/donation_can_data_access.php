<?php
class DonationCanDataAccess {

    var $db_version = "6.0";
    var $wpdb;

    function  __construct($wpdb) {
        $this->$wpdb = $wpdb;
    }

    function migrate_database() {

    }


}

?>
