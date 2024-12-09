<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TKD Model Wrapper Class.
 *
 * @package		TKDModel
 * @author		1
 * @version		1.0
 * @since		Version 1.0 2017
 *
 * @ways_to_setup_fields:
 *
 *                    *****************HIDDEN PRIMARY****************
 *    	 				// Can define this attribute in construct
 *          			$this->relations = array(
 *          		        'model_job_categories_department'=>array('pk'=>'job_category_id' , 'search_attr'=>'department_id'),
 *          		    );
 *
 *
 *               	array(
 *                       'field'   => $this->_table.'[id]',
 *                       'name'   => 'id',
 *                       'label'   => 'id',
 *                       'primary'   => 'primary',
 *                       'type'   => 'hidden',
 *                       'attributes'   => array(),
 *                       'foreign_keys' => $this->relations, //Define relations somewhere
 *                       'js_rules'   => '',
 *                       'rules'   => 'trim'
 *                    ),
 *
 *                    *****************TEXT****************
 *
 * 	                array(
 *                       'field'   => $this->_table.'[dep_head]',
 *                       'name'   => 'dep_head',
 *                       'label'   => 'HoD ',
 *                       'type'   => 'text',
 *                       'attributes'   => array(),
 *                       'js_rules'   => 'required',
 *                       'rules'   => 'required|trim|htmlentities'
 *                    ),
 *
 *                    *****************EDITOR****************
 *                 array(
 *                       'field'   => $this->_table.'[dep_detail]',
 *                       'name'   => 'dep_detail',
 *                       'label'   => 'Detail Description',
 *                       'type'   => 'editor',
 *                       'attributes'   => array(),
 *                       'js_rules'   => '',
 *                       'rules'   => 'trim|htmlentities'
 *                    ),
 *
 *                    *****************SWITCH****************
 *                  array(
 *                       'field'   => $this->_table.'[status]',
 *                       'name'   => 'status',
 *                       'label'   => 'Status?',
 *                       'type'   => 'switch',
 *                       'default'   => '1',
 *                       'attributes'   => array(),
 *                       'rules'   => 'trim'
 *                    ),
 *
 *                    *****************AJAX POPULATE****************
 *                                   array(
 *                       'field'   => $this->_table.'[department_id]',
 *                       'name'   => 'department_id',
 *                       'label'   => 'Department',
 *                       'type'   => 'multiselect',
 *                       'list_data'    => $department_options,
 *                       'list_fields'    => array('value'=>"id","label"=>"dep_name"),
 *                       'relation_model' => '',
 *                       'multi_value' => false,
 *                       'attributes'   => array("class"=>" populate-ajax department",
 *                                                "additional" => 'data-model="section"  data-target=".section_id"  data-key="department"  data-action="department" '
 *                                              ),
 *                       'js_rules'   => 'required',
 *                       'rules'   => 'required|trim'
 *                    ),
 *
 *                array(
 *                       'field'   => $this->_table.'[section_id]',
 *                       'name'   => 'section_id',
 *                       'label'   => 'Section',
 *                       'type'   => 'multiselect',
 *                       'list_data'    => array(),
 *                       'list_fields'    => array('value'=>"id","label"=>"name"),
 *                       'multi_value' => false,
 *                       'attributes'   => array('class'=>'section_id'),
 *                       'js_rules'   => 'required',
 *                       'rules'   => 'required|trim'
 *                    ),
 *
 *                    *****************IMAGE****************
 * 	 			 array(
 *                       'field'   => $this->_table.'[image]',
 *                       'name'   => 'image',
 *                       'label'   => 'Image',
 *                       'name_path'   => 'image_path',
 *                       'upload_config'   => 'site_upload_emblem',  //DEFINE PATH IN CONFIG VARIABLE with key 'site_upload_emblem' OR whatever
 *                       'thumb'   => array(	//Thumb - optional
 *                                          array('name'=>'image_thumb','max_width'=>150, 'max_height'=>150),
 *                                      ),
 *                       'type'   => 'fileupload',
 *                       'randomize' => true,
 *                       'preview'   => 'true',
 *                       'attributes'   => array(),
 *                       'rules'   => 'trim|htmlentities'
 *                    ),
 *
 *                    *****************Fields with multi RELATIONAL DATA(Mapping , sort of)****************
 *					// IN MAIN MODEL , WHOSE CONTROLLER IS CALLED, add following in PK field:
 *                     'foreign_keys' => $this->relations,
 *
 * 	 				// Can define this attribute in construct
 *          			$this->relations = array(
 *          		        'model_job_categories_department'=>array('pk'=>'job_category_id' , 'search_attr'=>'department_id'),
 *          		    );
 *
 *          		    // Define in get_fields():
 *          		    $dept_options = $this->model_department->find_all(array('status !='=>2),"id,dep_name");
 * 	        		 $related_data = $this->get_relateional_data();
 *
 * 	         	// Finally, provide field relations
 *              array(
 *                     'field'   =>  $this->_table.'[department_id]',
 *                     'name'   => 'department_id',
 *                     'label'   => 'Departments',
 *                     'type'   => 'multiselect',
 *                     'list_data'    => $dept_options,
 *                     'list_fields'    => array('value'=>"id","label"=>"dep_name"),
 *                     'related_data'  =>  $related_data,
 *                     'relation_type' => 'many_to_many',
 *                     'relation_model' => 'model_job_categories_department',
 *                     'multi_value' => true,
 *                     'attributes'   => array(),
 *                     'rules'   => 'required|trim'
 *                  ),
 *
 */

class MY_Model extends CI_Model
{

	private static $instance;

	/**
	 * Constructor
	 */
	protected $layout_data = array();
	protected $layout;
	public $_attributes = array();

