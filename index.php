<?php
	require_once('payment.class.php');

	$pay = new Payment();

	// echo $token = $pay->getAuthentication();

	echo $pay->payMethodC2B();
?>