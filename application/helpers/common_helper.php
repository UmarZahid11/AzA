<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 7.4.29 or newer
 *
 * @package		Codeigniter
 * @copyright	Copyright © 2022 Aza.
 * @version		Version 1.0
 *
 */

if (!function_exists('pre')) {
	function pre()
	{
		$args = func_get_args();

		echo "<pre>";
		foreach ($args as $ar)
			print_r($ar);
		die;
	}
}

if (!function_exists('end_script')) {
	function end_script($message)
	{
		echo $message;
		exit();
	}
}

if (!function_exists('end_script_json')) {
	function end_script_json($arr)
	{
		$arr = is_array($arr) ? json_encode($arr) : $arr;
		end_script($arr);
	}
}

if (!function_exists('debug')) {
	function debug($param, $exit = 0)
	{
		echo "<pre>";
		print_r($param);
		echo "</pre>";
		if ($exit)
			exit;
	}
}

if (!function_exists('dd')) {
    // 	function dd($param)
    // 	{
    // 		echo "<pre>";
    // 		print_r($param);
    // 		echo "</pre>";
    // 		exit;
    // 	}
    function dd()
    {
       array_map(function($x) { var_dump($x); }, func_get_args()); die;
    }
}

if (!function_exists('prevar')) {
	function prevar($params)
	{
		var_dump($params);
		die;
	}
}

// Check if Array is filled or empty
if (!function_exists('array_filled')) {
	function array_filled($array = array())
	{
		return (is_array($array) && count($array));
	}
}

// Return array with same keys as values.
if (!function_exists('array_value_as_key')) {
	function array_value_as_key($array = array())
	{

		$return = array();
		foreach ($array as $value) {
			$return[$value] = $value;
		}
		return $return;
	}
}

// Check if Array is filled or empty
if (!function_exists('nl_to_list')) {
	function nl_to_list($str = "", $start_li = "<li>", $end_li = "</li>")
	{

		return '<ul>' . $start_li . preg_replace("/([\n]+)/", $end_li . $start_li, $str) . $end_li . '</ul>';
	}
}

// Check if Array is filled or empty
if (!function_exists('nl_to_br')) {
	function nl_to_br($str = "")
	{

		return preg_replace("/([\n]+)/", "</br>", $str);
	}
}

// Check if Array is filled or empty
if (!function_exists('prepare_value')) {
	function prepare_value($str = "", $funcs = '')
	{
		$func_array = explode("|", $funcs);
		foreach ($func_array as $fn) {
			if (function_exists($fn))
				$str = $fn($str);
		}
		return $str;
	}
}

// Hidden debug - For LIVE use.
// Protects you site cosmetics while doing all the dirty work in commented HTMLs
if (!function_exists('live_debug')) {
	function live_debug($params)
	{
		echo "<!--LIVE DEBUGGER>";
		var_dump($params);
		echo "-->";
	}
}

// Checks if the view you are dreaming for really exists in reality
if (!function_exists('view_exists')) {
	function view_exists($view, $class = "")
	{

		$view_path = APPPATH . "views/" . $view;
		if (@file_exists($view_path . ".php")) {
			return $view;
		} else {
			return str_replace($class . "/", "default/", $view);
		}
	}
}

// This cutting-edge technology has the ability to cut through any string.
// Just try it out if it's too good to be believed.
if (!function_exists('truncate')) {
	function truncate($text = "", $limit = 150)
	{

		return (strlen($text) > $limit) ? (substr($text, 0, $limit) . "...") : $text;
	}
}

// Occasional JavaScript redirect.
if (!function_exists('redirect_script')) {
	function redirect_script($path)
	{
		global $config;
		ob_clean();
		ob_start();
		echo '<script>window.location="' . $config['base_url'] . $path . '";</script>';
		exit();
	}
}

// Occasional not_found redirect.
if (!function_exists('not_found')) {
	function not_found($msg)
	{

		redirect("404?error=" . urlencode($msg));
		exit();
	}
}

// If Array has an element --- IN_ARRAY.
if (!function_exists('inside_array')) {
	function inside_array($needle, $hey_stack)
	{
		return is_array($hey_stack) && in_array($needle, $hey_stack);
	}
}

