<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Path Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
 */

// ------------------------------------------------------------------------

/**

 */
if ( ! function_exists('get_tournament_picture'))
{
	function get_tournament_picture()
	{
		return './global/uploads/tournament/pictures/';
	}
}


if ( ! function_exists('get_page_image_path'))
{
	function get_page_image_path()
	{
		return './global/uploads/pages/images/';
	}
}


if ( ! function_exists('get_news_image_path'))
{
	function get_news_image_path()
	{
		return './global/uploads/news/images/';
	}
}

if ( ! function_exists('get_video_gallery_path'))
{
	function get_video_gallery_path()
	{
		return './global/uploads/videos/files/';
	}
}

if ( ! function_exists('get_sponsor_image_path'))
{
	function get_sponsor_image_path()
	{
		return './global/uploads/sponsors/images/';
	}
}


if ( ! function_exists('get_press_image_path'))
{
	function get_press_image_path()
	{
		return './global/uploads/press/images/';
	}
}

if ( ! function_exists('get_game_image_path'))
{
	function get_game_image_path()
	{
		return './global/uploads/games/images/';
	}
}

if ( ! function_exists('get_emblem_image_path'))
{
	function get_emblem_image_path()
	{
		return './global/uploads/emblem/images/';
	}
}

/* End of file path_helper.php */
/* Location: ./system/helpers/path_helper.php */