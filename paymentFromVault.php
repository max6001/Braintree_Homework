<!doctype html>

<?php
require_once '../lib/Braintree.php';
 
if(!empty($_POST)){
	switch($_POST["action"]) {
		case "pay":
			if ($_POST["custId"] != "") {
				$result = Braintree_Transaction::sale(
				  array(
					'customerId' => $_POST["custId"],
					'amount' => '10.00'
				  )
				);
			} else {
				$result = Braintree_Transaction::sale(
				  array(
					'paymentMethodToken' => $_POST["pmId"],
					'amount' => '10.00'
				  )
				);
			}

			if($result->success) {
				echo("Success! Transaction ID: " . $result->transaction->id);
			} else {
				echo '<pre>';
				print_r($result);
				echo '</pre>';
			}
			break;
			
		case "search":
			$customers = Braintree_Customer::all();
			
			foreach($customers->_ids as $element) {
				$detail = Braintree_Customer::find($element);
				echo ($detail->id . " " . $detail->firstName . " " . $detail->lastName . " " . $detail->creditCards[0]->token . "<br>");
				//echo '<pre>';
				//print_r($detail);
				//echo '</pre>';
			}
			echo "<br>";
			break;
			
	}
}

?>

<html class="is-modern">
<head>
	<meta charset="utf-8">
	<title>Hello, Client!</title>
	<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
</head>
<body>
	<form action="index.php" method="POST">
		<label><b>Display all customers from the Vault</b></label><br>
		<input type="hidden" name="action" value="search">
		<input type="submit" value="Search" />
	</form>
	<br>
	<form action="index.php" method="POST">
		<label><b>Make a 10$ payment</b></label><br>
		<label>Customer ID </label><input type="text" size="20" name="custId"><br>
		<label>Or Payment method ID </label><input type="text" size="20" name="pmId"><br>
		<input type="hidden" name="action" value="pay">
		<input type="submit" value="Pay" />
	</form>
</body>
</html>