// Innovate Payment - Signature verification
if (!function_exists('SignData')) {
	function SignData($post_data, $secretKey, $fieldList)
	{
		$signatureParams = explode(',', $fieldList);
		$signatureString = $secretKey;
		foreach ($signatureParams as $param) {
			if (array_key_exists($param, $post_data)) {
				$signatureString .= ':' . trim($post_data[$param]);
			} else {
				$signatureString .= ':';
			}
		}
		return sha1($signatureString);
	}
}

if (!function_exists('csl_date')) {
	function csl_date($date, $format = "d M, Y h:i:sA")
	{
		return date($format, strtotime($date));
	}
}

// This returns Discount value. The prices must be in BASE Currency . ie. $
if (!function_exists('discount_text')) {
	function discount_text($discount_rate, $discount_type = "value", $currency = "$", $currency_rate = "1.00", $prep_currency = true)
	{
		if ($discount_type == "percent")
			return $prep_currency ? $discount_rate . "%" : $discount_rate;
		else {
			return price($discount_rate, $currency, $currency_rate, $prep_currency);
		}
	}
}

// This returns Discount value. The prices must be in BASE Currency . ie. $
if (!function_exists('discount_value')) {
	function discount_value($discount_rate, $discount_type = "value", $price = 0)
	{
		$discount_rate = floatval($discount_rate);
		$price = floatval($price);

		if ($discount_type == 'percent') {
			$discount_rate = ($price * $discount_rate) / 100;
		}

		return $discount_rate;
	}
}

// This returns price w.r.t to currencies provided in the parameter
if (!function_exists('price')) {
	function price($price, $currency = "$", $currency_rate = "1.00", $prep_currency = true)
	//function price( $price,$currency="£" , $currency_rate = "1.00" , $prep_currency = true )
	{
		if (!$currency_rate)
			$currency_rate = 1.00;

		if (is_numeric($price)) {
			$price = number_format($price / $currency_rate, 2);
		} else {
			$price = number_format(0 / $currency_rate, 2);
		}

		return $prep_currency ? ($currency . "" . $price) : $price;
	}
}

if (!function_exists('price_without_symbol')) {
	function price_without_symbol($price, $currency = "$", $currency_rate = "1.00", $prep_currency = true)
	{
		$ci = &get_instance();

		if (isset($ci->session->userdata['conversion_rate']))
			$currency_rate = $ci->session->userdata['conversion_rate'];

		if (isset($ci->session->userdata['symbol']))
			$currency = $ci->session->userdata['symbol'];


		if (!$currency_rate)
			$currency_rate = $ci->conversion_rate;

		$price = number_format($price * $currency_rate, 2);
		return $prep_currency ? ($price) : $price;
	}
}

if (!function_exists('price_without_sign')) {
	function price_without_sign($price, $currency = "$", $currency_rate = "1.00", $prep_currency = false)
	{
		if (!$currency_rate)
			$currency_rate = 1.00;

		$price = number_format($price / $currency_rate, 2);
		return $prep_currency ? ($currency . "" . $price) : $price;
	}
}

// This returns price from currency provided to Base Currency : PKR
if (!function_exists('price_reverse')) {
	function price_reverse($price, $currency = "$", $currency_rate = "1.00", $prep_currency = true)
	{
		$price = number_format($price * $currency_rate, 2);
		return $prep_currency ? ($currency . " " . $price) : $price;
	}
}

// This one is to return Price formatted w.r.t default Currency setup in session
if (!function_exists('price_default')) {
	function price_default($price, $prep_currency = false)
	{
		global $config;
		return price($price,  $config['currency'],  $config['currency_rate'], $prep_currency);
	}
}

if (!function_exists('can_register')) {
	function can_register($user_data = array(), $registration_cost = 0)
	{
		return ($user_data['credits_total'] - $user_data['credits_consumed'] >= intval($registration_cost));
	}
}

if (!function_exists('label_encode')) {
	function label_encode($text = '')
	{
		return ucfirst(preg_replace("/([-_]+)/", " ", $text));
	}
}

if (!function_exists('recursive_array')) {
	function recursive_array($data, $children, $second = false)
	{
		foreach ($data as $key => $row) {

			$data[$row['category_id']] = $row;
			$data[$key]['children'] = array();

			if (isset($children[$row['category_id']]) && is_array($children[$row['category_id']]))
				$data[$row['category_id']]['children'] = recursive_array($children[$row['category_id']], $children, true);
			else
				return $data;
			return $data;
		}
	}
}

