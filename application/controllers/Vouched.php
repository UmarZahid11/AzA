<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Vouched
 */
class Vouched extends MY_Controller
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
     * Method index - VOUCHED_CALLBACK_URL
     *
     * @return void
     */
    public function index(): void
    {
        if(isset($_POST)) {
            log_message('ERROR', serialize($_POST));
            $update_param['signup_vouched_response'] = serialize($_POST);
            $this->model_signup->update_by_pk($this->userid, $update_param);
        }
    }
}
