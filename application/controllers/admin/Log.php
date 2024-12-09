<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Log
 */
class Log extends MY_Controller
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

        $this->dt_params['dt_headings'] = "log_id, log_type, log_source, log_level, log_message, log_createdon";
        $this->dt_params['searchable'] = array("log_id", "log_type", "log_source", "log_level", "log_message");
        $this->dt_params['action'] = array(
            "hide_add_button" => true,
            "hide" => false,
            "show_delete" => true,
            "show_edit" => false,
            "order_field" => false,
            "show_view" => true,
            "extra" => array(),
        );

        $config['js_config']['paginate'] = $this->dt_params['paginate'];
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
            if ($class_name == "log") {
                $parameter['fields'] = "log_type, log_source, log_level, log_message, log_text, DATE_FORMAT(log_createdon, '%M, %d %Y %h:%i:%s') as log_createdon";
            }
            $parameter['where'][$class_name . '_id'] = $id;
            $result['record'] = $this->$model_name->find_one($parameter);

            $result['record'] = $this->$model_name->prepare_view_data($result['record']);

            if (!$result['record']) {
                $result['failure'] = "No Item Found";
            }

            // Load relation fields data
            $relation_data = $this->$model_name->get_relation_data($id);

            if (array_filled($relation_data)) {
                $result['record']['relation_data'] = $relation_data;
            }
        } else {
            $result['failure'] = "No Item Found";
        }

        return $result;
    }
}