if (!function_exists('is')) {
	function is($variable)
	{
		if (isset($variable) && $variable) {
			return true;
		}
		return false;
	}
}

if (!function_exists('has_value')) {
	function has_value($needle, $haystack)
	{
		if (is_array($haystack))
			return in_array($needle, $haystack);
		else
			return $needle == $haystack;
	}
}

if (!function_exists('to_bit')) {
	function to_bit($is_addon)
	{
		return $is_addon ? 1 : 0;
	}
}

if (!function_exists('order_mask')) {
	function order_mask($id = 0)
	{
		return sprintf(ORDER_NO_MASK, $id);
	}
}

if (!function_exists('g')) {
	function g($var = "")
	{
		global $config;
		if ($var)
			$var = explode(".", $var);
		$return = $config;

		while (is_array($var) && count($var)) {
			$shifted_value = array_shift($var);
			if (isset($return[$shifted_value])) {
				$return = $return[$shifted_value];
			} else {
				$return = NULL;
			}
		}

		return $return;
	}
}

/**
 * Image url
 **/
if (!function_exists('get_image')) {
	function get_image($image_path, $image_name)
	{
		global $config;

		if (empty($image_name))
			return $config['base_url'] . 'assets/front_assets/images/image-placeholder.png';
		else
			return $config['base_url'] . $image_path . $image_name;
	}
}

/**
 * Image url
 **/
if (!function_exists('get_user_image')) {
	function get_user_image($image_path, $image_name)
	{
		global $config;

		if (empty($image_name))
			return $config['base_url'] . 'assets/front_assets/images/user.png';
		else
			return $config['base_url'] . $image_path . $image_name;
	}
}

/**
 * Array Intersect working in Cross.
 * @params : flip_second --
 * 					Flip second array and then intersect.
 *					Or flip first and then intersect
 */
if (!function_exists('array_intersect_cross')) {
	function array_intersect_cross($array1, $array2, $flip_second = true)
	{
		if (!$array1 || !$array2)
			return false;

		if ($flip_second)
			$array2 = array_flip($array2);
		else
			$array1 = array_flip($array1);

		$array1 = array_intersect($array1, $array2);

		return $flip_second ? $array1 : array_flip($array1);
	}
}

if (!function_exists('get_selected_navigation')) {
	function get_selected_navigation($class_name)
	{
		$ci = &get_instance();
		return ($ci->router->fetch_class() == $class_name ? 'class="active"' : '');
	}
}

if (!function_exists('get_facebook_share')) {
	function get_facebook_share()
	{
		global $config;
		$request_url = substr($_SERVER['REQUEST_URI'], 1);
		$facebook_link = "https://www.facebook.com/sharer/sharer.php?u=";
		$base_url = $config['base_url'];

		return $facebook_link . $base_url . $request_url;
	}
}

if (!function_exists('get_twitter_share')) {
	function get_twitter_share()
	{
		global $config;
		$request_url = substr($_SERVER['REQUEST_URI'], 1);
		$twitter_link = "https://twitter.com/home?status=";
		$base_url = $config['base_url'];

		return $twitter_link . $base_url . $request_url;
	}
}

if (!function_exists('get_pinterest_share')) {
	function get_pinterest_share()
	{

		global $config;
		$request_url = substr($_SERVER['REQUEST_URI'], 1);
		$pinterest_link = "https://pinterest.com/pin/create/button/?url=";
		$base_url = $config['base_url'];

		return $pinterest_link . $base_url . $request_url;
	}
}

// Encrypt String Start
if (!function_exists('string_encrypt')) {
	function string_encrypt($input)
	{
		$cryptKey     = 'e01c9261bf1626d678acdc44f1e06826';
		$pass_encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $input, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
		return ($pass_encoded);
	}
}
// Encrypt String End

// Decrypt String Start
if (!function_exists('string_decrypt')) {
	function string_decrypt($input)
	{
		$cryptKey    = 'e01c9261bf1626d678acdc44f1e06826';
		$pass_decode = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
		return $pass_decode;
	}
}
// Decrypt String End

