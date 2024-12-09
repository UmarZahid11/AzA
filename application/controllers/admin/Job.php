<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Job
 */
class Job extends MY_Controller
{
    /**
     * _list_data
     *
     * @var array
     */
    public $_list_data = array();

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        global $config;
        parent::__construct();

        $this->dt_params['dt_headings'] = "job_id, job_title, job_userid, job_status";
        $this->dt_params['searchable'] = array("job_id", "job_title", "job_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => false,
            "hide" => false,
            "show_delete" => true,
            "show_edit" => true,
            "order_field" => false,
            "show_view" => false,
            "extra" => array(),
        );

        $this->_list_data['job_userid'] = $this->model_signup->find_all_custom_list_active(array('where' => array('signup_type' => ROLE_3)), array('signup_firstname', 'signup_lastname'));
        $this->_list_data['job_category'] = $this->model_job_category->find_all_list_active(array(), 'job_category_name');
        $this->_list_data['job_language'] = $this->model_language->find_all_list(array(), 'language_value', 'language_code');
        $this->_list_data['job_type'] = $this->model_job_type->find_all_list_active(array(), 'job_type_name', 'job_type_name');

        $config['js_config']['paginate'] = $this->dt_params['paginate'];
    }

    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        parent::index();
    }

    /**
     * Method add
     *
     * @param int $id
     * @param array $data
     *
     * @return void
     */
    public function add($id = 0, $data = array())
    {
        $this->add_script(array("jquery.validate.js", "form-validation-script.js"), "js");

        $id = intval($id);
        $this->configure_add_page();
        $class_name = $this->router->class;
        $model_name = 'model_' . $class_name;
        $model_obj = $this->$model_name;
        $form_fields = $model_obj->get_fields();

        $model_array = array();
        $model_array[] = $model_name;

        $this->_validation_models_add[] = $model_name;

        $pk = $model_obj->get_pk();

        if ($id) {

            $params['where'][$pk] = $id;
            $this->form_data[$class_name] = $this->$model_name->find_one($params);

            // Load relation fields data
            $this->form_data['relation_data'] = $this->$model_name->get_relation_data($id);

            if (count($this->form_data[$class_name]) == 0) {
                redirect($this->admin_path . "?msgtype=error&msg=404+-+Record+not+found.", 'refresh');
                exit();
            }
        }

        $this->layout_data['bread_crumbs'] = array(
            array(
                "home/" => "Home",
                $class_name => humanize($class_name),
                $class_name . "/add/" => "Add " . humanize($class_name),
            )
        );

        $user_data = $this->input->post(NULL, true);

        $data['form_data'] = (isset($this->form_data)) ? $this->form_data : array();

        if (isset($data['form_data']['job']['job_category'])) {
            $data['form_data']['job']['job_category'] = unserialize($data['form_data']['job']['job_category']);
        }

        if (isset($data['form_data']['job']['job_language']) && @unserialize($data['form_data']['job']['job_language']) !== FALSE) {
            $data['form_data']['job']['job_language'] = unserialize($data['form_data']['job']['job_language']);
        } else {
            $data['form_data']['job']['job_language'] = isset($data['form_data']) && $data['form_data'] ? [$data['form_data']['job']['job_language']] : [];
        }

        $data['user_input'] = (isset($user_data['login'])) ? $user_data['login'] : array();

        if (isset($_POST) && !empty($_POST)) {

            if (isset($_POST['job']['job_category'])) {
                $_POST['job']['job_category'] = serialize($_POST['job']['job_category']);
            }

            if (isset($_POST['job']['job_language'])) {
                $_POST['job']['job_language'] = serialize($_POST['job']['job_language']);
            }

            if ($this->bulk_validate($this->_validation_models_add)) {

                $user_data = $_POST[$class_name];

                if ((isset($user_data)) && (is_array($user_data)) && (isset($_FILES[$class_name]['name'])))
                    $user_data = $user_data + $_FILES[$class_name]['name'];


                $this->$model_name->set_attributes($user_data);

                $insertId = $this->$model_name->save();

                if ($insertId) {
                    $this->$model_name->update_relations($insertId);
                    $this->afterSave($insertId, $this->$model_name);
                    if ($this->prevent_return_on_success)
                        return $insertId;

                    $this->add_redirect_success($insertId);
                } else {
                    redirect($this->admin_path . "?msgtype=error&msg=Couldnt Save Data.", 'refresh');
                    exit();
                }
            } else {
                $data['error'] = validation_errors();
            }
        }

        $data['page_title_min'] = "Management";
        $data['page_title'] = $class_name;
        $data['class_name'] = $class_name;
        $data['model_name'] = $model_name;
        $data['model_obj'] = $model_obj;
        $data['form_fields'][$class_name] = $form_fields;
        $data['dt_params'] = $this->dt_params;
        $data['id'] = $id;

        $this->before_add_render($data);
        $this->load_view("_form", $data);
    }

    /**
     * mapbox
     *
     * @return void
     */
    public function mapbox()
    {
        $searchTerm = urlencode($this->input->get('term'));

        $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' . $searchTerm . '.json?worldview=cn&access_token=' . MAP_BOX_API_KEY;
        $ch = curl_init();
        $getUrl = $url;
        $jsonBody = "";

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            error_log("cURL Error #:" . $err);
        } else {
            $jsonBody = json_decode($response);
        }

        $returnData = array();

        foreach ($jsonBody->features as $key => $val) {
            $data['id'] = $val->place_name;
            $data['value'] = $val->place_name;
            array_push($returnData, $data);
        }
        echo json_encode($returnData);
    }

    /**
     * job_categories
     *
     * @return void
     */
    public function job_categories()
    {
        $searchTerm = $this->input->get('q');

        $param = array();
        $param['where_like'][] = array(
            'column' => 'job_category_name',
            'value' => $searchTerm,
            'type' => 'both'
        );

        $Data = $this->model_job_category->find_all_active($param);
        $returnData = array();
        foreach ($Data as $key => $val) {
            $returnData['items'][$key]['id'] = $val['job_category_id'];
            $returnData['items'][$key]['title'] = $val['job_category_name'];
        }

        $returnData['pagination']['more'] = count($returnData) > 30 ? true : false;

        echo json_encode($returnData);
    }
}