	public function __construct()
	{
		parent::__construct();
		// SET The timezone when required
		// Timezone setting updated in tkd_config; leave it blank if not needed
		$this->set_timezone();
		/*if($this->uri_data['id'])
			$this->_id = intval($this->uri_data['id']);
		if($this->get_order_field())
			$this->pagination_params['fields'] .= ','.$this->get_order_field_name();*/
	}

	public function set_timezone()
	{
		if (MYSQL_TIME_ZONE)
			$this->db->query("SET time_zone  = '" . MYSQL_TIME_ZONE . "'");
	}

	public function table_name()
	{
		return  $this->db->dbprefix . $this->_table;
	}

	public function get_rules()
	{
		$fields = $this->get_fields();
		if (is_array($fields))
			foreach ($fields as $field_name => $field) {
				$rules[$field_name]['field'] = $field['table'] . "[{$field_name}]";
				$rules[$field_name]['label'] = isset($field['label']) ? $field['label'] : 'Not Available';
				$rules[$field_name]['rules'] = isset($field['rules']) ? $field['rules'] : '';
			}
		return $rules;
	}

	public function get_pk()
	{
		return $this->_pk;
	}

	public function prep_join($table, $joint, $type = "right")
	{
		return array(
			"table" => $table,
			"joint" => $joint,
			"type" => $type
		);
	}

	/**
	 * Method prepare_view_data
	 *
	 * @param array $record
	 *
	 * @return void
	 */
	public function prepare_view_data($record = [])
	{
		$return = array();
		$model_fields = $this->get_fields();
		if (array_filled($record)) {
			foreach ($record as $field => $value) {
				if (!isset($value))
					continue;
				$head = $model_fields[$field]['label'];
				$return[$head] = $value;
			}
		}
		return $return;
	}

	public function get_order_field()
	{
		if (isset($this->_order_field))
			return $this->_table . "." . $this->_order_field;
	}

	public function get_order_field_name()
	{
		return $this->_order_field;
	}

	public function get_status_field()
	{
		return $this->_status_field;
	}

	public function get_table_status_field()
	{
		if (isset($this->_status_field))
			return $this->_table . "." . $this->_status_field;
	}

	public function find_by_pk($id = 0, $return_obj = false, $params = array())
	{
		$pk = $this->get_pk();
		if ($id) {
			$params['where'][$pk] = ($id);
			return $this->find_one($params, $return_obj);
		}
		return [];
	}

	public function delete_by_pk($id = 0)
	{
		if ($id) {
			$pk = $this->get_pk();
			if (!$pk)
				pre("Unable to get PK from get_pk function");
			$where[$pk] = intval($id);
			return $this->delete_record($where);
		} else
			return false;
	}

	// Extract PK value FROM result
	public function extract_pk($data = array())
	{
		if (array_filled($data)) {
			$pk = $this->get_pk();
			if (!$pk)
				return false;
			$pk_set = array();
			foreach ($data as $row) {
				$pk_set[] = $row[$pk];
			}
		}
		return $pk_set;
	}

	public function find_all_active($params = array())
	{
		if (!isset($params['where']) || !is_array($params['where']))
			$params['where'] = array();

		$status_fld = $this->get_table_status_field();

		if ($status_fld)
			$params['where'][$status_fld] = STATUS_ACTIVE;

		return $this->find_all($params);
	}

	public function find_all_list($params = array(), $field, $key_field = "")
	{
		$resultant = array();
		$find_all_rs = $this->find_all($params);
		if (isset($params['return_count']) && $params['return_count'])
			$resultset = $find_all_rs[data];
		else
			$resultset = $find_all_rs;
		foreach ($resultset as $key => $value) {
			$key_field = $key_field ? $key_field : $this->get_pk();
			if ($key_field)
				$resultant[$value[$key_field]] = $value[$field];
			else
				$resultant[] = $value[$field];
			// ^Assuming there has to be a primary key - Assumptions... leads to hope , and hope leads to 500 ;)
			// But developers have to assume since Maths is mother of all sciences
		}
		if (isset($params['return_count']) && $params['return_count'])
			return array("count" => $find_all_rs['count'], "data" => $resultant);
		else
			return $resultant;
	}

	public function find_all_list_active($params = array(), $field, $key = "")
	{
		if (!isset($params['where']) || !$params['where'])
			$params['where'] = array();

		$status_fld = $this->get_table_status_field();

		if ($status_fld)
			$params['where'][$status_fld] = STATUS_ACTIVE;

		return $this->find_all_list($params, $field, $key);
	}

	public function find_all_custom_list($params = array(), $field, $key_field = "")
	{
		$resultant = array();
		$find_all_rs = $this->find_all($params);
		if (isset($params['return_count']) && $params['return_count'])
			$resultset = $find_all_rs[data];
		else
			$resultset = $find_all_rs;
		foreach ($resultset as $key => $value) {
			$key_field = $key_field ? $key_field : $this->get_pk();
			if ($key_field) {
				$resultant[$value[$key_field]] = '';
				foreach ($field as $field_value) {
					$resultant[$value[$key_field]] = $resultant[$value[$key_field]] . ' ' . $value[$field_value];
				}
			} else {
				$resultant[] = $value[$field];
			}
			// ^Assuming there has to be a primary key - Assumptions... leads to hope , and hope leads to 500 ;)
			// But developers have to assume since Maths is mother of all sciences
		}
		if (isset($params['return_count']) && $params['return_count'])
			return array("count" => $find_all_rs['count'], "data" => $resultant);
		else
			return $resultant;
	}

	public function find_all_custom_list_active($params = array(), $field, $key = "")
	{
		if (!isset($params['where']) || !$params['where'])
			$params['where'] = array();

		$status_fld = $this->get_table_status_field();

		if ($status_fld)
			$params['where'][$status_fld] = STATUS_ACTIVE;

		return $this->find_all_custom_list($params, $field, $key);
	}

