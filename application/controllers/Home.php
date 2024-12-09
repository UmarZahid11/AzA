<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Home
 */
class Home extends MY_Controller
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
        $param['where']['banner_heading'] = 'Home Page Banner';
        $data['banner'] = $this->model_banner->find_one_active($param);

        $param = array();
        $param['where']['cms_page_name'] = 'Home';
        $data['cms'] = $this->model_cms_page->find_all_active($param);

        $data['testimonial'] = $this->model_testimonial->find_all_active();

        $param = array();
        $param['order'] = 'faq_id DESC';
        $param['limit'] = 4;
        $data['faq'] = $this->model_faq->find_all_active($param);

        $param = array();
        $param['order'] = "partner_image_id DESC";
        $param['limit'] = 10;
        $param['where']['partner_image_partner_id'] = 1;
        $data['partner_image'] = $this->model_partner_image->find_all($param);

        $param = array();
        $param['limit'] = 6;
        $param['order'] = 'story_id DESC';
        $data['story'] = $this->model_story->find_all_active($param);

        $param = array();
        $param['where']['job_userid'] = 0;
        $param['limit'] = 6;
        $param['order'] = 'job_id DESC';
        $data['job'] = $this->model_job->find_all_active($param);

        // to be replaced by ajax on view
        $param = array();
        $param['where']['job_userid'] = 0;
        $param['offset'] = 6;
        $param['limit'] = 6;
        $param['order'] = 'job_id DESC';
        $data['additional_job'] = $this->model_job->find_all_active($param);

        $param = array();
        $param['where']['cms_page_name'] = 'Login';
        $data['login_cms'] = $this->model_cms_page->find_all_active($param);

        //
        $membership_sections = $this->model_membership_section->find_all_active();
        //
        $sectionData = [];
        foreach($membership_sections as $section) {
            $sectionData[$section['membership_section_name']] = $this->model_membership_attribute_identifier->find_all_active(
                array(
                    'where' => array(
                        'membership_attribute_section_id' => $section['membership_section_id']
                    ),
                    'joins' => array(
                        0 => array(
                            "table" => "membership_attribute" ,
                            "joint" => "membership_attribute.membership_attribute_id = membership_attribute_identifier.membership_attribute_identifier_id",
                            "type" => "both"   
                        )
                    ),
                    'fields' => 'membership_attribute_name'
                )
            );
        }
        $data['sectionData'] = $sectionData;

        $memberships = $this->model_membership->find_all_active();
        //
        $membershipData = [];
        foreach($memberships as $membership) {
            $membershipData[$membership['membership_id']]['membership'] = $membership;
            $membershipData[$membership['membership_id']]['membership']['membership_interval'] = $this->model_membership_interval->find_by_pk($membership['membership_interval_id']);
            $membershipData[$membership['membership_id']]['data'] = $this->model_membership_pivot->find_all_active(
                array(
                    'where' => array(
                        'membership_pivot_membership_id' => $membership['membership_id']
                    ),
                    'joins' => array(
                        0 => array(
                            "table" => "membership_attribute_identifier" ,
                            "joint" => "membership_attribute_identifier.membership_attribute_identifier_id = membership_pivot.membership_pivot_attribute_id",
                            "type" => "both"   
                        )
                    ),
                    'fields' => 'membership_pivot_value'
                )
            );
        }
        $data['membershipData'] = $membershipData;
        //

        //
        $this->layout_data['title'] = 'Home | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    public function main() {
        $data = [];
        //
        $this->layout_data['title'] = 'Main | ' . $this->layout_data['title'];
        //
        $this->load_view("main", $data);
    }

    public function thankyou() {
        $data = [];
        //
        $this->layout_data['title'] = 'Thank you | ' . $this->layout_data['title'];
        //
        $this->load_view("thank-you", $data);
    }

    public function subscription() {
        $data = [];
        //
        $this->layout_data['title'] = 'Subscription | ' . $this->layout_data['title'];
        //
        $this->load_view("subscription", $data);
    }

    /**
     * redirecting to social media function
     *
     * @return void
     */
    function redirecting()
    {
        $this->load_view("loading");
    }

    /**
     * premium function
     *
     * @return void
     */
    function premium()
    {
        global $config;

        $data['config'] = $config;

        //
        $this->layout_data['title'] = 'Premium | ' . $this->layout_data['title'];
        //
        $this->load_view('premium', $data);
    }

    /**
     * Learn_more function
     *
     * @return void
     */
    function learn_more()
    {
        $param = array();
        $param['where']['cms_page_name'] = 'Learn more';
        $data['cms'] = $this->model_cms_page->find_all_active($param);
        //
        $this->layout_data['title'] = 'Learn more | ' . $this->layout_data['title'];
        //
        $this->load_view('learn_more', $data);
    }

    /**
     * Canva function
     *
     * @return void
     */
    function canva()
    {
        $this->load_view('canva');
    }

    /**
     * Method refreshCSRF - echo current csrf_token (usefull when only div is refreshed, see function in custom.js => refreshCSRF())
     *
     * @return void
     */
    function refreshCSRF(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['_token'])) {
                try {
                    $_token = JWT::decode($_POST['_token'], CI_ENCRYPTION_SECRET);
                    if ($_token === CI_ENCRYPTION_PUBLIC) {
                        echo json_encode(array('status' => TRUE, 'csrf' => $this->csrf_token));
                        die;
                    }
                } catch (\Exception $e) {
                    log_message('ERROR', $e->getMessage());
                }
            }
        }
        echo json_encode(array('status' => FALSE));
    }

    /**
     * setTimeZone function
     *
     * @return void
     */
    function setTimezone()
    {

        $timezone_offset_minutes = $_POST['timezone_offset_minutes'];
        $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes * 60, 0);

        date_default_timezone_set($timezone_name);
        $this->session->set_userdata('timezone', $timezone_name);

        echo json_encode(['timezone_name' => date_default_timezone_get()]);
    }
}
