<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Career
 */
class Career extends MY_Controller
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
     * Method index
     *
     * @param int $page
     * @param int $limit
     *
     * @return void
     */
    public function index(int $page = 1, int $limit = PER_PAGE)
    {
        $data = array();

        $param = array();
        $param['where']['inner_banner_name'] = 'Career';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $param['where']['cms_page_name'] = 'Career';
        $data['cms'] = $this->model_cms_page->find_all_active($param);

        if ($this->model_signup->hasRole(ROLE_0)) {
            $data['currency'] = $this->model_currency->find_all();
            $data['job_type'] = $this->model_job_type->find_all_active();
            $data['job_category'] = $this->model_job_category->find_all_active();
        }

        $data['page'] = $page;
        $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

        $data['limit'] = $limit;

        // Prev + Next
        $data['prev'] = $page - 1;
        $data['next'] = $page + 1;

        $data['career'] = $this->model_career->find_all_active(
            array(
                'order' => 'career_id DESC',
                'limit' => $limit,
                'offset' => $paginationStart
            )
        );
        $data['career_count'] = $allRecrods = $this->model_career->find_count_active();

        $data['totalPages'] = ceil($allRecrods / $limit);

        //
        $this->layout_data['title'] = 'Career Opportunity | ' . $this->layout_data['title'];
        //
        $this->load_view("index", $data);
    }

    /**
     * portfolio
     *
     * @return void
     */
    public function portfolio()
    {
        $data = array();

        $param = array();
        $param['where']['inner_banner_name'] = 'Portfolio';
        $data['banner'] = $this->model_inner_banner->find_one_active($param);

        $param = array();
        $param['where']['portfolio_image_portfolio_id'] = 1;
        $data['portfolio_image'] = $this->model_portfolio_image->find_all($param);

        $param = array();
        $param['where']['cms_page_name'] = 'Portfolio';
        $data['cms'] = $this->model_cms_page->find_all_active($param);

        //
        $this->layout_data['title'] = 'Our Portfolio | ' . $this->layout_data['title'];
        //
        $this->load_view("portfolio", $data);
    }

    /**
     * Method save
     *
     * @return void
     */
    public function save()
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = __(ERROR_MESSAGE);

        $captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
        $secretKey = defined('CAPTCHA_SECRET_KEY') ? CAPTCHA_SECRET_KEY : '';

        if ($secretKey) {
            // post request to server
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);
        } else {
            $responseKeys["success"] = TRUE;
        }

        // should return JSON with success as true
        if ($responseKeys["success"]) {
            if ($this->validate("model_career")) {
                $file_error = false;
                $insert_career = array();
                $insert_career = $_POST['career'];
                $insert_career['career_slug'] = str_replace(" ", "-", $insert_career['career_job_title']);
                if ($_FILES['career_company_logo']['error'] == 0) {

                    if ($_FILES['career_company_logo']['size'] <= 2097152) {
                        $tmp = $_FILES['career_company_logo']['tmp_name'];
                        $name = mt_rand() . $_FILES['career_company_logo']['name'];

                        $upload_path = 'assets/uploads/career/';
                        $insert_career['career_company_logo'] = $name;
                        $insert_career['career_company_logo_path'] = $upload_path;
                        if (move_uploaded_file($tmp, $upload_path . $name)) {
                            $file_error = false;
                        } else {
                            $file_error = true;
                        }
                    } else {
                        $file_error = true;
                    }
                }
                if (!$file_error) {
                    $insert_career['career_status'] = STATUS_TRUE;
                    $inserted = $this->model_career->insert_record($insert_career);
                    if ($inserted) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __("Data posted successfully!");
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __("Logo file exceeds size limit!");
                }
            } else {
                $json_param['txt'] = validation_errors();
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_CAPTCHA_FAILED);
        }
        echo json_encode($json_param);
    }
}