// Time ago String Start
if (!function_exists('timeago')) {
	function timeago($date)
	{
		$timestamp = strtotime($date);

		$strTime = array("second", "minute", "hour", "day", "month", "year");
		$length = array("60", "60", "24", "30", "12", "10");

		$currentTime = time();

		if ($currentTime >= $timestamp) {
			$diff     = time() - $timestamp;
			for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
				$diff = $diff / $length[$i];
			}

			$diff = round($diff);
			return $diff . " " . $strTime[$i] . "(s) ago";
		}
		return $date;
	}
}
// Time ago String End

// Search Highlighter start
if (!function_exists('text_highlights')) {
	function text_highlights($text, $words, $case = false)
	{
		$words = trim($words);
		//$words_array = explode(',', $words);

		/*$regex = ($case !== false) ? '/\b(' . implode('|', array_map('preg_quote', $words_array)) . ')\b/i' : '/\b(' . implode('|', array_map('preg_quote', $words_array)) . ')\b/';
        foreach($words_array as $word) {
            if(strlen(trim($word)) != 0)
                $text = preg_replace($regex, '<font style="background: yellow";>$1</font>', $text);
        }*/

		// WORKING CODE
		$text = str_ireplace($text, '<label style="background: yellow;">' . $text . '</label>', $words);

		//$pattern = '/\b('.$text.')\b/i';
		//$text = preg_replace($pattern, "<label style='background: yellow;'>$1</label>", $words);

		/*$pattern = "/$text/i";
        if(preg_match($pattern, $words)){
            $text = preg_replace($pattern, "<label style='background: yellow;'>$1</label>", $words);
        }*/

		/*$p = preg_quote($words, $text);  // The pattern to match
        $text = preg_replace("/($p)/i",'<span style="background:yellow;">$1</span>',$words);*/

		return $text;
	}
}
// Search Highlighter end

function time_difference($start_date, $end_date)
{
	$now = new DateTime($end_date);
	$ago = new DateTime($start_date);
	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}
	$string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) : 'Today';
}

function time_difference1($departure_time, $arrival_time)
{
	$diff = $departure_time->diff($arrival_time);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}
	$string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) : 'just now';
}

// Calculate File SIZE IN GB,MB,KB
function formatSizeUnits($bytes)
{
	if ($bytes >= 1073741824) {
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	} elseif ($bytes >= 1048576) {
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	} elseif ($bytes >= 1024) {
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	} elseif ($bytes > 1) {
		$bytes = $bytes . ' bytes';
	} elseif ($bytes == 1) {
		$bytes = $bytes . ' byte';
	} else {
		$bytes = '0 bytes';
	}

	return $bytes;
}

// Convert milli second to seconds
function millitosecond($video_millis = 0)
{
	$seconds = floor($video_millis / 1000);
	$minutes = floor($seconds / 60);
	$hours = floor($minutes / 60);
	$milliseconds = $seconds % 1000;
	$seconds = $seconds % 60;
	$minutes = $minutes % 60;

	$format = '%u:%02u:%02u.%03u';
	$time = sprintf($format, $hours, $minutes, $seconds, $milliseconds);
	$vcl = rtrim($time, '0');

	return $vcl;
}

// Convert Bytes to SIZE
function formatBytes($size, $precision = 2)
{
	if ($size > 0) {
		$size = (int) $size;
		$base = log($size) / log(1024);
		$suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

		return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	} else {
		return $size;
	}
}
// Search Highlighter end

if (!function_exists('validateDate')) {
	function validateDate($date, $format = 'Y-m-d')
	{
		$dt = DateTime::createFromFormat($format, $date);
		return $dt && $dt->format($format) == $date;
	}
}

if (!function_exists('isValidDate')) {
	function isValidDate($date, $format = 'Y-m-d')
	{
		return $date == date($format, strtotime($date));
	}
}

if (!function_exists('parseDuration')) {
	function parseDuration($timestamp)
	{
		$object = new DateInterval($timestamp);
		$duration = ($object->d ? $object->d . 'd ' : '') . ($object->h ? $object->h . 'h ' : '') . ($object->i ? $object->i . 'min' : '');
		return $duration;
	}
}

if (!function_exists('getRealIpAddr')) {
	function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}

if (!function_exists('slugify')) {
	function slugify($text, string $divider = '-')
	{
		// replace non letter or digits by divider
		$text = preg_replace('~[^\pL\d]+~u', $divider, $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, $divider);

		// remove duplicate divider
		$text = preg_replace('~-+~', $divider, $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}
}

if (!function_exists('get_client_ip')) {

	function get_client_ip()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		} else if (isset($_SERVER['REMOTE_ADDR'])) {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		} else {
			$ipaddress = 'UNKNOWN';
		}

		return $ipaddress;
	}
}


