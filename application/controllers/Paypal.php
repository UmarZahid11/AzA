<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Paypal
 */
class Paypal extends MY_Controller
{
    /**
     * accessToken
     *
     * @var mixed
     */
    private $accessToken;
    
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Method getWebhooks
     *
     * @return void
     */
    function getWebhooks(): void
    {
        $url = PAYPAL_URL . PAYPAL_WEBHOOK_URL;
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

        $response = $this->curlRequest($url, $headers);
        $decoded_response = json_decode($response);
        if(property_exists($decoded_response, 'message')) {
            $json_param['message'] = $decoded_response->message;
        } else {
            dd($decoded_response);
        }
    }
    
    function onboarding()
    {
        $url = PAYPAL_URL . PAYPAL_REFERRAL_URL;
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->paypalAccessToken;

        $body = [
            'operations' => array(
                0 => array(
                    'operation' => 'API_INTEGRATION'
                )
            ),
            'legal_consents' => array(
                0 => array(
                    'type' => 'SHARE_DATA_CONSENT',
                    'granted' => TRUE
                )
            )
        ];
        
        $response = $this->curlRequest($url, $headers, $body, TRUE);
        $decoded_response = json_decode($response);
        
        echo '<pre>';
        print_r($decoded_response);
       
    }
}