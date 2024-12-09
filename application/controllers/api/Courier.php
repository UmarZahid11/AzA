<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Courier
 */
class Courier extends MY_Controller
{
    /**
     * 
     */ 
    private $headers;
    
    /**
     * 
     */ 
    private $url;

    /**
     * 
     */ 
    private $MoovParcel;
    
    /**
     * 
     */ 
    private $user;

    /**
     * 
     */ 
    private $token;
    
    /**
     * 
     */ 
    private $auth_company;
    
    /**
     * 
     */ 
    private $PRESETS;
    
    /**
     * 
     */ 
    private $CREATE_LABEL;
    
    /**
     * 
     */ 
    private $MARK_SHIPMENT_CANCELLED;

    /**
     * 
     */ 
    private $PDFS_RENEW;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = 'https://production.courierapi.co.uk/api';
        $this->couriers = '/couriers/v1';
        $this->MoovParcel = $this->couriers . '/MoovParcel';
        $this->CitySprint = '/couriers/v1/CitySprint';
        $this->user = 'E Square';
        $this->token = 'urcelayqtzsighwb';
        $this->auth_company = 'E Square';

        $this->PRESETS = '/presets';
        $this->COURIER_SPECIFICS = '/courier-specifics';
        $this->CREATE_LABEL = '/create-label';
        $this->MARK_SHIPMENT_CANCELLED = '/mark-shipment-cancelled';
        $this->PDFS_RENEW = '/pdfs/renew';
        $this->GET_PRICE = '/get-price';
        $this->LIST_REGISTERED_COURIERS = '/list-registered-couriers';

