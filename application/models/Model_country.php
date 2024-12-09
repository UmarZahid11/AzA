<?php

/**
 * Model_country
 */
class Model_country extends MY_Model {

    protected $_table    = 'country';
    protected $_pk    = 'id';
    public $pagination_params = array();
    public $_per_page    = 20;

    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Method get_country
     *
     * @param int $id
     *
     * @return void
     */
    public function get_country($id)
    {
        $id = intval($id);
        if(!$id)
            return false;

        return $this->find_by_pk($id);
    }

    /**
     * Method find_by_alias
     *
     * @param string $country_code
     *
     * @return void
     */
    public function find_by_alias($country_code)
    {
        $param = array();
        $param['where']['sb_country_alias'] = $country_code;
        return $this->model_country->find_one_active($param) ? $this->model_country->find_one_active($param) : '';
    }

    /**
     * Method get_country_name
     *
     * @param int $id
     *
     * @return void
     */
    public function get_country_name($id)
    {
        $country =  $this->get_country($id);
        return $country['country'] ;
    }

    /**
     * Method get_country_list
     *
     * @return void
     */
    public function get_country_list()
    {
        return $this->find_all_list(array() , "country");
    }

    /**
     * Method get_id
     *
     * @param string $name
     *
     * @return void
     */
    public function get_id($name)
    {
        $param['where']['country'] = $name;
        $row =  $this->find_one($param);

        return $row['id'];
    }
}
