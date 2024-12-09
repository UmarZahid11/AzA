<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cashflow
 */
class Cashflow extends MY_Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

	function index() {
		// $url = 'https://gateway-int.cashflows.com/api/gateway/payment-jobs';
		$url = 'https://gateway-int.cashflows.com/api/gateway/supported-payment-methods';

		$GET = TRUE;

        $post_fields = array();

		if(!$GET) {
			$post_fields = array(
				"type"=> "Payment",
				"paymentMethodsToUse"=> ["creditcard"],
				"parameters"=> [
					"cardNumber"=>"4000000000000002",
					"cardCvc"=> "123",
					"cardExpiryMonth"=> "05",
					"cardExpiryYear"=> "24"
				],
				"order"=> [
					"orderNumber"=> "Payment ref D1"
				],
				"currency"=> "GBP",
				"amountToCollect"=> "10.00"
			);
		}

		$api_key = '10074c13-4ff3-4728-a423-1b1b90e62911';
		$body = (!empty($post_fields) ? json_encode($post_fields) : '');
		//
		if($GET) {
			$hash = $api_key;
		} else {
			$hash = $api_key . $body;
		}
		$hash = hash('sha512', $hash);

        $headers = array(
			'Version: 1.1',
            'ConfigurationId:230826117364418560',
            'Hash:' . $hash,
			'Content-Type: application/json'
        );

		if(!$GET) {
			$response = $this->curlRequest($url, $headers, $post_fields, TRUE);
		} else {
			$response = $this->curlRequest($url, $headers);
		}
		debug($response);
	}
}