	public function find_all_grouping($params = array(), $group_field = "", $key_field = "", $value_field = "")
	{
		$status_fld = $this->get_table_status_field();
		if ($status_fld)
			$params['where'][$status_fld] = STATUS_ACTIVE;

		$list = $this->find_all($params);

		foreach ($list as $key => $data) {

			$value = $value_field ? $data[$value_field] : $data;

			if ($key_field)
				$return_list[$data[$group_field]][$data[$key_field]] = $value;
			else
				$return_list[$data[$group_field]][] = $value;
		}
		return $return_list;
	}

	public function find_all($params = array())
	{
		extract($params);
		if (isset($where) && count($where))
			$this->db->where($where);
		if (isset($having) && count($having))
			$this->db->having($having);
		if (isset($where_like) && count($where_like)) {
			foreach ($where_like as $like_value) {
				$this->db->like($like_value['column'], $like_value['value'], $like_value['type']);
			}
		}
		if (isset($or_like) && count($or_like)) {
			foreach ($or_like as $or_like_value) {
				$this->db->or_like($or_like_value['column'], $or_like_value['value'], $or_like_value['type']);
			}
		}
		if (isset($where_in) && count($where_in))
			foreach ($where_in as $where_key => $where_value_arr) {
				$this->db->where_in($where_key, $where_value_arr);
			}
		if (isset($where_is_null) && count($where_is_null))
			foreach ($where_is_null as $where_value_arr) {
				$this->db->where($where_value_arr . ' IS NULL', NULL, TRUE);
			}
		if (isset($or_where_in) && count($or_where_in))
			foreach ($or_where_in as $where_key => $where_value_arr) {
				$this->db->or_where_in($where_key, $where_value_arr);
			}
		if (isset($where_not_in) && count($where_not_in))
			//$this->db->where_not_in($where_not_in);
			foreach ($where_not_in as $where_not_key => $where_not_value_arr) {
				$this->db->where_not_in($where_not_key, $where_not_value_arr);
			}
		if (isset($or_where) && count($or_where))
			$this->db->or_where($or_where);
		if (isset($where_string) && trim($where_string))
			$this->db->where($where_string);

		if (isset($return_count) && $return_count) {
			$fields = "SQL_CALC_FOUND_ROWS " . ($fields ? $fields : "*");
		}
		if (isset($fields) && trim($fields))
			$this->db->select($fields, FALSE);
		if (isset($offset) && isset($limit) && $offset != "" && $limit != "")
			$this->db->limit($limit, $offset);
		elseif (isset($limit) && $limit != "")
			$this->db->limit($limit);
		if (isset($order) && $order != "")
			$this->db->order_by($order);
		if (isset($group) && $group != "")
			$this->db->group_by($group);
		if ((isset($joins)) && (array_filled($joins))) {
			foreach ($joins as $join) {
				$this->db->join($join['table'], $join['joint'], (isset($join['type']) ? $join['type'] : ''));
			}
		}

		$result = $this->db->get($this->_table)->result_array();

		if (isset($params['group_field']) && $params['group_field']) {
			foreach ($result as $key => $value) {
				unset($result[$key]);
				$result[$value[$params['group_field']]] = $value;
			}
		}

		if (isset($return_count) && $return_count) {
			$query = $this->db->query('SELECT FOUND_ROWS() AS counter');
			$return["count"] = $query->row()->counter;
			$return['data'] = $result;
			return $return;
		}
		return $result;
	}

	public function update_by_pk($id, $data = array())
	{
		$pk = $this->get_pk();
		if ($pk && $id && array_filled($data)) {
			$params['where'][$pk] = $id;
			return $this->update_model($params, $data);
		}
	}

	public function update_model($where_params = array(), $data = array())
	{
		extract($where_params);
		if (!(isset($where)) && !(isset($where_like)) && !(isset($or_where)) && !(isset($where_string)) && !(isset($where_in)))
			return false;

		if (isset($where))
			$this->db->where($where);
		if (isset($where_in))
			foreach ($where_in as $where_key => $where_value_arr) {
				$this->db->where_in($where_key, $where_value_arr);
			}
		if (isset($where_like))
			$this->db->like($where_like);
		if (isset($or_where))
			$this->db->or_where($or_where);
		if (isset($where_string))
			$this->db->where(trim($where_string));

		$this->db->update($this->_table, $data);
		return $this->db->affected_rows();
	}

	public function find_count($params = array())
	{
		$params['fields'] = "COUNT(*) AS count";
		$count = $this->find_one($params);
		return $count['count'];
	}

	public function find_count_active($params = array())
	{
		$status_fld = $this->get_table_status_field();

		if (isset($params['where']) && !is_array($params['where']))
			$params['where'] = array();

		if ($status_fld)
			$params['where'][$status_fld] = STATUS_ACTIVE;

		return $this->find_count($params);
	}

	public function find_one($params = array(), $return_obj = false)
	{
		extract($params);
		// if(count($where))
		if (isset($where))
			$this->db->where($where);
		if (isset($where_like) && count($where_like)) {
			foreach ($where_like as $like_value) {
				$this->db->like($like_value['column'], $like_value['value'], $like_value['type']);
			}
		}
		//if(count($having))
		if (isset($having))
			$this->db->having($having);
		if (isset($or_where))
			$this->db->or_where($or_where);
		//if(trim($where_string))
		if (isset($where_string) && trim($where_string))
			$this->db->where(trim($where_string));

		if (isset($where_in))
			foreach ($where_in as $where_key => $where_value_arr) {
				$this->db->where_in($where_key, $where_value_arr);
			}
		if (isset($where_not_in) && count($where_not_in))
			//$this->db->where_not_in($where_not_in);
			foreach ($where_not_in as $where_not_key => $where_not_value_arr) {
				$this->db->where_not_in($where_not_key, $where_not_value_arr);
			}

		//if($fields)
		if (isset($fields))
			$this->db->select($fields, FALSE);
		//if($order!="" )
		if (isset($order))
			$this->db->order_by($order);
		//if($group!="" )
		if (isset($group))
			$this->db->group_by($group);
		if (isset($joins)) {
			foreach ($joins as $join) {
				$this->db->join($join['table'], $join['joint'], isset($join['type']) ? $join['type'] : '');
			}
		}

		$table = (isset($table)) ? $table : $this->_table;

		$result = $this->db->get($table, 1, 0);

		if (!$return_obj)
			$result = $result->row_array();
		else
			$result = $result->row();

		return $result;
	}

