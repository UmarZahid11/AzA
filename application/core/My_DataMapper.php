<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TKD Model Wrapper Class.
 *
 * @package		TKDModel
 * @author		Muhammad Uzair Khan (Muhammad.Uzair@tradekey.com)
 * @version		1.0
 * @since		Version 1.0 2014
 *
 *
 */

class My_DataMapper extends DataMapper
{

	private static $instance;

	/**
	 * Constructor
	 */
	public $_attributes = array();

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}


	public function set_timezone()
	{
		if (MYSQL_TIME_ZONE)
			$this->db->query("SET time_zone  = '" . MYSQL_TIME_ZONE . "'");
	}
}
// END Model class

/* End of file Model.php */
/* Location: ./system/core/Model.php */