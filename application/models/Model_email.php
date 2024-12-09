<?php

/**
 * Model_email
 */
class Model_email extends MY_Model
{
    /**
     * from
     *
     * @var string
     */
    private $from = 'mikejason014@gmail.com';

    /**
     * to
     *
     * @var string
     */
    private $to = 'mikejason014@gmail.com';

    /**
     * _template
     *
     * @var string
     */
    private $_template = 'email_template';

    /**
     * subject
     *
     * @var string
     */
    private $subject;

    /**
     * msg
     *
     * @var string
     */
    private $msg;

    /**
     * customerSupportEmail
     *
     * @var mixed
     */
    private $customerSupportEmail;

    function __construct()
    {
        parent::__construct();
        $this->customerSupportEmail = g('db.admin.support_email') ?? 'info@azaverze.com';
        self::_set();
    }

    /**
     * Method _set
     *
     * @return void
     */
    function _set()
    {
        // set from email
        $this->from = $this->_set_email();

        // set template
        $this->_template = 'email_template';
    }

    /**
     * Method _set_email
     *
     * @return void
     */
    private function _set_email()
    {
        $this->load->model('model_config');

        $config_info = $this->model_config->find_by_pk(13);

        if (isset($config_info) && array_filled($config_info))
            return $config_info['config_value'];
        else
            return 'infodemolink1@gmail.com';
    }

    /**
     * Method _set_to_email
     *
     * @return void
     */
    private function _set_to_email()
    {
        $this->load->model('model_config');

        $config_info = $this->model_config->find_by_pk(CONFIG_SUPPORT_EMAIL);

        if (array_filled($config_info)) {
            return $config_info['config_value'];
        } else {
            return 'info@azaverze.com';
        }
    }

    /**
     * Method email
     *
     * @param $send_to $send_to
     * @param $send_from $send_from
     * @param $subject $subject
     * @param $msg $msg
     *
     * @return void
     */
    public function email($send_to = '', $send_from = '', $subject = '',  $msg = '')
    {
        if (ENVIRONMENT == 'development') {
            // echo $send_to . "<br />";
            // echo $send_from . "<br />";
            // echo $subject . "<br />";
            // echo $msg . "<br />";
            // die();
        }
        parent::email($send_to, $send_from, $subject, $msg);
    }

    /**
     * Method client_email
     *
     * @param $to $to
     * @param $template $template
     * @param $title $title
     *
     * @return void
     */
    public function client_email($to, $template, $title)
    {
        $this->load->library('email');

        $db_to = g("db.admin.sales_email");
        $send_from = $this->from;
        $name = g('site_name');

        $send_to = $this->to;
        $title = 'Demo';

        $message = $template;

        $this->email->from($send_from);
        $this->email->to($send_to);
        $this->email->subject($title);
        $this->email->set_mailtype("html");
        $this->email->message($message);
        //$this->email->protocol('smtp');
        $this->email->send();
    }

    /**
     * Method notification_delete_user
     *
     * @param $userID $userID
     *
     * @return void
     */
    public function notification_delete_user($userID)
    {
        $param['fields'] = 'signup_id,signup_fname,signup_lname,signup_email';
        $user_data = $this->model_signup->find_by_pk($userID, false, $param);

        $this->_notification_delete_user('user', $user_data);
        $this->_notification_delete_user('admin', $user_data);
    }

