<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Links Generator. 
 *
 * A helper to create Links... Getting too messed up
 * We will use this STATICALLY...
 * @copyright	Copyright (c) 2017
 * @since		Version 1.0
 * @nature		STATIC
 */

Class Links {

	// Dont let create objects...

	public function __call($name, $arguments)
	{
		// Nothing ATM
	}

	public static function img($path,$img,$is_thumb = false)
	{
		$thumb = $is_thumb ? "thumb/" : "" ;
		return l( $path . $thumb . $img ) ; 
	}

	public static function no_img()
	{
		$slug =  urlencode($slug) ;
		return g('site_global_images_root') . 'no-image-thumb.png' ;
	}

	public static function category_detail($slug)
	{
		$slug =  urlencode($slug) ;
		return sprintf(g('_urls.category_detail') , $slug);
	}

	public static function product_detail($slug)
	{
		$slug =  urlencode($slug) ;
		return sprintf(g('_urls.product_detail') , $slug);
	}

	public static function subcat($slug='')
	{
		$slug =  urlencode($slug) ;
		return l("filter/".$slug) ;
	}

	public static function bread_crumb($crumb='')
	{
		
		if( strpos($crumb , g('base_url')) ===  false )
			$crumb = l( $crumb ) ;

		return $crumb ;
	}

}

// l is basic link creating function...
if( ! function_exists( 'l' ) )
{
	function l($uri = "")
	{
		return g('base_url')	. $uri ;
	}
}

// l is basic ADMIN link creating function...
if( ! function_exists( 'la' ) )
{
	function la($uri)
	{
		return g('admin_base_url')	. $uri ;
	}
}

// i is basic image_link creating function...
if( ! function_exists( 'i' ) )
{
	function i($uri)
	{
		return g('images_root')	. $uri ;
	}
}
/* file of file path_helper.php */
/* Location: ./system/helpers/path_helper.php */
