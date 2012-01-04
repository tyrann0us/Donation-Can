<?php
define('DONATION_STATUS_UNKNOWN', 0);
define('DONATION_STATUS_COMPLETED', 1);
define('DONATION_STATUS_PENDING', 2);
define('DONATION_STATUS_REFUNDED', 10);
define('DONATION_STATUS_FAILED', 11);
define('DONATION_STATUS_REFUND_CANCELLED', 12);

class DonationCanDonation {

    var $item_number;
    var $cause_code;
    var $transaction_id;
    var $status;
    var $amount;
    var $fee;
    var $payer_email;
    var $payer_first_name;
    var $payer_last_name;
    var $mysql_time;
    var $sandbox;

    /**
     *
     * @param string $item_number
     * @param string $transaction_id
     * @param string $status
     * @param float $amount
     * @param float $fee
     * @param string $payer_email
     * @param string $payer_first_name
     * @param string $payer_last_name
     * @param string $mysql_time
     */
    function __construct($item_number, $transaction_id, $status, $cause_code,
            $amount, $fee, $payer_email, $payer_first_name, $payer_last_name, $mysql_time) {
        
        // Save the data
        $this->cause_code = $cause_code;
        $this->item_number = $item_number;
        $this->transaction_id = $transaction_id;
        $this->amount = $amount;
        $this->fee = $fee;
        $this->payer_email = $payer_email;
        $this->payer_first_name = $payer_first_name;
        $this->payer_last_name = $payer_last_name;
        $this->mysql_time = $mysql_time;

        $this->status = $status;
    }

    function getItemNumber() {
        return $this->item_number;
    }

    function setCauseCode($cause_code) {
        $this->cause_code = $cause_code;
    }

    function getCauseCode() {
        return $this->cause_code;
    }

    function isChange() {
        return ($this->isRefund() || $this->status == DONATION_STATUS_REFUND_CANCELLED);
    }

    function isRefund() {
        return ($this->status == DONATION_STATUS_REFUNDED);
    }

    function isCompleted() {
        return ($this->status == DONATION_STATUS_COMPLETED);
    }

    function isPending() {
        return ($this->status == DONATION_STATUS_PENDING);
    }

    function setAmount($amount) {
        $this->amount = $amount;
    }

    function getAmount() {
        return $this->amount;
    }

    function setFee($fee) {
        $this->fee = $fee;
    }

    function getFee() {
        return $this->fee;
    }

    function setSandbox($value) {
        $this->sandbox = $value;
    }

    function getDataAsArray() {
        $data = array(
            "item_number" => $this->item_number,
            "transaction_id" => $this->transaction_id,
            "payment_status" => $this->status,
            "time" => $this->mysql_time,
            "payer_email" => $this->payer_email,
            "payer_name" => $this->payer_first_name,
            "payer_lastname" => $this->payer_last_name,
            "cause_code" => $this->cause_code,
            "amount" => $this->amount,
            "fee" => $this->fee,
            "sandbox" => $this->sandbox ? 1 : 0
        );
        $types = array(
            "%s",
            "%s",
            "%s",
            "%s",
            "%s",
            "%s",
            "%s",
            "%s",
            "%f",
            "%f",
            "%d"
        );

        // Write to log for saving
        foreach ($data as $k => $v) {
            w2log("$k: $v");
        }

        return array("data" => $data, "types" => $types);
    }

}
?>