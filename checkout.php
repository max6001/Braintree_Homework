<?php 

require_once '../lib/Braintree.php';

$nonce = $_POST["payment_method_nonce"];

$result = Braintree_Transaction::sale(array(
  'amount' => '100.00',
  'paymentMethodNonce' => $nonce
));

echo '<pre>';
 print_r($result);
echo '</pre>';

?>