    /**
     * Method _notification_delete_user
     *
     * @param $type $type
     * @param $user_data $user_data
     *
     * @return void
     */
    public function _notification_delete_user($type = 'user', $user_data = array())
    {
        $message = '';

        if ($type == 'user') {
            $message .= 'Dear ' . ucfirst($user_data['signup_fname'] . ' ' . $user_data['signup_lname']) . "<br />";
            $message .= "Your account has been deleted in our website.<br />";
            $to = $this->to;
            $from = $this->from;
        } else {
            $message .= "Dear Admin<br />";
            $message .= "One account has been deleted in website.<br />";
            $to = $this->from;
            $from = $this->from;
        }

        $param['form_input']['id'] = $user_data['signup_id'];
        $param['form_input']['first_name'] = ucfirst($user_data['signup_fname']);
        $param['form_input']['last_name'] = ucfirst($user_data['signup_lname']);
        $param['form_input']['email'] = $user_data['signup_email'];
        $param['form_input']['status'] = 'Account Delete';

        $subject = "User account Deleted";
        $message .= "Thanks & Regards <br />";
        $param['form_input']['comments'] = $message;
        $msg = $this->load->view('_layout/email_template/' . $this->_template, $param, true);

        parent::email_structure($to, $from, $subject, $msg);
    }

    /**
     * fire_email - Generic Email Template
     *
     * @param  string $to
     * @param  string $from
     * @param  string $subject
     * @param  string $message
     *
     * @return void
     */
    public function fire_email($to, $from, $subject, $message)
    {
        global $config;

        $to = ($to != NULL ? $to : '');
        $from = $this->_set_to_email();
        $subject = ($subject != NULL ? $subject : $config['title']);
        $message = ($message != NULL ? $message : $config['title']);

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <' . $from . '>' . "\r\n";

        $param = array();
        $param['msg'] = $message;
        $param['logo'] = $this->model_logo->find_one_active();

        $message = $this->load->view('_layout/email_template/email_template', $param, true);

        parent::email($to, $from, $subject, $message);
    }

    /**
     * fire_email - Generic Email Template
     *
     * @param  string $to
     * @param  string $from
     * @param  string $subject
     * @param  string $message
     *
     * @return void
     */
    public function fireEmail($to, $from, $subject, $message, $title, $form_input)
    {
        global $config;

        $to = ($to != NULL ? $to : '');
        $from = g('db.admin.email') ?? $this->customerSupportEmail;
        $subject = ($subject != NULL ? $subject : $config['title']);
        $message = ($message != NULL ? $message : $config['title']);

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <' . $from . '>' . "\r\n";

        $param = array();
        $param['msg'] = $message;
        $param['logo'] = $this->model_logo->find_one_active();
        $param['title'] = $title;
        $param['form_input'] = $form_input;

        $message = $this->load->view('_layout/email_template/email_template', $param, true);

        parent::email($to, $from, $subject, $message);
    }

    /**
     * Method generate_token
     *
     * @param int $signup_id
     *
     * @return void
     */
    private function generate_token($signup_id)
    {
        // Remove old token if exist
        $where_params['where']['token_user_id'] = $signup_id;
        $data = array(
            'token_status' => STATUS_INACTIVE
        );
        $this->model_token->update_model($where_params, $data);
        // Generate token
        $token = md5(time());
        $data = array(
            'token_user' => $token,
            'token_user_id' => $signup_id,
            'token_status' => STATUS_ACTIVE
        );
        // Save token
        $this->model_token->set_attributes($data);
        $this->model_token->save();

        return $token;
    }

    /**
     * Method inquiry_email
     *
     * @param array $user_data
     *
     * @return void
     */
    public function inquiry_email($user_data)
    {
        $message = '';

        $message .= 'Dear ' . ucfirst($user_data['inquiry_fullname']) . "<br />";
        $message .= "Thank you for your Inquiry. We will contact you shortly. <br/>";
        $to = $user_data['inquiry_email'];
        $from = g('db.admin.support_email');

        $subject = g('site_name') . " - Inquiry";


        $message .= "<br /> Thanks & Regards <br />";
        $message .= g('site_name') . "<br />";

        //$param['form_input']['comments'] = $message;
        $user_data['message'] = $message;


        $msg = $this->load->view('_layout/email_template/inquiry', $user_data, true);

        parent::email_structure($to, $from, $subject, $msg);
    }

