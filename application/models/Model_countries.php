<?php

/**
 * Model_countries
 */
class Model_countries extends MY_Model {

    protected $_table    = 'countries';
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
     * Method get_countries
     *
     * @param int $id
     *
     * @return void
     */
    public function get_countries($id)
    {
        $id = intval($id);
        if(!$id)
            return false;

        return $this->find_by_pk($id);
    }

    /**
     * Method find_by_alias
     *
     * @param string $countries_code
     *
     * @return void
     */
    public function find_by_alias($countries_code)
    {
        $param = array();
        $param['where']['sb_countries_alias'] = $countries_code;
        return $this->model_countries->find_one_active($param) ? $this->model_countries->find_one_active($param) : '';
    }

    /**
     * Method get_countries_name
     *
     * @param int $id
     *
     * @return void
     */
    public function get_countries_name($id)
    {
        $countries =  $this->get_countries($id);
        return $countries['countries'] ;
    }

    /**
     * Method get_countries_list
     *
     * @return void
     */
    public function get_countries_list()
    {
        return $this->find_all_list(array() , "countries");
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
        $param['where']['countries'] = $name;
        $row =  $this->find_one($param);
        return $row['id'];
    }
}
