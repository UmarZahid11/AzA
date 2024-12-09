<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Quickbook
 */
class Quickbook extends MY_Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if ($this->userid == 0) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
            redirect(l(''));
        }
    }

    /**
     * accountRequest function
     *
     * @return void
     */
    function accountRequest() : void {
        $json_param['status'] = FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if(!$this->model_quickbook_account_request->requestExists($this->userid)) {
            // create request
            $request_param = array();
            $request_param['quickbook_account_request_signup_id'] = $this->userid;
            $inserted = $this->model_quickbook_account_request->insert_record($request_param);
            if($inserted) {
                $json_param['status'] = TRUE;
                $json_param['txt'] = 'Your request has been processed successfully, the adminstrator will get back to you in 24 to 48 hours!';
            }
        } else {
            $json_param['txt'] = 'A request has already been sent to the administrator, try checking your email address.';
        }

        echo json_encode($json_param);
    }
}