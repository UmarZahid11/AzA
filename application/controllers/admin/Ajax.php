<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Ajax
 */
class Ajax extends MY_Controller
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
		parent::__construct();
	}

	/**
	 * Method populate
	 *
	 * @return void
	 */
	public function populate()
	{
		$model = "model_" . $_POST['search_model'];
		$model_obj = $this->{$model};
		$pk = $model_obj->get_pk();
		$dd_key = $_POST['dd_key'] ? $_POST['dd_key'] : $pk;
		$dd_value = $_POST['dd_value'];

		$params['where'][$_POST['search_key']] = $_POST['search_val'];
		$params['fields'] = "$dd_value, $dd_key";
		if ($_POST['search_model_relation']) {
			$relation = $model_obj->relations[$_POST['search_model_relation']];

			$params['joins'][] = array(
				"table" => $_POST['search_model_relation'],
				"joint" => $relation['own_key'] . "=" . $pk,
			);
		}

		$data = $model_obj->find_all_active($params, "");

		if (is_array($data))
			end_script(json_encode($data));
	}
}


