<?php

require_once "../lib/Braintree.php";

//Send the request
$result = Braintree_Transaction::submitForSettlement($_POST["trxid"],$_POST["amount"]);

//Display the success/failure
echo "<br><br>";
if ($result->success) {
    echo("Success! Transaction ID: " . $result->transaction->id);
} else if ($result->transaction) {
    echo("Error: " . $result->message);
    echo("<br/>");
    echo("Code: " . $result->transaction->processorResponseCode);
} else {
    echo("Validation errors:<br/>");
    foreach (($result->errors->deepAll()) as $error) {
        echo("- " . $error->message . "<br/>");
    }
}

//Display the full response
echo '<pre>';
 print_r($result);
echo '</pre>';
?>