        $this->headers = [
            'api-user: ' . $this->user,
            'api-token: ' . $this->token,
            'content-type: application/json'
        ];
    }

    /**
     * Method renewLabel
     * 
     * @param string $key = return from create label response
     *
     * @return void
     */
    public function renewLabel(string $key = '')
    {
        $response = $this->curlRequest($this->url . $this->PDFS_RENEW . '?key=' . $key, $this->headers);

        $decoded_response = json_decode($response);
        debug($decoded_response);
    }
    
    /**
     * Method getService
     *
     * @return void
     */
    public function getService()
    {
        $response = $this->curlRequest($this->url . $this->MoovParcel . $this->PRESETS, $this->headers);

        $decoded_response = json_decode($response);
        debug($decoded_response);
    }
    
    /**
     * Method getShipments
     *
     * @return void
     */
    public function getShipments()
    {
        $response = $this->curlRequest($this->url . '/shipments.json', $this->headers);

        $decoded_response = json_decode($response);
        debug($decoded_response);
    }
    
    /**
     * Method getCouriers
     *
     * @return void
     */
    public function getCouriers()
    {
        $response = $this->curlRequest($this->url . $this->couriers . $this->LIST_REGISTERED_COURIERS, $this->headers);

        $decoded_response = json_decode($response);
        debug($decoded_response);
    }
    
    /**
     * Method getCouriers
     *
     * @return void
     */
    public function getCouriersSpecifics()
    {
        $response = $this->curlRequest($this->url . $this->MoovParcel . $this->COURIER_SPECIFICS, $this->headers);

        $decoded_response = json_decode($response);
        debug($decoded_response);
    }
    
    
    /**
     * Method getPrice
     *
     * @return void
     */
    public function getPrice(string $dc_service_id = "DPD-12")
    {
        $requestId = bin2hex(random_bytes(32));

        $parcels = [
            0 => array(
                "dim_width" => 10,
                "dim_height" => 20,
                "dim_length" => 30,
                "dim_unit" => "cm",
                "items" => [
                    0 => array(
                        "description" => "Test Item",
                        "origin_country" => "GB",
                        "quantity" => 1,
                        "value_currency" => "GBP",
                        "weight" => 2,
                        "weight_unit" => "KG",
                        "sku" => "TestSKU",
                        "hs_code" => "50000000",
                        "value" => "0.00",
                        "extended_description" => "Test Item"
                    )
                ]
            )
        ];
        
        //"2024-08-23 18:00:00";
        $collection_date = date('Y-m-d H:i:s', strtotime('+1 day'));
        
        $ship_from = array(
            "name" => "Ross",
            "phone" => "01111111111",
            "email" => "ross@saas-ecommerce.com",
            "company_name" => "Nino Logistics",
            "address_1" => "2 Infirmary Street",
            "address_2" => "",
            "address_3" => "",
            "city" => "Leeds",
            "postcode" => "LS1 2JP",
            "county" => "",
            "country_iso" => "GB",
            "company_id" => "00000000",
            "tax_id" => "GB123456789",
            "eori_id" => "GB123456789000",
            "ioss_number" => null
        );
        
        $ship_to = array(
            "name" => "Ross Jermy",
            "phone" => "",
            "email" => "ross@saas-ecommerce.com",
            "company_name" => null,
            "address_1" => "9 Mellor Meadows",
            "address_2" => "Whittington",
            "address_3" => "",
            "city" => "Oswestry",
            "county" => "Shropshire",
            "postcode" => "SY11 4FN",
            "country_iso" => "GB",
            "tax_id" => null
        );
            
        $shipment = array(
            "label_size" => "6x4",
            "label_format" => "pdf",
            "generate_invoice" => false,
            "generate_packing_slip" => false,
            "courier" => array(
                "auth_company" => "Shop2X"
            ),
            "collection_date" => $collection_date,
            "dc_service_id" => $dc_service_id,
            "reference" => "mhtest1",
            "reference_2" => "",
            "delivery_instructions" => "",
            "ship_from" => $ship_from,
            "ship_to" => $ship_to,
            "parcels" => $parcels
        );
        
        $post_fields = [
            "auth_company" => $this->auth_company,
            "format_address_default" => true,
            "request_id" => $requestId,
            "shipment" => $shipment
        ];

        $response = $this->curlRequest($this->url . $this->CitySprint . $this->GET_PRICE, $this->headers, $post_fields);

        $decoded_response = json_decode($response);
        debug($decoded_response);
    }    
    
    /**
     * Method createLabel
     * 
     * @param string $dc_service_id
     *
     * @return void
     */
    public function createLabel(string $dc_service_id = "DPD-12") 
    {
        $requestId = bin2hex(random_bytes(32));

        $parcels = [
            0 => array(
                "dim_width" => 10,
                "dim_height" => 20,
                "dim_length" => 30,
                "dim_unit" => "cm",
                "items" => [
                    0 => array(
                        "description" => "Test Item",
                        "origin_country" => "GB",
                        "quantity" => 1,
                        "value_currency" => "GBP",
                        "weight" => 2,
                        "weight_unit" => "KG",
                        "sku" => "TestSKU",
                        "hs_code" => "50000000",
                        "value" => "0.00",
                        "extended_description" => "Test Item"
                    )
                ]
            )
        ];
        
        //"2024-08-23 18:00:00";
        $collection_date = date('Y-m-d H:i:s', strtotime('+1 day'));
        
        $ship_from = array(
            "name" => "Ross",
            "phone" => "01111111111",
            "email" => "ross@saas-ecommerce.com",
            "company_name" => "Nino Logistics",
            "address_1" => "2 Infirmary Street",
            "address_2" => "",
            "address_3" => "",
            "city" => "Leeds",
            "postcode" => "LS1 2JP",
            "county" => "",
            "country_iso" => "GB",
            "company_id" => "00000000",
            "tax_id" => "GB123456789",
            "eori_id" => "GB123456789000",
            "ioss_number" => null
        );
        
        $ship_to = array(
            "name" => "Ross Jermy",
            "phone" => "",
            "email" => "ross@saas-ecommerce.com",
            "company_name" => null,
            "address_1" => "9 Mellor Meadows",
            "address_2" => "Whittington",
            "address_3" => "",
            "city" => "Oswestry",
            "county" => "Shropshire",
            "postcode" => "SY11 4FN",
            "country_iso" => "GB",
            "tax_id" => null
        );
            
        $shipment = array(
            "label_size" => "6x4",
            "label_format" => "pdf",
            "generate_invoice" => false,
            "generate_packing_slip" => false,
            "courier" => array(
                "auth_company" => "Shop2X"
            ),
            "collection_date" => $collection_date,
            "dc_service_id" => $dc_service_id,
            "reference" => "mhtest1",
            "reference_2" => "",
            "delivery_instructions" => "",
            "ship_from" => $ship_from,
            "ship_to" => $ship_to,
            "parcels" => $parcels
        );
        
        $post_fields = [
            "auth_company" => $this->auth_company,
            "format_address_default" => true,
            "request_id" => $requestId,
            "shipment" => $shipment
        ];

        $response = $this->curlRequest($this->url . $this->MoovParcel . $this->CREATE_LABEL, $this->headers, $post_fields);

        $decoded_response = json_decode($response);
        debug($decoded_response);
    }
    
    /**
     * Method markShipmentCancelled
     * 
     * @param string $dc_request_id - id from create label response
     *
     * @return void
     */
    public function markShipmentCancelled(string $dc_request_id = '') 
    {
        $post_fields = [
        	"testing" => false,
        	"auth_company" => $this->auth_company,
        	"dc_request_id" => $dc_request_id,
            "cancel_label" => true
        ];

        $response = $this->curlRequest($this->url . $this->MoovParcel . $this->MARK_SHIPMENT_CANCELLED, $this->headers, $post_fields);

        $decoded_response = json_decode($response);
        debug($decoded_response);
    }
    
    /**
     * Method curlRequest
     *
     * @param string $url
     * @param array $headers - header data array
     * @param array $post_fields - post data array
     * @param boolean $is_post - is request a post request
     * @param boolean $is_custom_request - is request a custom request
     * @param string $custom_request_type - [patch, delete]
     * @param boolean $build_post_query
     * @param string $user_pwd
     *
     * @return ?string - (encoded)
     */
    protected function curlRequest(string $url, array $headers, array $post_fields = array(), bool $is_post = FALSE, bool $is_custom_request = FALSE, string $custom_request_type = '', $build_post_query = FALSE, $user_pwd = ''): ?string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($user_pwd) {
            curl_setopt($ch, CURLOPT_USERPWD, $user_pwd);
        }
        if ($is_custom_request) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $custom_request_type);
        }
        if (!empty($post_fields)) {
            if ($build_post_query) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
            }
        }

        if ($is_post) {
            curl_setopt($ch, CURLOPT_POST, $is_post);
        }
        $response = curl_exec($ch);
        $err = curl_error($ch);

        //
        $this->session->set_userdata('last_http_status', curl_getinfo($ch, CURLINFO_HTTP_CODE));
        log_message('error', 'URL: ' . $url . ' - last_http_status: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE));

        curl_close($ch);

        if ($err) {
            log_message('error', "cURL Error #:" . $err);
            //
            $this->_log_message(
                LOG_TYPE_GENERAL,
                LOG_SOURCE_CURL,
                LOG_LEVEL_ERROR,
                $err,
                ''
            );
            return NULL;
        } else {
            return $response;
        }
    }

}