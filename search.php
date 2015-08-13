<?php

require_once "../lib/Braintree.php";

Braintree_Configuration::environment("sandbox");
Braintree_Configuration::merchantId("nx2243pjk36s3s4m");
Braintree_Configuration::publicKey("pyp9gg887p9vqqsv");
Braintree_Configuration::privateKey("4f52fee8a7cdf20f76a5c71309610031");

//Send the request
if ($_POST["trxid"]!="") {
	$result = Braintree_Transaction::find($_POST["trxid"]);
} elseif ($_POST["date"]!="") {
	$result = Braintree_Transaction::search(array(
		Braintree_TransactionSearch::createdAt()->greaterThanOrEqualTo($_POST["date"])
	));
} else {
	$result = Braintree_Transaction::search(array(
		Braintree_TransactionSearch::createdAt()->between($_POST["startdate"],$_POST["enddate"])
	));
}

//Display the full response
echo '<pre>';
 print_r($result);
echo '</pre>';
?>