    /**
     * Method reset_password
     *
     * @param array $data
     * @param string $token
     *
     * @return void
     */
    public function reset_password($data, $token)
    {
        $this->from = $this->_set_to_email();
        $name = $data['signup_firstname'] . " " . $data['signup_lastname'];

        $this->to = $data['signup_email'];

        $url = g('base_url') . "user/forgot-password?id=" . $data['signup_id'] . "&token=" . $token;

        $content = "$name, <br />";
        $content .= 'We received a password reset request from ' . $this->to . '. <br />';
        $content .= 'To reset your ' . g('site_name') . ' account password please <a href="' . $url . '">click here</a> <br />';
        $content .= 'If this activity occurred without your knowledge or permission, we would appreciate your notifying us at ' . $this->customerSupportEmail . '<br />';
        $content .= 'Thank you <br />';
        $content .= g('site_name') . ' Team';

        $param['msg'] = $content;

        $this->msg = $this->load->view('_layout/email_template/' . $this->_template, $param, true);
        $this->subject = g('site_name') . " - Password Reset Email";

        parent::email($this->to, $this->from, $this->subject, $this->msg);

        return true;
    }

    /**
     * Method notification_register
     *
     * @param $id $id
     * @param $type='user' $type [user, admin]
     *
     * @return void
     */
    public function notification_register($id, $type = 'user')
    {
        $this->subject = g('site_name') . ' - Account registration';
        $token = md5("REG-" . $id . "GEF");
        $data = $this->model_signup->find_by_pk($id);

        $user_type = $this->model_signup->getRole() ?? 'new user';
        if ($type == 'user') {
            $this->from = $this->_set_to_email();
            $this->to = $data['signup_email'];

            $url = g('base_url') . "signup/authentication/$id/?token=$token";

            $content = 'Thanks for joining ' . g('site_name') . '. We are ready to activate your account but want to verify your email
                    address and the authenticity of your request first. Our records show the email of ' . $data['signup_email'] . '
                    registered for a ' . g('site_name') . ' account on ' . date("m/d/Y g:i a", strtotime($data['signup_createdon'])) . '<br /><br />';

            $content .= 'Please <a href="' . $url . '">click here</a> to confirm your intention to register on ' . g('site_name') . '.<br /><br />';
            $content .= 'Your confirmation enables you to access and utilize all features on ' . g('site_name') . '.
                        If this activity occurred without your knowledge or permission, we would appreciate your notifying
                        us at ' . $this->customerSupportEmail . '<br /><br />';

            $param['msg'] = $content;
        } else {

            $this->from = $this->customerSupportEmail;
            $this->to = $this->_set_to_email();

            $param['title'] = 'User Detail';
            $content = 'Dear site administrator, A ' . $user_type . ' has been registered on your website.';
            $param['msg'] = $content;
            $param['form_input']['firstname'] = htmlentities(trim($data['signup_firstname']));
            $param['form_input']['lastname'] = htmlentities(trim($data['signup_lastname']));
            $param['form_input']['email'] = htmlentities(trim($data['signup_email']));
            $param['form_input']['status'] = ($data['signup_status'] == 1 ? 'ACTIVE' : 'IN-ACTIVE');
        }

        $this->msg = $this->load->view('_layout/email_template/' . $this->_template, $param, true);

        $this->email($this->to, $this->from, $this->subject, $this->msg);

        return true;
    }

    /**
     * Method _new_job_email - used in custom.php
     *
     * @param string $subscribe_emails
     * @param array $job_data
     * @param string $url
     * @param string $company_name
     *
     * @return void
     */
    public function _new_job_email($subscribe_email, $url, $company_name)
    {
        $this->subject = g('site_name') . ' - Job Opportunity';

        $this->from = $this->_set_to_email();
        $this->to = $subscribe_email;;

        $content = 'A new job has been posted by ' . $company_name . ' on ' . g('site_name') . '. <a href="' . $url . '">Click here</a> to view.';

        $param['msg'] = $content;

        $this->msg = $this->load->view('_layout/email_template/' . $this->_template, $param, true);

        $this->email($this->to, $this->from, $this->subject, $this->msg);
    }

