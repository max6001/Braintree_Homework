<!doctype html>

<?php
require_once '../lib/Braintree.php';

if(!empty($_POST)){
	switch($_POST["action"]) {
		case "create":
			$result = Braintree_Customer::create(array(
				'firstName' => $_POST["firstName"],
				'lastName' => $_POST["lastName"],
				'company' => $_POST["company"],
				'email' => $_POST["email"],
				'phone' => $_POST["phone"],
				'fax' => $_POST["fax"],
				'website' => $_POST["website"]
			));

			if($result->success) {
				echo ($result->customer->id . " created<br><br>");
			} else {
				echo '<pre>';
				print_r($result);
				echo '</pre>';
			}
			break;
			
		case "delete":
			$result = Braintree_Customer::delete($_POST["custId"]);
			
			if($result->success) {
				echo "Customer deleted<br><br>";
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
				echo ($detail->id . " " . $detail->firstName . " " . $detail->lastName . " " . $detail->creditCards[0]->bin . "<br>");
				//echo '<pre>';
				//print_r($detail);
				//echo '</pre>';
			}
			echo "<br>";
			break;
			
		case "update":
			$result = Braintree_Customer::update(
				$_POST["custId"],
				array(
				'firstName' => $_POST["firstName"],
				'lastName' => $_POST["lastName"],
				'company' => $_POST["company"],
				'email' => $_POST["email"],
				'phone' => $_POST["phone"],
				'fax' => $_POST["fax"],
				'website' => $_POST["website"]
			));
			
			if($result->success) {
				echo ($result->customer->id . " updated<br><br>");
			} else {
				echo '<pre>';
				print_r($result);
				echo '</pre>';
			}
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
		<label><b>Create Customer</b></label><br>
		<label>First name</label><input type="text" size="20" name="firstName"><br>
		<label>Last name</label><input type="text" size="20" name="lastName"><br>
		<label>Company</label><input type="text" size="20" name="company"><br>
		<label>Phone number</label><input type="text" size="40" name="phone"><br>
		<label>Fax number</label><input type="text" size="40" name="fax"><br>
		<label>Website</label><input type="text" size="40" name="website"><br>
		<label>Email</label><input type="text" size="40" name="email"><br><br>
		<input type="hidden" name="action" value="create">
		<input type="submit" value="Create">
	</form>
	<br>
	<form action="index.php" method="POST">
		<label><b>Delete Customer</b></label><br>
		<label>Customer ID</label><input type="text" size="20" name="custId"><br>
		<input type="hidden" name="action" value="delete">
		<input type="submit" value="Delete" />
	</form>
	<br>
	<form action="index.php" method="POST">
		<label><b>Display all customers from the Vault</b></label><br>
		<input type="hidden" name="action" value="search">
		<input type="submit" value="Search" />
	</form>
	<br>
	<form action="index.php" method="POST">
		<label><b>Edit Customer</b></label><br>
		<label>ID</label><input type="text" size="20" name="custId"><br>
		<label>First name</label><input type="text" size="20" name="firstName"><br>
		<label>Last name</label><input type="text" size="20" name="lastName"><br>
		<label>Company</label><input type="text" size="20" name="company"><br>
		<label>Phone number</label><input type="text" size="40" name="phone"><br>
		<label>Fax number</label><input type="text" size="40" name="fax"><br>
		<label>Website</label><input type="text" size="40" name="website"><br>
		<label>Email</label><input type="text" size="40" name="email"><br><br>
		<input type="hidden" name="action" value="update">
		<input type="submit" value="Update">
	</form>
</body>
</html>