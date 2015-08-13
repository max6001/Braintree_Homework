<!doctype html>

<?php
require_once '../lib/Braintree.php';
$clientToken = Braintree_ClientToken::generate();
?>

<html class="is-modern">
<head>
	<meta charset="utf-8">
	<title>Hello, Client!</title>
	<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
	<script src="https://js.braintreegateway.com/js/beta/braintree-hosted-fields-beta.17.js"></script>
</head>
<body>

	<h1>Transaction</h1>
	<h2>Payment with different integration</h2>
	<!-- Drop-In & PayPal -->
	<form id="btIntegration"> 
	<input type="radio" name="type" id="type_dropIn" checked="checked">Drop-In <br />
	<input type="radio" name="type" id="type_paypal">PayPal <br />
	<input type="radio" name="type" id="type_custom">Custom <br />
	<input type="radio" name="type" id="type_hosted">Hosted Fields <br />
	<input type="button" id="loadBtBtn" name='load' value="LOAD BRAINTREE" onClick='loadBT("<?php echo $clientToken; ?>")' /> 
	</form> 

	<form id="checkout" method="post" action="checkout.php">
		<div id="payment"></div>
			<input type="submit" id="submitBtn" style="visibility:hidden;" value="PAY 100">
	</form>
	
	<!-- Custom -->
	<form id="custom-form" method="post" action="checkout.php" style="visibility:hidden">
			<input data-braintree-name="number" value="4111111111111111"><br />
			<input data-braintree-name="cvv" value="100"><br />
			<input data-braintree-name="expiration_month" value="10">
			<input data-braintree-name="expiration_year" value="2020">
			<input data-braintree-name="postal_code" value="94107"><br />
			<input data-braintree-name="cardholder_name" value="John Smith"><br />
			<input type="submit" id="submitCustomBtn" value="PAY 100">
	</form>

	<!-- Hosted Fields -->
	<form id="hosted-form" method="post" action="checkout.php" style="visibility:hidden">
		  <label for="card-number">Card Number</label>
		  <div id="card-number"></div>

		  <label for="cvv">CVV</label>
		  <div id="cvv"></div>

		  <label for="expiration-date">Expiration Date</label>
		  <div id="expiration-date"></div>
		  <input type="submit" value="PAY 100" />
	</form><br>
	
	<!-- Submit for settlement -->
	<h2>Submit for settlement</h2>
	<form id="capture" method="post" action="capture.php">
		<label>Transaction ID</label><input type="text" size="6" name="trxid"><br>
		<label>Amount</label><input type="text" size="20" name="amount"><br>
		<input type="submit" value="Capture now!">
	</form><br><br>
	
	<!-- Refund -->
	<h2>Refund</h2>
	<form action="refund.php" method="POST" id="refund-form">
		<label>Transaction ID</label><input type="text" size="6" name="trxid"><br>
		<label>Amount</label><input type="text" size="20" name="amount"><br>
	<input type="submit" value="Refund now!" />
	</form><br>
	
	<!-- Void -->
	<h2>Void</h2>
	<form action="void.php" method="POST" id="void-form">
		<label>Transaction ID</label><input type="text" size="6" name="trxid"><br>
	<input type="submit" value="Void now!" />
	</form><br>
	
	<!-- Search -->
	<h2>Search</h2>
	<form action="search.php" method="POST" id="search-form">
		<label>Transaction ID</label><input type="text" size="6" name="trxid"><br>
		<label>Or Date is </label><input type="text" size="12" name="date" value="2015-02-24"><br>
		<label>Or Date is between </label><input type="text" size="12" name="startdate" value="2015-01-01">
						and <input type="text" size="12" name="enddate" value="2015-02-24"><br>
	<input type="submit" value="Search now!" />
	</form><br>
	
	<!-- Clone -->
	<h2>Clone</h2>
	<form action="clone.php" method="POST" id="clone-form">
		<label>Transaction ID</label><input type="text" size="6" name="trxid"><br>
		<label>Amount</label><input type="text" size="20" name="amount"><br>
	<input type="submit" value="Clone now!" />
	</form>
	
<script>

	function loadBT(clientToken){
		document.getElementById("btIntegration").style.visibility="hidden";
		if(document.getElementById('type_dropIn').checked){
			braintree.setup(clientToken, "dropin", {
				container: "payment"
			});
			setTimeout(function(){document.getElementById("submitBtn").style.visibility="visible"}, 3000);
		}
		else if(document.getElementById('type_paypal').checked){
			braintree.setup(clientToken, "paypal", {
				container: "payment",
				singleUse: false,
				enableShippingAddress: true,
				onPaymentMethodReceived: function (){
					alert("Salut");
				}
			});
			setTimeout(function(){
				document.getElementById("submitBtn").style.visibility="visible"}, 1000);
		}
		else if(document.getElementById("type_custom").checked){
			braintree.setup(clientToken, "custom", {
				id: "custom-form"
			});
			document.getElementById("custom-form").style.visibility="visible";
		}
		else if(document.getElementById("type_hosted").checked){
			braintree.setup(clientToken, "custom", {
				id: "hosted-form",
				hostedFields: {
					styles: {
						// Style all elements
						"input": {
						  "font-size": "16pt",
						  "color": "#3A3A3A"
						},

						// Styling a specific field
						".number": {
						  "font-family": "monospace"
						},

						// Styling element state
						":focus": {
						  "color": "blue"
						},
						".valid": {
						  "color": "green"
						},
						".invalid": {
						  "color": "red"
						},

						// Media queries
						// Note that these apply to the iframe, not the root window.
						"@media screen and (max-width: 700px)": {
						  "input": {
							"font-size": "14pt"
						  }
						}
					},
					number: { selector: "#card-number" },
					cvv: { selector: "#cvv" },
					expirationDate: { selector: "#expiration-date" },
					onFieldEvent: function (event) {
						if (event.type === "focus") {
							// alert("Focus");
						} else if (event.type === "blur") {
							// alert("blur");
						} else if (event.type === "fieldStateChange") {
							// Handle a change in validation or card type
							console.log(event.isValid); // true|false
							if (event.card) {
								console.log(event.card.type);
							//	alert(event.card.type);
							// visa|master-card|american-express|diners-club|discover|jcb|unionpay|maestro
							}
						}
					}
				}
			});
			document.getElementById("hosted-form").style.visibility="visible";
		}
	}
</script>
  
</body>
</html>