	public function save_one($params = array(), $record)
	{
		if (!is_array($params['where']))
			return false;

		$data = $this->find_one($params);

		if (is_array($data))
			$data = $record + $data;

		$this->set_attributes($data);
		$id = $this->save();

		return $id;
	}


	public function find_one_active($params = array(), $return_obj = false)
	{
		if (isset($params['where']) && !is_array($params['where']))
			$params['where'] = array();

		$status_fld = $this->get_table_status_field();

		if ($status_fld)
			$params['where'][$status_fld] = STATUS_ACTIVE;

		return $this->find_one($params, $return_obj);
	}



	/*
	*	Used for pagination  -- Significantly to Prepare Datatable Data for Admin
	* @params: where=>array, fields=>string, group=>string, order->string , joins->array,,
	*
	**/
	public function pagination_query($params = array())
	{
		$CI = &get_instance();
		$searchable_fields = $CI->dt_params['searchable'];
		$status_fld = $this->get_table_status_field();
		$dbprefix = $this->db->dbprefix;

		$params['where'] = (isset($params['where'])) ? $params['where'] : ((isset($this->pagination_params['where'])) ? $this->pagination_params['where'] : array());

		// By Default, we do not want Deleted Items in our Pagination List
		if ($status_fld)
			$params['where'][$status_fld . ' !='] = STATUS_DELETE;

		if ((isset($_REQUEST['filter']) && (is_array($_REQUEST['filter'])))) {
			foreach ($_REQUEST['filter'] as $field => $value) {

				$value = trim($value);
				if (strlen($value) && is_array($searchable_fields) && in_array($field, $searchable_fields)) {
					$field_attr = $this->get_fields($field);
					$field_type = (isset($field_attr['type_filter_dt'])) ? $field_attr['type_filter_dt'] : '';

					switch ($field_type) {
						case 'radio':
						case 'checkbox':
						case 'dropdown':
							# code...
							$params['where'][$field_attr['table'] . "." . $field] = $value;
							break;

						case 'text':
						default:
							# code...
							//$table_dat = (isset($field_attr['table']))?$field_attr['table']:'';
							//$like_strings[] = $dbprefix.$table_dat.".".$field . " LIKE '%". $value ."%'" ;
							//$like_strings[] = $dbprefix.$table_dat.".".$field . " LIKE '%". $value ."%'" ;
							$like_strings[] = $dbprefix . $field_attr['table'] . "." . $field . " LIKE '%" . $value . "%'";
							break;
					}
				}
			}

			if ((isset($like_strings)) && (array_filled($like_strings)))
				$params['where_string'] = "(" . implode(" AND ", $like_strings) . ")";
		}

		$params['fields'] = (isset($params['fields'])) ? $params['fields'] : ($this->pagination_params['fields'] ? $this->pagination_params['fields'] : "*");

		$params['group'] = (isset($params['group']) ? $params['group'] : (isset($this->pagination_params['group']) ? $this->pagination_params['group'] : ''));

		$params['order'] = (isset($params['order'])) ? $params['order'] : $this->pagination_params['order'];

		if (isset($this->_order_field)) {
			$params['order'] = $this->get_order_field() . " DESC";
		}

		$params['joins'] = (isset($params['joins']) ? $params['joins'] : (isset($this->pagination_params['joins']) ? $this->pagination_params['joins'] : ''));

		$params['return_count'] = true;

		$params['offset'] = intval($_POST['start']);

		$params['offset'] = intval($_REQUEST['start']);

		$params['limit'] = $_REQUEST['length'] ? $_REQUEST['length'] : $this->_per_page;

		$params['limit'] = intval($params['limit']);

		//For fields that are honored to allow search from datatables
		//Create LIKE string for them

		if (intval($params['limit']) <= 0)
			unset($params['limit']);

		return $this->find_all($params);
	}

	/*
	*
	* @params: where=>array, fields=>string, group=>string, order->string , joins->array,,
	*
	**/
	public function single_pagination_query($params = array())
	{
		$params['where'] = $params['where'] ? $params['where'] : ($this->pagination_params['where'] ? $this->pagination_params['where'] : "*");

		$params['fields'] = ($params['fields'] ? $params['fields'] : ($this->pagination_params['fields'] ? $this->pagination_params['fields'] : "*"));

		$params['group'] = $params['group'] ? $params['group'] : $this->pagination_params['group'];

		$params['order'] = $params['order'] ? $params['order'] : $this->pagination_params['order'];

		$params['joins'] = $params['joins'] ? $params['joins'] : $this->pagination_params['joins'];

		$params['return_count'] = true;

		$params['offset'] = intval($_GET['iDisplayStart']);

		$params['limit'] = intval($_GET['iDisplayLength']) ? intval($_GET['iDisplayLength']) : $this->_per_page;
		//For fields that are honored to allow search from datatables
		//Create LIKE string for them
		if (is_array($this->pagination_params['searchable']) && count($this->pagination_params['searchable']) && trim($_GET['sSearch'])) {
			foreach ($this->pagination_params['searchable'] as $searchable) {
				$like_strings[] = $searchable . " LIKE '%" . trim(mysql_real_escape_string($_GET['sSearch'])) . "%'";
			}
			$params['where_string'] = "(" . implode(" OR ", $like_strings) . ")";
		}
		return $this->find_one($params['where'], $params);
	}

