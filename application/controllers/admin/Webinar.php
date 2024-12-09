<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Webinar
 */
class Webinar extends MY_Controller
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

        $this->dt_params['dt_headings'] = "webinar_id, webinar_topic, webinar_status";
        $this->dt_params['searchable'] = array("webinar_id", "webinar_topic", "webinar_status");
        $this->dt_params['action'] = array(
            "hide_add_button" => true,
            "hide" => false,
            "show_delete" => false,
            "show_edit" => false,
            "order_field" => false,
            "show_view" => true,
            "extra" => array(),
        );

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
    public function add($id = '', $data = array())
    {
        $this->add_script(array("jquery.validate.js", "form-validation-script.js"), "js");
        $this->register_plugins("jquery-file-upload");
        parent::add($id, $data);
    }

    /**
     * Method get_view
     *
     * @param int $id
     *
     * @return void
     */
    public function get_view($id = 0)
    {
        $result = array();
        $class_name = $this->router->class;
        $model_name = 'model_' . $class_name;
        $model_obj = $this->$model_name;
        $form_fields = $model_obj->get_fields();

        if ($id) {
            $parameter = array();
            if ($class_name == "webinar") {
                $parameter['fields'] = "webinar_userid, webinar_uuid, webinar_fetchid, webinar_host_id, webinar_topic, webinar_agenda, webinar_duration, webinar_password, webinar_contact_email, webinar_contact_name, webinar_start_time, webinar_timezone, webinar_current_status, webinar_status, DATE_FORMAT(webinar_createdon, '%M, %d %Y %h:%i:%s') as webinar_createdon";
            }
            $parameter['where'][$class_name . '_id'] = $id;
            $result['record'] = $this->$model_name->find_one($parameter);

            $result['record'] = $this->prepare_view_data($result['record']);

            if (!$result['record']) {
                $result['failure'] = "No item found";
            }

            $relation_data = $this->$model_name->get_relation_data($id);
            if (array_filled($relation_data)) {
                $result['record']['relation_data'] = $relation_data;
            }
        } else {
            $result['failure'] = "No Item Found";
        }

        return $result;
    }

    /**
     * Method prepare_view_data
     *
     * @param array $record
     *
     * @return array
     */
    public function prepare_view_data($record = []): array
    {
        $model_fields = $this->model_webinar->get_fields();
        if (array_filled($record)) {
            foreach ($record as $field => $value) {
                if ($value == '' || $value == NULL) {
                    continue;
                }
                $head = isset($model_fields[$field]['label']) ? $model_fields[$field]['label'] : '';
                $name = isset($model_fields[$field]['name']) ? $model_fields[$field]['name'] : '';
                if ($head) {
                    $return[$head] =  ((isset($this->model_webinar->get_fields($name)['list_data'][$value])) ? $this->model_webinar->get_fields($name)['list_data'][$value] : $value);
                }
            }
            return $return;
        }
    }
}
