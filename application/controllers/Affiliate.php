<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Affiliate
 */
class Affiliate extends MY_Controller
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

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $data = array();
        
        //
        $this->layout_data['title'] = 'Affiliate Signup | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
        
    }
    
    public function save() {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        
        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            
            // filter ip for specific region
            $country = get_location('country', $_SERVER['REMOTE_ADDR']);
            $address = ($_POST['signup_address_address-search']);
            
            if($country == 'PK') {
                $ip_address = '';
                $country = '';
                $state = '';
                $city = '';
            } else {
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $state = get_location('region', $_SERVER['REMOTE_ADDR']);
                $city = get_location('city', $_SERVER['REMOTE_ADDR']);
            }
        
            try {
                //
                $affiliate = $_POST['signup'];

                $affiliate['signup_type'] = ROLE_1;
                $affiliate['signup_affiliate'] = 1;
                $affiliate['signup_membership_status'] = STATUS_ACTIVE;
                $affiliate['signup_password'] = password_hash($affiliate['signup_password'], PASSWORD_BCRYPT);
                $affiliate['signup_address'] = $address;
                $affiliate['signup_location_country'] = $country;
                $affiliate['signup_location_state'] = $state;
                $affiliate['signup_location_city'] = $city;
                
                $inserted = $this->model_signup->insert_record($affiliate);
                
                if($inserted) {
                    $json_param['status'] = TRUE;
                    $json_param['txt'] = SUCCESS_MESSAGE;
                }
            } catch(\Exception $e) {
                $json_param['txt'] = $e->getMessage();
            }
        } else {
            $json_param['txt'] = ERROR_MESSAGE_LINK_EXPIRED;
        }
        echo json_encode($json_param);

    }
    
}