	public function insert_record($record = array())
	{
		if (count($record)) {
			$insertion = $this->db->insert($this->_table, $record);
			$insert_id = $this->db->insert_id();
			// Sometimes, like in case of Composite key, InsertId is not returned.
			return $insert_id ? $insert_id : ($insertion ? $insertion : false);
		} else
			return false;
	}

	public function insert_if_not_found($record = array(), $where = array())
	{
		if (count($record)) {
			$where = array();
			$params['where'] = array_filled($where) ? $where : $record;
			$exists = $this->find_one($params);
			if (!$exists) {
				$insertion = $this->db->insert($this->_table, $record);
				$insert_id = $this->db->insert_id();
				// Sometimes, like in case of Composite key, InsertId is not returned.
			}
			return $insert_id ? $insert_id : ($insertion ? $insertion : false);
		} else
			return false;
	}

	public function insert_if_not_found_or_update($record = array(), $where = array())
	{
		if (count($record)) {
			$params = array();
			$params['where'] = $where;
			$exists = $this->find_one($params);

			if ($exists) {
				$insert_id = $this->update_model($params, $record);
			} else {
				$insert_id = $this->insert_record($record);
			}
			// Sometimes, like in case of Composite key, InsertId is not returned.
			return $insert_id ? $insert_id : ($insertion ? $insertion : false);
		} else
			return false;
	}

	public function insert_batch_record($record = array())
	{
		if (count($record)) {
			$insertion = $this->db->insert_batch($this->_table, $record);
			$affected_rows = $this->db->affected_rows();
			// Sometimes, like in case of Composite key, InsertId is not returned.
			return $affected_rows ? $affected_rows : ($insertion ? $insertion : false);
		} else
			return false;
	}
	public function update_batch_record($record = array())
	{
		if (count($record)) {
			$insertion = $this->db->update_batch($this->_table, $record);
			$affected_rows = $this->db->affected_rows();
			// Sometimes, like in case of Composite key, InsertId is not returned.
			return $affected_rows ? $affected_rows : ($insertion ? $insertion : false);
		} else
			return false;
	}

	public function delete_record($where = array())
	{
		if (count($where)) {
			$this->db->where($where);
			$this->db->delete($this->_table);
			return $this->db->affected_rows();
		} else
			return false;
	}
	public function delete_record_custon($where = array())
	{
		if (count($where)) {
			extract($where);
			$this->db->where($where);
			$this->db->delete($this->_table);
			return $this->db->affected_rows();
		} else
			return false;
	}