if (!function_exists('get_location')) {
    function get_location($type = 'string', $PublicIP = '') {
    
        $PublicIP = $PublicIP ? $PublicIP : get_client_ip();
    
    	$json = "";
    	if (ini_get('allow_url_fopen')) {
			try {
    			// $json = file_get_contents("http://ipinfo.io/$PublicIP/geo");
			} catch(Exception $e) { }
    	}

		if($json) {
			$decoded_json     = json_decode($json, true);
			$country  = isset($decoded_json['country']) ? $decoded_json['country'] : '';
			$region   = isset($decoded_json['region']) ? $decoded_json['region'] : '';
			$city     = isset($decoded_json['city'])  ? $decoded_json['city'] : '';
		
			switch ($type) {
				case 'string':
					return ($city ? $city . ", " : '') . ($region ? $region . ", " : '') . ($country ?? '');
					break;
				case 'json':
					return $json;
					break;
				case 'array':
					return $decoded_json;
					break;
				case 'ip':
					return $PublicIP;
					break;
				case 'country':
					return $country;
					break;
				case 'region':
					return $region;
					break;
				case 'city':
					return $city;
					break;
			}
		}
    	return NULL;
    }
}

if (!function_exists('order_no')) {
	function order_no($param)
	{
		$order_no = "INV-000$param";
		return $order_no;
	}
}

if (!function_exists('ticket_no')) {
	function ticket_no($param)
	{
		$order_no = "HMG-TK-000$param";
		return $order_no;
	}
}

if (!function_exists('__')) {
	function __($string)
	{
		$ci = &get_instance();
		if ($ci->lang->line($string, FALSE)) {
			return $ci->lang->line($string, FALSE);
		} else {
			return $string;
		}
	}
}

if (!function_exists('concat_name')) {
	function concat_name($string1, $string2)
	{
		return ucfirst($string1) . ' ' . ucfirst($string2);
	}
}

if (!function_exists('random_digits')) {
	function random_digits($length = 4)
	{
		return substr(str_shuffle("0123456789"), 0, $length);
	}
}

if (!function_exists('csrf_token')) {
	function csrf_token($length = 35)
	{
		return bin2hex(random_bytes($length));
	}
}

if (!function_exists('thousandsCurrencyFormat')) {
	function thousandsCurrencyFormat($num)
	{

		if ($num > 1000) {

			$x = round($num);
			$x_number_format = number_format($x);
			$x_array = explode(',', $x_number_format);
			$x_parts = array('k', 'm', 'b', 't');
			$x_count_parts = count($x_array) - 1;
			$x_display = $x;
			$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
			$x_display .= $x_parts[$x_count_parts - 1];

			return $x_display;
		}

		return $num;
	}
}

if (!function_exists('strip_string')) {
	function strip_string($string, $limit = 50)
	{
		return strlen($string) > $limit ? substr($string, 0, $limit) . "..." : $string;
	}
}

if (!function_exists('record_detail')) {
	function record_detail($offset, $obj, $obj_count)
	{
		return 'Showing ' . ((count($obj) > 0) ? ($offset + 1) : (0)) . ' - '  . (count($obj) > 0 ? (count($obj) + $offset) : 0) . ' of total ' . (count($obj) > 0 ? $obj_count : 0) . ' record(s).';
	}
}

if (!function_exists('percent_amount')) {
	function percent_amount($amount, $percent)
	{
		return ($percent * $amount) / 100;
	}
}

if (!function_exists('job_budget_string')) {
	function job_budget_string($job_salary_lower, $job_salary_upper = 0, $job_salary_interval = '')
	{
		return (isset($job_salary_lower) ? price($job_salary_lower) : price(0)) . (is($job_salary_upper) ? (' - ' . price($job_salary_upper) . ((is($job_salary_interval) ? ' / ' . $job_salary_interval : ''))) : '');
	}
}

if (!function_exists('job_budget_int')) {
	function job_budget_int($job_salary_lower, $job_salary_upper = 0, $job_salary_interval = '')
	{
		return (isset($job_salary_lower) ? ($job_salary_lower) : (0)) . (is($job_salary_upper) ? (' - ' . ($job_salary_upper) . ((is($job_salary_interval) ? ' / ' . $job_salary_interval : ''))) : '');
	}
}

