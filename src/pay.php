<?php
require_once 'Klarna.php';

	if (isset($_POST['amount']) && isset($_POST['payment_method'])) {
		$GLOBALS['amount'] = $_POST['amount'];
		$payment_method = $_POST['payment_method'];
		
		$output = "Amount: $ " . $GLOBALS['amount'] . "<br>";
		$output .= "Payment Method: " . $payment_method;

		$klarna = new Klarna();
		$result = $klarna->createSession($GLOBALS['amount']);
		$session_id = $result['session_id'];
		$token = $result['client_token'];
		$klarna->createHPPSession($session_id, $token);
		$order_id =$GLOBALS['order_id'];;
		$klarna->checkOrderStatus($order_id);
	} 
?>