	public function save()
	{
		global $config;

		$fields = $this->get_fields();
		$user_data = $this->_attributes;

		$pk = $this->get_pk();

		$this->insert_id = '';

		foreach ($fields as $field_name => $fld) {
			$user_field_data = (isset($user_data[$field_name])) ? $user_data[$field_name] : '';

			// For file uploads, data will not be in POST var.
			if (!$user_field_data && (isset($_FILES['name'][$field_name])))
				$user_field_data = $_FILES['name'][$field_name];

			if ($field_name == $pk) {
				$fkeys = (isset($fld['foreign_keys'])) ? $fld['foreign_keys'] : (isset($this->relations) ? $this->relations : '');
				if (intval($user_field_data) > 0) {
					$this->insert_id = $user_field_data;
					$params = array();
					$params['where'] = array($pk => $this->insert_id);
					$old_data = $this->find_one($params);
					if (!$old_data)
						end_script("Record with ID {$this->insert_id} not FOUND");

					//SET WHERE CLAUSE FOR UPDATE QUERY. NO OTHER QUERY MUST RUN BEFORE IT.
					$mode = "update";
				} else {
					$mode = "insert";
					//Dont add this premium key in $record
					continue;
				}
			}

			// Skipping for default value incase it's an insert and user did not set any value for it.
			if (!isset($user_field_data) && $mode == "insert" && !in_array($fld['type'], array("checkbox", "switch")))
				continue;

			if (isset($fld['relation_type'])) {
				switch ($fld['relation_type']) {
					case "many_to_many":
						$model = $fld['relation_model'];
						if (is_array($user_field_data)) {
							foreach ($user_field_data  as $key => $val) {
								$many_to_many_data[$model][$key][$field_name] = $val;
							}
						}

						break;
					case "one_to_many":
						break;
				}

				continue; //Do not insert the data in current MODEL table if it's a relation
			}

			switch ($fld['type']) {
				case 'fileupload':

					if (isset($config[$fld['upload_config']]))
						$destination_path = $config[$fld['upload_config']];
					else
						$destination_path = $config['site_upload_default'] . $this->_table . "/";

					if (is_array($_FILES) && $_FILES[$this->_table]['name'][$field_name]) {
						$file_params = array();
						$file_params['name'] = $_FILES[$this->_table]['name'][$field_name];
						$file_params['tmp_name'] = $_FILES[$this->_table]['tmp_name'][$field_name];
						$file_params['destination_path'] = $destination_path;
						$file_params['field_config'] = $fld;
						$file_params['old_data'] = (isset($old_data)) ? $old_data : '';

						$uploadhelper = new Fileupload_helper($file_params);
						$uploaded = $uploadhelper->do_upload();
						if (isset($uploaded['error'])) {
							$referer = $this->agent->referrer();
							//pre($uploaded['error']);
							if (strpos($referer, '?')) {
								$url = explode('?', $referer);
								redirect($url[0] . "?msgtype=error&msg=" . $uploaded['error'], 'refresh');
							} else {
								redirect($referer . "?msgtype=error&msg=" . $uploaded['error'], 'refresh');
							}
							exit();
						} else {
							//$record = $record + $uploaded ;
							if (!empty($record)) {
								$record = $record + $uploaded;
							} else {
								$record = $uploaded;
							}
						}

						// Remove Old file - If availabel


					}
					continue 2; //Awesomely, Skip remaining flow for this iteration
					break;
				case 'audiofileupload':

					if ($config[$fld['upload_config']])
						$destination_path = $config[$fld['upload_config']];
					else
						$destination_path = $config['site_upload_default'] . $this->_table . "/";

					if (is_array($_FILES) && $_FILES[$this->_table]['name'][$field_name]) {
						$file_params = array();
						$file_params['name'] = $_FILES[$this->_table]['name'][$field_name];
						$file_params['tmp_name'] = $_FILES[$this->_table]['tmp_name'][$field_name];
						$file_params['destination_path'] = $destination_path;
						$file_params['field_config'] = $fld;
						$file_params['old_data'] = (isset($old_data)) ? $old_data : '';

						$uploadhelper = new Fileupload_helper($file_params);
						$uploaded = $uploadhelper->do_upload();
						if (isset($uploaded['error'])) {
							$referer = $this->agent->referrer();
							//pre($uploaded['error']);
							if (strpos($referer, '?')) {
								$url = explode('?', $referer);
								redirect($url[0] . "?msgtype=error&msg=" . $uploaded['error'], 'refresh');
							} else {
								redirect($referer . "?msgtype=error&msg=" . $uploaded['error'], 'refresh');
							}
							exit();
						} else {
							//$record = $record + $uploaded ;
							if (!empty($record)) {
								$record = $record + $uploaded;
							} else {
								$record = $uploaded;
							}
						}

						// Remove Old file - If availabel


					}
					continue 2; //Awesomely, Skip remaining flow for this iteration
					break;
				case 'customfileupload':

					if ($config[$fld['upload_config']])
						$destination_path = $config[$fld['upload_config']];
					else
						$destination_path = $config['site_upload_default'] . $this->_table . "/";

					if (is_array($_FILES) && $_FILES[$this->_table]['name'][$field_name]) {
						$file_params = array();
						$file_params['name'] = $_FILES[$this->_table]['name'][$field_name];
						$file_params['tmp_name'] = $_FILES[$this->_table]['tmp_name'][$field_name];
						$file_params['destination_path'] = $destination_path;
						$file_params['field_config'] = $fld;
						$file_params['old_data'] = (isset($old_data)) ? $old_data : '';

						$uploadhelper = new Fileupload_helper($file_params);
						$uploaded = $uploadhelper->do_upload();
						if (isset($uploaded['error'])) {
							$referer = $this->agent->referrer();
							//pre($uploaded['error']);
							if (strpos($referer, '?')) {
								$url = explode('?', $referer);
								redirect($url[0] . "?msgtype=error&msg=" . $uploaded['error'], 'refresh');
							} else {
								redirect($referer . "?msgtype=error&msg=" . $uploaded['error'], 'refresh');
							}
							exit();
						} else {
							//$record = $record + $uploaded ;
							if (!empty($record)) {
								$record = $record + $uploaded;
							} else {
								$record = $uploaded;
							}
						}

						// Remove Old file - If availabel


					}
					continue 2; //Awesomely, Skip remaining flow for this iteration
					break;
				case 'videoupload':

					if ($config[$fld['upload_config']])
						$destination_path = $config[$fld['upload_config']];
					else
						$destination_path = $config['site_upload_default'] . $this->_table . "/";

					if (is_array($_FILES) && $_FILES[$this->_table]['name'][$field_name]) {
						$file_params = array();
						$file_params['name'] = $_FILES[$this->_table]['name'][$field_name];
						$file_params['tmp_name'] = $_FILES[$this->_table]['tmp_name'][$field_name];
						$file_params['size'] = $_FILES[$this->_table]['size'][$field_name];
						$file_params['destination_path'] = $destination_path;
						$file_params['field_config'] = $fld;
						$file_params['old_data'] = (isset($old_data)) ? $old_data : '';

						$uploadhelper = new Fileupload_helper($file_params);
						$uploaded = $uploadhelper->do_upload();
						if (isset($uploaded['error'])) {
							$referer = $this->agent->referrer();
							//pre($uploaded['error']);
							if (strpos($referer, '?')) {
								$url = explode('?', $referer);
								redirect($url[0] . "?msgtype=error&msg=" . $uploaded['error'], 'refresh');
							} else {
								redirect($referer . "?msgtype=error&msg=" . $uploaded['error'], 'refresh');
							}
							exit();
						} else {
							//$record = $record + $uploaded ;
							if (!empty($record)) {
								$record = $record + $uploaded;
							} else {
								$record = $uploaded;
							}
						}

						// Remove Old file - If availabel


					}
					continue 2; //Awesomely, Skip remaining flow for this iteration
					break;

				case 'switch':
					$record[$field_name] = intval($user_field_data);
					break;
				case 'none':
					break;
				default:
					//if(($user_field_data !== null))
					if (!empty($user_field_data) || $user_field_data == 0)
						$record[$field_name] = $user_field_data;
					break;
			}
			if ((isset($fld['index'])) && ($fld['index'] == 'foreign')) {
				$record[$field_name] = isset($record[$field_name]) ? $record[$field_name] :  $_POST['indices']['pk'][$fld['relation']];
			}
		}

		if ($mode) {

			if ($mode == "update" && $this->insert_id)
				$this->db->where($pk, $this->insert_id);

			// Save Model
			if ($this->db->$mode($this->_table, $record)) {
				$this->insert_id = $this->insert_id ? $this->insert_id : $this->db->insert_id();

				// Do relation stuff if relation exists..
				if ($this->insert_id && isset($many_to_many_data) && is_array($many_to_many_data) && count($many_to_many_data)) {

					foreach ($many_to_many_data as $model => $mdata) {
						$where = array();
						$where[$fkeys[$model]['pk']] = $this->insert_id;
						$this->$model->delete($where);
						foreach ($mdata as $dt) {
							# code...
							$dt[$fkeys[$model]['pk']] = $this->insert_id;
							$this->$model->set_attributes($dt);
							$this->$model->save();
						}
					}
				}
			} else {
				pre("$mode failed for {$this->_table}");
			}
		} else {
			pre("No PK set for " . $this->_table . " in get_fields function in model");
		}
		$_POST['indices']['pk'][$this->_table] = $this->insert_id;
		return $this->insert_id;
	}

