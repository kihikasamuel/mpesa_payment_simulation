<?php

/**
 * @author root
 *on 12.20.2018
 */
class Payment
{
	
	function __construct()
	{
		// 
	}

	function getAuthentication()
	{
		$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		$credentials = base64_encode('cNJHuybnZTKGQFv34yl7Ywa9yWFtmXoX:tOMWnnvIU8FFoi29');
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //when accessing the token alone
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$curl_response = curl_exec($curl);

		$decode =  json_decode($curl_response);

		$token = $decode->access_token;// works when theres Returntransfers otherwisw just print decode to see all available options

		return $token;

	}

	// register the callback urls
	function registerUrls()
	{
		$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

		$pay = new Payment();//new object
		$accesskey = $pay->getAuthentication();

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$accesskey)); //setting custom header


		$curl_post_data = array(
		  //Fill in the request parameters with valid values
		  'ShortCode' => '600000',
		  'ResponseType' => 'Completed',
		  'ConfirmationURL' => 'http://dda89352.ngrok.io/testme/confirmation/',
		  'ValidationURL' => 'http://dda89352.ngrok.io/testme/validation_url/'
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);
		// print_r($curl_response);

		return $curl_response;
	}

	function payMethodC2B($amount = 1000, $phone = 254708374149)
	{
		$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

		$pay = new Payment();//new instance of an object
		$accesskey = $pay->getAuthentication();
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$accesskey)); //setting custom header


		$curl_post_data = array(
		      //Fill in the request parameters with valid values
		     'ShortCode' => '600000',
		     'CommandID' => 'CustomerPayBillOnline',
		     'Amount' => $amount,
		     'Msisdn' => $phone,
		     'BillRefNumber' => '00000'
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);
		// print_r($curl_response);

		return $curl_response;
	}
}

?>