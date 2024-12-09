<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Newsletter
 */
class Newsletter extends MY_Controller
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
     * subscribeNewsletter
     *
     * @return void
     */
    public function subscribeNewsletter()
    {
        if ($this->validate("model_newsletter")) {
            $insert_newsletter = $_POST['newsletter'];
            $inserted = $this->model_newsletter->insert_record($insert_newsletter);
            if ($inserted) {
                $json_param['status'] = STATUS_TRUE;
                $json_param['txt'] = __("Newsletter subscription successful!");
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __("Error occurred while processing your request!");
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = validation_errors();
        }
        echo json_encode($json_param);
    }
}