	// Upload multiple files ($index is for multiple file upload)
	public function save_multiple_files($index)
	{
		global $config;
		$fields = $this->get_fields();
		$user_data = $this->_attributes;
		$pk = $this->get_pk();
		$this->insert_id = '';
		foreach ($fields as $field_name => $fld) {
			$user_field_data = (isset($user_data[$field_name])) ? $user_data[$field_name] : '';

			// For file uploads, data will not be in POST var.
			if (!$user_field_data && (isset($_FILES['name'][$field_name])))
				$user_field_data = $_FILES['name'][$field_name];

			if ($field_name == $pk) {
				$fkeys = (isset($fld['foreign_keys'])) ? $fld['foreign_keys'] : $this->relations;
				if (intval($user_field_data) > 0) {
					$this->insert_id = $user_field_data;
					$params = array();
					$params['where'] = array($pk => $this->insert_id);
					$old_data = $this->find_one($params);

					if (!$old_data)
						end_script("Record with ID {$this->insert_id} not FOUND");

					//SET WHERE CLAUSE FOR UPDATE QUERY. NO OTHER QUERY MUST RUN BEFORE IT.
					$mode = "update";
				} else {
					$mode = "insert";
					//Dont add this premium key in $record
					continue;
				}
			}

			// Skipping for default value incase it's an insert and user did not set any value for it.
			if (!isset($user_field_data) && $mode == "insert" && !in_array($fld['type'], array("checkbox", "switch")))
				continue;

			if ($fld['relation_type']) {
				switch ($fld['relation_type']) {
					case "many_to_many":
						$model = $fld['relation_model'];
						if (is_array($user_field_data)) {
							foreach ($user_field_data  as $key => $val) {
								$many_to_many_data[$model][$key][$field_name] = $val;
							}
						}

						break;
					case "one_to_many":
						break;
				}

				continue; //Do not insert the data in current MODEL table if it's a relation
			}

			switch ($fld['type']) {
				case 'fileupload':
					if ($config[$fld['upload_config']])
						$destination_path = $config[$fld['upload_config']];
					else
						$destination_path = $config['site_upload_default'] . $this->_table . "/";

					if (is_array($_FILES) && $_FILES[$this->_table]['name'][$field_name][$index]) {
						$file_params = array();
						$file_params['name'] = $_FILES[$this->_table]['name'][$field_name][$index];
						$file_params['tmp_name'] = $_FILES[$this->_table]['tmp_name'][$field_name][$index];
						$file_params['destination_path'] = $destination_path;
						$file_params['field_config'] = $fld;
						$file_params['old_data'] = (isset($old_data)) ? $old_data : '';

						$uploadhelper = new Fileupload_helper($file_params);
						$uploaded = $uploadhelper->do_upload();
						if ($uploaded['error']) {
							$referer = $this->agent->referrer();
							//pre($uploaded['error']);
							if (strpos($referer, '?')) {
								$url = explode('?', $referer);
								redirect($url[0] . "?msgtype=error&msg=" . $uploaded['error'], 'refresh');
							} else {
								redirect($referer . "?msgtype=error&msg=" . $uploaded['error'], 'refresh');
							}
							exit();
						} else {
							//$record = $record + $uploaded ;
							if (!empty($record)) {
								$record = $record + $uploaded;
							} else {
								$record = $uploaded;
							}
						}

						// Remove Old file - If availabel


					}
					continue 2; //Awesomely, Skip remaining flow for this iteration
					break;
				case 'switch':
					$record[$field_name] = intval($user_field_data);
					break;
				default:
					if (($user_field_data !== null))
						$record[$field_name] = $user_field_data;
					break;
			}
			if ($fld['index'] == 'foreign') {
				$record[$field_name] = isset($record[$field_name]) ? $record[$field_name] :  $_POST['indices']['pk'][$fld['relation']];
			}
		}

		if ($mode) {

			if ($mode == "update" && $this->insert_id)
				$this->db->where($pk, $this->insert_id);

			// Save Model
			if ($this->db->$mode($this->_table, $record)) {
				$this->insert_id = $this->insert_id ? $this->insert_id : $this->db->insert_id();

				// Do relation stuff if relation exists..
				if ($this->insert_id && is_array($many_to_many_data) && count($many_to_many_data)) {

					foreach ($many_to_many_data as $model => $mdata) {
						$where = array();
						$where[$fkeys[$model]['pk']] = $this->insert_id;
						$this->$model->delete($where);
						foreach ($mdata as $dt) {
							# code...
							$dt[$fkeys[$model]['pk']] = $this->insert_id;
							$this->$model->set_attributes($dt);
							$this->$model->save();
						}
					}
				}
			} else {
				pre("$mode failed for {$this->_table}");
			}
		} else {
			pre("No PK set for " . $this->_table . " in get_fields function in model");
		}
		$_POST['indices']['pk'][$this->_table] = $this->insert_id;
		return $this->insert_id;
	}

