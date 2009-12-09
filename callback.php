<?php
/*
Copyright (c) 2009, Jarkko Laine.

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

// Writes a comment to the log file. Should be enabled only when debugging.
function w2log($msg) {
	if (true) {
		$fd = fopen("log", "a");
		$str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $msg; 
		fwrite($fd, $str . "\n");
		fclose($fd);
	}
}

w2log("Callback.php called");


require( dirname(__FILE__).'/../../../wp-config.php' );

//
// Verify the payment notification from PayPal. If everything is OK, save the 
// transaction to the database.
//

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
  $value = urlencode(stripslashes($value));
  $req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];

// test
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$fee = $_POST['mc_fee'];

w2log("Item name: ".$item_name);
w2log("Item number: ".$item_number);
w2log("Payment status: ".$payment_status);
w2log("Payment amount: ".$payment_amount);
w2log("Txn id: ".$txn_id);
w2log("Receiver email: ".$receiver_email);
w2log("payer email: ".$payer_email);
w2log("first name: ".$first_name);
w2log("last name: ".$last_name);
w2log("fee: ".$fee);


if (!$fp) {
	w2log("Http error, can't connect to ssl://www.paypal.com");
} else {
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {
			w2log("Transaction verified --> saving donation");

			$conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
			mysql_select_db(DB_NAME, $conn);

			w2log("DB connected");
      
			$table_name = "wp_donation_can_paypal_donations"; // TODO READ the prefix from wp!
			$sql = "INSERT INTO $table_name(transaction_id, payment_status, cause_code, amount, fee, payer_email, payer_name, time) "
				."VALUES('".$txn_id."', '".$payment_status."', '".$item_number."', '".$payment_amount."', '".$fee."', '".$payer_email."', '".$first_name." ".$last_name."', NOW());";
      		
			$results = mysql_query($sql, $conn);
			w2log("OK");
			
			// Try to notify via email
			$general_settings = get_option("donation_can_general");			
			$goals = get_option("donation_can_causes");
			$goal = $goals[$item_number];
						
			$emails = split(",", $general_settings["notify_email"]);
			$goal_emails = split(",", $goal["notify_email"]);
			
			if (!empty($emails) || !empty($goal_emails)) {
				$all_emails = array_merge($emails, $goal_emails);
				//TODO tässä välissä voisi varmistella vielä, että kaikki on oikein formatoitu...
				w2log("Sending email to: " . $all_emails);
			
				$to = join(",", $all_emails);
			
				$admin_email = get_option('admin_email'); 
			
				$subject = '[Donation Can] New Donation to ' . $item_number;
				$message = 'A new donation was made to your cause, "' . $goal["name"] . "\":\r\n\r\n"
						  . $first_name . ' ' . $last_name . ' (' . $payer_email . ') donated ' . $payment_amount . ' (PayPal fee: ' . $fee . ') to "'. $goal["name"] . '" (' . $item_number . ').'
						  . "\r\n\r\nVisit the WordPress dashboard to see all donations to this goal: \r\n" 
						  . get_bloginfo("url") . "/wp-admin/admin.php?page=goals.php" . "\r\n\r\nThanks,\r\nDonation Can";
				$headers = 'From: Donation Can <'.$admin_email.'>' . "\r\n" .
				    'Reply-To: Donation Can <'.$admin_email.'>' . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
			
				mail($to, $subject, $message, $headers);
			}
		} else if (strcmp ($res, "INVALID") == 0) {
			// TODO log more info on this into the db?
			w2log("Invalid");
		}
	}
	fclose ($fp);
}
?>