<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Quickbook_activity
 */
class Quickbook_activity extends MY_Controller
{
    /**
     * _list_data
     *
     * @var array
     */
    public $_list_data = array();

    /**
     * class
     *
     * @var mixed
     */
    private $class;

    public function __construct()
    {
        global $config;
        parent::__construct();

        $this->dt_params['dt_headings'] = "quickbook_activity_id, quickbook_activity_entity, quickbook_activity_userid, quickbook_activity_status";

        $this->dt_params['searchable'] = array("quickbook_activity_id", "quickbook_activity_userid", "quickbook_activity_entity", "quickbook_activity_status");

        $this->dt_params['action'] = array(
            "hide_add_button" => true,
            "hide" => false,
            "show_delete" => false,
            "show_edit" => false,
            "order_field" => false,
            "show_view" => false,
			"extra" => array(
				'<a title="View" href="' . $config['admin_base_url'] . $this->router->class . '/detail/%d/" class="btn-xs btn btn-primary order_details_btn"><i class="icon-eye"></i></a>',
			),
        );

        $this->_list_data['quickbook_activity_status'] = array(
            STATUS_INACTIVE => "<span class=\"label label-default\">Inactive</span>",
            STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>"
        );

        $this->_list_data['quickbook_activity_userid'] = $this->model_signup->find_all_custom_list(array(), ['signup_firstname', 'signup_lastname']);

        $config['js_config']['paginate'] = $this->dt_params['paginate'];

        $this->class = $this->router->class;
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
        parent::add($id, $data);
    }

    /**
     * Method detail
     *
     * @param mixed $entity_id
     *
     * @return void
     */
    public function detail($entity_id = '')
	{
        $this->layout_data['bread_crumbs'] = array(
			array(
				"home/" => "Home",
				'quickbook_activity/' => "Quickbook Activity",
				'/' => "Details",
			)
		);

		$this->layout_data['template_config']['show_toolbar'] = false;
		$this->register_plugins(array(
			"jquery-ui",
			"bootstrap",
			"bootstrap-hover-dropdown",
			"jquery-slimscroll",
			"uniform",
			"boots",
			"font-awesome",
			"simple-line-icons",
			"select2",
			"bootbox",
			"bootstrap-toastr",
			"bootstrap-datetimepicker"
		));

		$data['entity_detail'] = $this->{'model_'.$this->class}->find_by_pk($entity_id);

		if (!array_filled($data['entity_detail']))
			not_found("Invalid entity id");

		$data['page_title_min'] = "Detail";
		$data['page_title'] = ucfirst($this->class);
		$data['class_name'] = $this->class;
		$data['model_name'] = "model_" . $this->class;
		$data['model_obj'] = $this->{'model_' . $this->class};
		$data['dt_params'] = $this->dt_params;
		$data['id'] = $entity_id;
		$data['object'] = $this;

		$this->load_view("detail", $data);
	}
}