	public function update_relations($id)
	{
		if (!array_filled($this->relations)) {
			// No RELATIONS Defined....
			return true;
		}
		foreach ($this->relations as $table => $relate) {

			/*
				If this relation needs to be updated from this model,
				it must have relative field defined in get_fields();
			 */
			$field = $this->get_fields($table);
			if (!$field)
				continue;

			$model_obj = $this->{"model_" . $table};
			switch ($relate['type']) {
				case 'has_many':
					$multi_rec = array();
					if (is_array($_POST[$table][$relate['other_key']]))
						foreach ($_POST[$table][$relate['other_key']] as $foreign_id) {
							$user_data = array();
							$user_data[$relate['other_key']] = $foreign_id;
							$user_data[$relate['own_key']] = $id;
							$multi_rec[] = $user_data;
						}
					break;

				default:
					# code...
					break;
			} // END- RELATION TYPE Switch

			$where_relation = array();
			$where_relation[$relate['own_key']] = $id;
			$model_obj->delete_record($where_relation);
			$update_table = $model_obj->insert_batch_record($multi_rec);
		}  // END-FOREACH - Relation
	}

	public function get_relation_data($id)
	{
		$relations_data = array();
		if (array_filled($this->relations)) {
			foreach ($this->relations as $table => $relate) {
				$model_obj = $this->{"model_" . $table};

				switch ($relate['type']) {
					case 'has_many':
						$params = array();
						$params['where'][$relate['own_key']] = $id;

						$relations_data[$table][$relate['other_key']] = $model_obj->find_all_list($params, $relate['other_key']);

						break;

					default:
						# code...
						break;
				} // END- RELATION TYPE Switch

			}  // END-FOREACH - Relation
		}
		return $relations_data;
	}

	public function reorder_records($params = [])
	{
		if (!count($params))
			return false;

		extract($params);
		$table = $this->table_name();
		$order_field = $this->get_order_field_name();

		// Dragged Down / Order Down
		if (($next_order && $order > $next_order) || ($order > $previous_order && $previous_order)) {
			$where = "$order_field>=" . intval($previous_order) . " AND $order_field < " . intval($order);
			$data = $order_field . ' = ' . $order_field . '+1';
			$query = "UPDATE $table SET $data WHERE $where";
			$this->db->query($query);

			$data = $order_field . " = " . intval($previous_order);
			$query = "UPDATE $table SET $data WHERE id=" . intval($id);

			if ($this->db->affected_rows())
				$this->db->query($query);
		}

		// Dragged Up / Order Up
		if (($order < $next_order && $next_order) || ($order < $previous_order && $previous_order)) {
			$where = "$order_field<=" . intval($next_order) . " AND $order_field > " . intval($order);
			$data = $order_field . ' = ' . $order_field . '-1';
			$query = "UPDATE $table SET $data WHERE $where";
			$this->db->query($query);

			$data = $order_field . " = " . intval($next_order);
			$query = "UPDATE $table SET $data WHERE id=" . intval($id);

			if ($this->db->affected_rows())
				$this->db->query($query);
		}
	}

	/*DELETE*/
	public function delete($where = array())
	{
		if (is_array($where) && count($where))
			$this->db->delete($this->_table, $where);
		return $this->db->affected_rows();
	}

	/*SET Attributes that will be used for insert or update*/
	public function set_attributes($data)
	{
		$this->_attributes = $data;
	}

	// Email Structure
	public function email_structure($to, $from, $subject, $msg, $optional = array())
	{
		$this->load->library('email');

		$send_from =  $from;
		$name  = isset($optional['from_name']) ? $optional['from_name'] : $send_from;
		$send_to = $to;

		$message = $msg;

		$this->email->from($send_from, $name);
		$this->email->to($send_to);
		$this->email->subject($subject);
		$this->email->set_mailtype("html");
		$this->email->message($message);

		if (ENVIRONMENT == 'development') {
			return true;
		}

		if ($this->email->send()) {
			return true;
		} else {
			return false;
			//echo $this->email->print_debugger();
		}
	}

	public function email($send_to = '', $send_from = '', $subject = '',  $msg = '')
	{
		// core php
		if (1 == 2) {
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: <' . $send_from . '>' . "\r\n";
			//$headers .= 'Cc: myboss@example.com' . "\r\n";

			if (ENVIRONMENT == 'development')
				return true;
			else
				mail($send_to, $subject, $msg, $headers);
		}
		// CI u
		if (1 == 1) {
			$config = array();
			$config['useragent'] = 'CodeIgniter';

			// $config['protocol'] = 'smtp';
			// $config['mailpath'] = '/usr/sbin/sendmail';
			// $config['smtp_host'] = 'ssl://smtp.gmail.com';
			// $config['smtp_user'] = 'daltondeveloper.testing@gmail.com';
			// $config['smtp_pass'] = '@Admin!23#';
			// $config['smtp_port'] = 465;
			// $config['smtp_timeout'] = 5;

			$config['wordwrap'] = TRUE;
			$config['wrapchars'] = 76;
			$config['mailtype'] = 'html';
			$config['charset'] = 'utf-8';
			$config['validate'] = FALSE;
			$config['priority'] = 3;
			$config['crlf'] = "\r\n";
			$config['newline'] = "\r\n";
			$config['bcc_batch_mode'] = FALSE;
			$config['bcc_batch_size'] = 200;

			$this->load->library('email'); // Note: no $config param needed
			$this->email->initialize($config);
			$this->email->from($send_from, $send_from);
			$this->email->to($send_to);
			// $this->email->bcc('info@demo.com');
			$this->email->subject($subject);
			$this->email->message($msg);

			if (ENVIRONMENT == 'development')
				return true;
			else
				$this->email->send();
		}

		return true;
	}
}
// END Model class

/* End of file Model.php */
/* Location: ./system/core/Model.php */