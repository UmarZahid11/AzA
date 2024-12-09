<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Contact
 */
class Contact extends MY_Controller
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

        $param = array();
        $param['where']['inner_banner_name'] = 'Contact Us';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $param['where']['cms_page_name'] = 'Contact';
        $data['cms'] = $this->model_cms_page->find_all_active($param);

        //
        $this->layout_data['title'] = 'Contact Us | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    /**
     * save
     *
     * @return void
     */
    public function save()
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = __(ERROR_MESSAGE);

        $captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
        $secretKey = defined('CAPTCHA_SECRET_KEY') ? CAPTCHA_SECRET_KEY : '';

        if($secretKey) {
            // post request to server
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);
        } else {
            $responseKeys["success"] = TRUE;
        }

        // should return JSON with success as true
        if ($responseKeys["success"]) {

            if ($this->validate("model_inquiry")) {
                $insert_inquiry = $_POST['inquiry'];
                $inserted = $this->model_inquiry->insert_record($insert_inquiry);
                if ($inserted) {
                    $json_param['status'] = STATUS_TRUE;
                    $json_param['txt'] = __("Your query has been sent successfully!");
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['txt'] = validation_errors();
            }
        } else {
            $json_param['txt'] = ERROR_MESSAGE_CAPTCHA_FAILED;
        }

        echo json_encode($json_param);
    }
}
