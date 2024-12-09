<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Partner_image
 */
class Partner_image extends MY_Controller
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
	}

	/**
	 * Method upload_images
	 *
	 * @param int $id
	 *
	 * @return void
	 */
	public function upload_images($id = 0)
	{
		global $confg;

		$id = intval($id);
		$class_name = $this->router->class;
		$model_name = 'model_' . $class_name;
		$model_obj = $this->$model_name;
		$pk = $model_obj->get_pk();
		$form_fields = $model_obj->bulk_image_fields();

		if (!$id) {
			pre("Invalid ID");
		} else {
			$foreign_model = "model_" . $form_fields['foreign_key']['table'];
			$foreign_obj = $this->$foreign_model;
			$data = $foreign_obj->find_by_pk($id);
			if (!$data) {
				echo "Invalid partner";
				exit();
			}
		}

		$return = array();
		$ret_params = array();
		$ret_params['where'][$form_fields['foreign_key']['name']] = $id;

		if ($_POST && strlen($_FILES['partner_image']['name']['partner_image_name'])) {
			if ($this->bulk_validate(array($model_name))) {
				$user_data = $_POST[$class_name] + $_FILES[$class_name]['name'];
				$model_obj->set_attributes($user_data);
				$ret_params['where'][$pk] = $model_obj->save();
			} else {
				$return["files"][] = array("error" => validation_errors());
			}
		}

		$return["files"] = $model_obj->get_images($ret_params);
		echo json_encode($return);
		exit();
	}

	/**
	 * Method delete_image
	 *
	 * @param int $id
	 * @param string $token
	 *
	 * @return void
	 */
	public function delete_image($id = 0, $token)
	{
		$class_name = $this->router->class;
		$model_name = 'model_' . $class_name;
		$model_obj = $this->$model_name;
		$pk = $model_obj->get_pk();
		$form_fields = $model_obj->bulk_image_fields();

		$id = intval($id);
		if (!$id || !$token)
			return false;

		$rec = $model_obj->find_by_pk($id);
		if ($token == $model_obj->img_salt($rec)) {
			$deleted = $model_obj->delete_by_pk($id);
			if ($deleted) {
				$img_path = $rec[$form_fields['image_path']['name']] . $rec[$form_fields['image']['name']];
				$thumb_path = $rec[$form_fields['image_path']['name']] . $rec[$form_fields['image_thumb']['name']];

				if (is_file($img_path))
					unlink($img_path);
				if (is_file($thumb_path))
					unlink($thumb_path);
			}
		} else {
			echo "Invalid Request.";
		}

		exit();
	}
}
