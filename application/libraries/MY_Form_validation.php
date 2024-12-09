<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		Muhammad uzair
 * @category	Validation
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/form_validation.html
 */
class MY_Form_validation extends CI_Form_validation
{

	protected $CI;
	protected $_field_data			= array();
	protected $_config_rules		= array();
	protected $_error_array			= array();
	protected $_error_messages		= array();
	protected $_error_prefix		= '<p>';
	protected $_error_suffix		= '</p>';
	protected $error_string			= '';
	protected $_safe_form_data		= FALSE;

	/**
	 * Constructor
	 */
	public function is_unique($str, $field)
	{
		list($table, $field) = explode('.', $field);
		$db_prefix = $this->CI->db->dbprefix;
		$q = $this->CI->db->query("SHOW KEYS FROM $db_prefix" . "$table WHERE Key_name = 'PRIMARY'")->row();
		$primary_key = $q->Column_name;
		$post = $this->CI->input->post($table);
		$id_get = (isset($post[$primary_key])) ? $post[$primary_key] : 0;
		if ($id_get > 0) :
			$query = $this->CI->db->limit(1)->get_where($table, array($field => $str, $primary_key . ' !=' => $id_get, $table . '_status !=' => 2));
		else :
			$query = $this->CI->db->limit(1)->get_where($table, array($field => $str, $table . '_status !=' => 2));
		endif;

		return $query->num_rows() === 0;
	}

	/*public function valid_url($str)
    {
        return (filter_var($str, FILTER_VALIDATE_URL) !== FALSE);
    }*/

	function valid_url($url)
	{
		if (preg_match('/^(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-z]{2,5}' . '((:[0-9]{1,5})?\\/.*)?$/i', $url)) {
			return $url;
		} else {
			return false;
		}
	}
}
// END Form Validation Class

/* End of file Form_validation.php */
/* Location: ./system/libraries/Form_validation.php */
