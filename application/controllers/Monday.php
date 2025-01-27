<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Monday
 */
class Monday extends MY_Controller
{
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
     * index function
     *
     * @return void
     */
    public function index() {
        return redirect(MONDAY_OAUTH_URL);
    }

    /**
     * callback function
     *
     * @return void
     */
    function callback() {

        $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
        $headers = [
            // 'Authorization: Basic ' . JWT::urlsafeB64Encode(MONDAY_CLIENT_ID . ':' . MONDAY_CLIENT_SECRET),
            // 'content-type: application/x-www-form-urlencoded',
        ];

        $post_fields = array(
            'code' => $code,
            'client_id' => MONDAY_CLIENT_ID,
            'client_secret' => MONDAY_CLIENT_SECRET,
            'redirect_uri' => MONDAY_REDIRECT_URL
        );

        // create access token
        $response = $this->curlRequest(MONDAY_TOKEN_URL . '?' . http_build_query($post_fields), $headers, [], TRUE);
        $decoded_response = json_decode($response, true);
        $this->session->set_userdata('monday', $decoded_response);

        if(isset($decoded_response['access_token']) && $decoded_response['access_token']) {
            $this->session->set_flashdata('success', __('Authentication successfull!'));
            $this->session->has_userdata('monday_intended') ? redirect($this->session->userdata('monday_intended')) : redirect(l('dashboard/monday/listing'));
        } else {
            $this->session->set_flashdata('error', __('Authentication failed!'));
            redirect(l('dashboard'));
        }
    }
}