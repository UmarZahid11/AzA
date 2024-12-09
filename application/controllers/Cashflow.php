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
		$url = 'https://gateway-int.cashflows.com/api/gateway/payment-jobs';
		// $url = 'https://gateway-int.cashflows.com/api/gateway/supported-payment-methods';

		$GET = FALSE;

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
					"orderNumber"=> "Payment ref D1" . time()
				],
				"currency"=> "GBP",
				"amountToCollect"=> "10.00"
			);
		}

		$api_key = '10074c13-4ff3-4728-a423-1b1b90e62911';
		$body = (!empty($post_fields) ? json_encode($post_fields) : '');
		//
		if(!$GET) {
			$hash = $api_key;
		} else {
			$hash = $api_key . $body;
		}
		$hash = strtoupper(bin2hex(hash('sha512', $hash, true)));

        $headers = array(
			'Content-Type:application/json',
            'ConfigurationId:230826117364418560',
            'Hash:' . $hash,
        );

		if(!$GET) {
			$response = $this->curlRequest($url, $headers, $post_fields, TRUE);
		} else {
			$response = $this->curlRequest($url, $headers);
		}
		debug($response);
	}

	function paymentIntent() {
		// First thing we need to do is create a payment intent using an API call to Cashflows. For that, we need
		// the API credentials obtained from the portal.
		$sConfigurationId = '230826117364418560';
		$sApiKey = '10074c13-4ff3-4728-a423-1b1b90e62911';

		// Choose the integration or production API.
		$sApiUrl = 'https://gateway-int.cashflows.com/api/gateway/payment-intents/';
		// $sApiUrl = 'https://gateway.cashflows.com/api/gateway/payment-intents/';

		$iAmount = isset($_GET['amount']) ? $_GET['amount'] : 10.99;
		$sCurrency = isset($_GET['currency']) ? $_GET['currency'] : 'GBP';

		// A create payment intent request is a POST request to the API using JSON as the body, which we're going to
		// prepare below.
		$aCreatePaymentJobRequest = [
			'configurationId' => $sConfigurationId,
			'amountToCollect' => number_format($iAmount, 2),
			'currency' => $sCurrency,
			'locale' => 'en_GB',
			'paymentMethodsToUse' => [ 'Card' ]
		];

		$jCreatePaymentJobRequest = json_encode($aCreatePaymentJobRequest);
		$sHash = strtoupper(bin2hex(hash('sha512', $sApiKey . $jCreatePaymentJobRequest, true)));

		// The actual call to the API requires the headers to be set up according to the documentation.
		$aOptions = [
			'http' => [
				'ignore_errors' => false,
				'header' =>
					"Content-type: application/json\r\n" .
					"ConfigurationId: {$sConfigurationId}\r\n" .
				"Hash: {$sHash}\r\n",
				'method' => 'POST',
				'content' => $jCreatePaymentJobRequest
			]
		];
		$oContext = stream_context_create($aOptions);
		$sResults = file_get_contents($sApiUrl, false, $oContext);

		// We should have a valid payment intent now. A payment intent should contain a token which we need to
		// initialise the iframe solution.
		if (
			!$sResults
			|| !($aResults = json_decode($sResults, TRUE))
			|| !isset($aResults['data']['paymentJobReference'])
			|| !isset($aResults['data']['token'])
		) {
			die('Error during creation of a payment job using the Cashflows API.');
		}

		$sPaymentJobReference = $aResults['data']['paymentJobReference'];
		$sToken = $aResults['data']['token'];
		$sCurrency = $aCreatePaymentJobRequest['currency'];
		$sAmount = $aCreatePaymentJobRequest['amountToCollect'];

		debug($aResults);
	}
}