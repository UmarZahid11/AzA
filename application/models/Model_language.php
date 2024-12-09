<?php

/**
 * Model_language
 */
class Model_language extends MY_Model
{
    protected $_table    = 'language';
    protected $_field_prefix    = 'language_';
    protected $_pk    = 'language_id';
    protected $_status_field    = 'language_status';
    public $pagination_params = array();
    public $relations = array();
    public $dt_params = array();
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

}
