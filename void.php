<?php

require_once "../lib/Braintree.php";

Braintree_Configuration::environment("sandbox");
Braintree_Configuration::merchantId("nx2243pjk36s3s4m");
Braintree_Configuration::publicKey("pyp9gg887p9vqqsv");
Braintree_Configuration::privateKey("4f52fee8a7cdf20f76a5c71309610031");

//Send the request
$result = Braintree_Transaction::void($_POST["trxid"]);

//Display the success/failure
echo "<br><br>";
if ($result->success) {
  echo $_POST["trxid"]." voided<br>";
} else {
  print_r($result->errors);
}

//Display the full response
echo '<pre>';
 print_r($result);
echo '</pre>';
?>