    /**
     * Method notification_job_application
     *
     * @param string $job_organizer_email
     * @param string $job_name
     * @param string $applicant_name
     * @param string $url
     *
     * @return void
     */
    public function notification_job_application($job_organizer_email, $job_name, $applicant_name, $url): void
    {
        $this->subject = g('site_name') . ' New Job Application';

        $this->from = $this->_set_to_email();
        $this->to = $job_organizer_email;

        $content = 'A new job application has been posted by ' . $applicant_name . ' for job "' . $job_name . '". <a href="' . $url . '">Click here</a> to view.';

        $param['msg'] = $content;

        $this->msg = $this->load->view('_layout/email_template/' . $this->_template, $param, true);

        $this->email($this->to, $this->from, $this->subject, $this->msg);
    }

    /**
     * Method notification_job_application
     *
     * @param string $job_applicant_email
     * @param string $content
     *
     * @return void
     */
    public function notification_job_application_action($job_applicant_email, $content): void
    {
        $this->subject = g('site_name') . ' Job Application Notification';

        $this->from = $this->_set_to_email();
        $this->to = $job_applicant_email;

        $param['msg'] = $content;

        $this->msg = $this->load->view('_layout/email_template/' . $this->_template, $param, true);

        $this->email($this->to, $this->from, $this->subject, $this->msg);
    }

    /**
     * Method notification_job_milestone
     *
     * @param string $job_organizer_email
     * @param string $job_name
     * @param string $applicant_name
     * @param string $url
     *
     * @return void
     */
    public function notification_job_milestone($job_applicant_email, $job_name, $organizer_name, $url): void
    {
        $this->subject = g('site_name') . ' New Job Milestone';

        $this->from = $this->_set_to_email();
        $this->to = $job_applicant_email;

        $content = 'A new job milestone has been addded to to job "' . $job_name . '" by ' . $organizer_name . '. <a href="' . $url . '">Click here</a> to view.';

        $param['msg'] = $content;

        $this->msg = $this->load->view('_layout/email_template/' . $this->_template, $param, true);

        $this->email($this->to, $this->from, $this->subject, $this->msg);
    }

    /**
     * Method notification_charge_receipt
     *
     * @param string $job_organizer_email
     * @param string $content
     *
     * @return void
     */
    public function notification_charge_receipt($job_organizer_email, $message)
    {
        $this->to = $job_organizer_email;
        $this->subject = g('site_name') . ' Milestone Payment Receipt';
        $this->from = $this->_set_to_email();

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <' . $this->from . '>' . "\r\n";

        // $matches with body of stripe receipt
        mail($this->to, $this->subject, $message, $headers);
    }

    /**
     * Method notification_order_charge_receipt
     *
     * @param string $job_organizer_email
     * @param string $content
     *
     * @return void
     */
    public function notification_order_charge_receipt($job_organizer_email, $message, $subject)
    {
        $this->to = $job_organizer_email;
        $this->subject = $subject;
        $this->from = $this->_set_to_email();

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <' . $this->from . '>' . "\r\n";

        // $matches with body of stripe receipt
        mail($this->to, $this->subject, $message, $headers);
    }


