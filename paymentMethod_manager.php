<!doctype html>

<?php
require_once '../lib/Braintree.php';

if(!empty($_POST)){
	switch($_POST["action"]) {
		case "add":
			$result = Braintree_PaymentMethod::create(array(
				'customerId' => $_POST["custId"],
				'paymentMethodNonce' => 'nonce-from-the-client'
			));

			if($result->success) {
				echo ($result->paymentMethod->bin . " added for this customer<br>");
			} else {
				echo '<pre>';
				print_r($result);
				echo '</pre>';
			}
			break;
			
		case "delete":
			$result = Braintree_PaymentMethod::delete($_POST["token"]);
			
			if($result->success) {
				echo "Payment method deleted<br><br>";
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
		<label><b>Add Payment Method to an existing Customer (premade CC on Sandbox)</b></label><br>
		<label>Customer ID</label><input type="text" size="20" name="custId"><br>
		<input type="hidden" name="action" value="add">
		<input type="submit" value="Add">
	</form>
	<br>
	<form action="index.php" method="POST">
		<label><b>Delete Payment Method for an existing Customer</b></label><br>
		<label>Payment Method Token</label><input type="text" size="20" name="token"><br>
		<input type="hidden" name="action" value="delete">
		<input type="submit" value="Delete" />
	</form>
	<br>
</body>
</html>