if (!function_exists('get_remote_file_info')) {
	function get_remote_file_info($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_NOBODY, TRUE);
		$data = curl_exec($ch);
		$fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		$httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return [
			'fileExists' => (int) $httpResponseCode == 200,
			'fileSize' => (int) $fileSize
		];
	}
}

if (!function_exists('is_multi_array')) {
	function is_multi_array(array $array)
	{
		return is_array($array[array_key_first($array)]);
	}
}

if (!function_exists('build_post_fields')) {
	function build_post_fields($data, $existingKeys = '', &$returnArray = [])
	{
		if (($data instanceof CURLFile) or !(is_array($data) or is_object($data))) {
			$returnArray[$existingKeys] = $data;
			return $returnArray;
		} else {
			foreach ($data as $key => $item) {
				build_post_fields($item, $existingKeys ? $existingKeys . "[$key]" : $key, $returnArray);
			}
			return $returnArray;
		}
	}
}

if (!function_exists('multiple_property_exists')) {
	function multiple_property_exists(object $object, array $array)
	{
		if (!is_object($object)) {
			return FALSE;
		}
		if (!empty($array)) {
			foreach ($array as $key => $value) {
				if (!property_exists($object, $value)) {
					return FALSE;
				}
				if (array_key_last($array) == $key) {
					return TRUE;
				}
			}
		}
		return FALSE;
	}
}

/**
 * @param array      $array
 * @param int|string $position
 * @param mixed      $insert
 */
function array_insert(&$array, $position, $insert)
{
    if (is_int($position)) {
        array_splice($array, $position, 0, $insert);
    } else {
        $pos   = array_search($position, array_keys($array));
        $array = array_merge(
            array_slice($array, 0, $pos),
            $insert,
            array_slice($array, $pos)
        );
    }
}

if (!function_exists('amount_after_platform_fee')) {
	function amount_after_platform_fee($amount)  {
		return percent_amount($amount, (g('db.admin.service_fee') ? g('db.admin.service_fee') : 1)) + $amount;
	}
}

if (!function_exists('milestone_due_payment')) {
	function milestone_due_payment($amount)  {
		return $amount - percent_amount($amount, (g('db.admin.service_fee') ? g('db.admin.service_fee') : 1));
	}
}

if (!function_exists('clean')) {
    function clean($string) {
       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
       $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    
       return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }
}

function cartFirstContent($cartContents) {
	if($cartContents) {
		return $cartContents[array_key_first($cartContents)];
	}
	return NULL;
}

function cartReferenceType($cartContents) {
	$cartFirstContent = cartFirstContent($cartContents);
	return $cartFirstContent ? $cartFirstContent['options']['type'] : '';
}

// based on original work from the PHP Laravel framework
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('cleanString')) {
    function cleanString($input) {
        // Step 1: Remove all special characters except letters and digits
        $cleaned = preg_replace('/[^A-Za-z0-9. ]/', '', $input);
        
        // Step 2: Replace multiple spaces with a single space
        $cleaned = preg_replace('/\s+/', '_', $cleaned);
        
        // Step 3: Trim leading and trailing spaces
        $cleaned = trim($cleaned);
        
        return $cleaned;
    }
}

if (!function_exists('curlRequest')) {
	function curlRequest(string $url, array $headers, array $post_fields = array(), bool $is_post = FALSE, bool $is_custom_request = FALSE, string $custom_request_type = '', $build_post_query = FALSE, $user_pwd = ''): ?string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($user_pwd) {
            curl_setopt($ch, CURLOPT_USERPWD, $user_pwd);
        }
        if ($is_custom_request) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $custom_request_type);
        }
        if (!empty($post_fields)) {
            if ($build_post_query) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
            }
        }
        if ($is_post) {
            curl_setopt($ch, CURLOPT_POST, $is_post);
        }
        $response = curl_exec($ch);
        $err = curl_error($ch);

        //
        log_message('error', 'URL: ' . $url . ' - last_http_status: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE));

        curl_close($ch);

        if ($err) {
            log_message('error', "cURL Error #:" . $err);
            return NULL;
        } else {
            return $response;
        }
    }
}