    /**
     * Method notification_order_invoice
     *
     * @param $order_id $order_id
     * @param $type $type
     *
     * @return void
     */
    public function notification_order_invoice($order_id = 0, $type = 'USER')
    {
        $id = intval($order_id);

        $order_data = $this->model_order->find_by_pk($id);

        //ADD SHIPPING AMOUNT
        $param['order_tax'] = isset($order_data['order_tax_amount']) ? $order_data['order_tax_amount'] : 0;

        $param['order_discount'] = isset($order_data['order_discount_amount']) ? $order_data['order_discount_amount'] : 0;
        $param['discount_amount'] = $param['order_discount'];

        $param['order_shipping_amount'] = isset($order_data['order_shipping_amount']) ? $order_data['order_shipping_amount'] : 0;
        $param['shipping_amount'] = $param['order_shipping_amount']; // just for extra use

        $param['type'] = $type;

        //ADD USER EMAIL ID's
        $user_email[] = isset($order_data['order_email']) ? $order_data['order_email'] : '';
        $user_email[] = $order_data['order_shipping_email'];
        $param['user_email'] = implode(",", array_unique($user_email));

        //GET EMAIL FROM SAVE IN DB
        $db_param = array();
        $db_param['where_string'] = 'config_id = ' . ADMIN_EMAIL_CONFIG_ID;
        $admin_email = $this->model_config->find_all_list_active($db_param, 'config_value');

        $param['inquiry_email'] = $this->_set_to_email();

        // SOME TYPE WISE DATA
        if ($type == 'USER') {
            $param['name'] = $order_data['order_firstname'] . ' ' . $order_data['order_lastname'];
            $param['email'] = $param['user_email'];
        } else {
            $param['name'] = 'Admin';
            // $param['email'] = $confirmation_email;
        }

        $param['billing_address'] = $order_data['order_address1'];

        if (!empty($order_data['order_billing_city']))
            $param['billing_address'] .= ', ' . $order_data['order_city'];

        if (!empty($order_data['order_billing_state']))
            $param['billing_address'] .= ' ' . $order_data['order_state'];

        if (!empty($order_data['order_billing_zip_code']))
            $param['billing_address'] .= ' ' . $order_data['order_zip'];


        $fields = $this->model_order->get_fields('order_payment_status');
        $param['payment_status'] = $fields['list_data'][$order_data['order_payment_status']];

        $param['order_no'] = order_no($id);

        // GET ORDER ITEM
        $oi_param['where']['membership_id'] = $order_data['order_reference_id'];
        $membership = $this->model_membership->find_all_active($oi_param);

        // cost per month
        // COST_ATTRIBUTE = 2, from databse, table = fb_membership_attribute
        // $membership_cost = ($this->model_membership_pivot->raw_pivot_value($membership[0]['membership_id'], COST_ATTRIBUTE));

        $i = 1;
        $total_invoice_amount = 0;
        foreach ($membership as $membership_value) {
            $param['product'][$i]['product_name'] = ($membership_value['membership_id'] == ROLE_3 ? MEMBERSHIP_PRODUCT_ENTREPRENEUR_TITLE : $membership_value['membership_title']);

            $param['product'][$i]['product_qty'] = 1;

            $param['product'][$i]['product_rate'] = $order_data['order_amount'];

            $param['product'][$i]['product_total'] = $order_data['order_total'];

            $total_invoice_amount += $order_data['order_total'];

            $i++;
        }

        $param['test_mode'] = false;
        $param['order_total'] = $total_invoice_amount;

        $result['total_order_amount'] = ($param['order_total'] + $param['order_tax'] + $param['order_shipping_amount']) - ($param['discount_amount']);

        $result['data'] = $param;

        $logo = $this->model_logo->find_one(
            array('where' => array('logo_status' => 1))
        );

        $result['logo'] = get_image($logo['logo_image_path'], $logo['logo_image']);
        $result['color_theme'] = "#8204aa";
        //
        $content = $this->load->view('_layout/email_template/order_invoice_template', $result, TRUE);

        // echo $content;die;

        if ($type == 'USER') {
            $send_to = $order_data['order_email'];
        } else {
            $send_to = $admin_email;
        }

        $send_from = $this->customerSupportEmail;

        $subject = 'Membership Subscription Acknowledgement';

        parent::email($send_to, $send_from, $subject, $content);

        return true;
    }
}
