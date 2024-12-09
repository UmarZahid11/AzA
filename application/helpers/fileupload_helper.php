<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * FilUploader.
 *
 * A helper to upload file....
 *
 * @package		-
 * @since		Version 1.0
 * @filesource
 */

Class Fileupload_helper {

	private $file_params = array();

	public function __construct($params = array()) {
		$this->file_params = $params;
	}

	public function reset_class() {
		// Do something amazing here
		$this->file_params = array();
	}

	public function do_upload() {
		global $config ;
		$return =  array();
		$params = $this->file_params ;
		$random_suffix = "";

		$filename = $params['name'] . $random_suffix ;

		$fld = $params['field_config'] ;
		$old_data = $params['old_data'] ;

		if($fld['randomize']) {
			$filename = $this->randomize_filename( $filename );
		}

		$destination = $params['destination_path'] .$filename ;

		//chmod($destination_path, 0777);

		if(!is_dir($params['destination_path'])) {
			return array( 'error' => "ERROR: Folder not found:" . $destination_path );
		} elseif(!is_writable($params['destination_path'])) {
			return array( 'error' => "FOLDER $destination_path is not writable. Please modify permissions to upload files" );
		}

		// Start
		if(isset($this->file_params['field_config']['attributes']['allow_ext'])) {
			$file_ext_allow = explode("|", $this->file_params['field_config']['attributes']['allow_ext']);
		} else {
			$file_ext_allow = array('jpeg','jpg','png','pdf','docx','doc','epub','azw','LIT','lit','ODF','MOBI','mobi','fb2','rtf','txt','iBook');
		}

		$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

		if(!in_array($file_ext, $file_ext_allow)) {
			return array( 'error' => "ERROR: not allow to ".$file_ext." file extension." );
		}

		if(isset($this->file_params['field_config']['attributes']['max_size'])) {
		    if ($params['size'] > $this->file_params['field_config']['attributes']['max_size']) {
		        return array( 'error' => "ERROR: attachment exceeds upload size limit." );
		    }
        }

		if(move_uploaded_file( $params['tmp_name'] , $destination)) {
			$return[$fld['name']] = $filename ;
			$return[$fld['name_path']] = $params['destination_path'] ;
			//if(array_filled($fld['thumb']))
			if(isset($fld['thumb']))
			{
				foreach ($fld['thumb'] as $key => $thumb) {
					# code...
					$thumb['upload_path'] = $params['destination_path'] ;
					$thumb['filename'] = $filename;
					$thumb['thumb_no'] = (isset($fld['thumb']) && count($fld['thumb']))>1 ? ($key+1) : "" ;
					$thumb['old_file'] = (isset($old_data[$thumb['name']]))?$old_data[$thumb['name']] : '' ;
					$return[$thumb['name']] = $this->create_thumbnail($thumb) ;
				}
			}

			$old_file_name = (isset($old_data[ $fld['name'] ]))?$old_data[ $fld['name'] ]:'';
			$old_file = $params['destination_path'] . $old_file_name ;

			if($old_file_name != $filename && is_file(( $old_file )))
				unlink($old_file);

		} else {
			$return['error'] = "Failed to upload.";
		}

		$this->reset_class();

		return $return ;
	}

	public function randomize_filename($filename='') {
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$filename = str_replace(".$ext", "", $filename);
		$filename = preg_replace("/([^a-z0-9_-])/", "", $filename) ;
		$filename .= time().rand(10,99).".".$ext;
		if(strlen($filename) > 50)
			$filename = substr($filename, -40);
		return $filename;
	}

	// A tool to create cute little thumbnails
	/*
	@$params contains:
		'upload_path' - Path to upload files. Will create thumb folder if not exists in this path...
		'thumb_config' - Contains configuration like :
		'image_library'
		'source_image'  (source file from whom to create thumb)
		'thumb_marker'
		'new_image' (name of thumb'ed image)
		'maintain_ration' : true by default. And mostly should remain that way.
		'width'
		'height'
	*/
	function create_thumbnail($params = array()) {

		//listing thumbnail
		extract($params);

		if(!is_dir($upload_path."thumb/"))
			mkdir($upload_path."thumb/");

		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = $upload_path . $filename;
		$thumb_config['thumb_marker'] = (isset($thumb_marker))?$thumb_marker:'';
		$new_image = ($thumb_no ? $thumb_no : "") . $filename;
		$thumb_config['new_image'] = $upload_path."thumb/".$new_image;
		$thumb_config['create_thumb'] = TRUE;
		$thumb_config['maintain_ratio'] = TRUE;

		if($max_width)
			$thumb_config['width'] = $max_width;
		if($max_height)
			$thumb_config['height'] = $max_height;

		$_CI = & get_instance();
		$_CI->load->library('image_lib');
		$_CI->image_lib->initialize($thumb_config);
		$_CI->image_lib->resize();
		$_CI->image_lib->clear();

		if(is_file($upload_path."thumb/" . $old_file))
			unlink($upload_path."thumb/" . $old_file);

		return $new_image;

	}
}
/* file of file path_helper.php */
/* Location: ./system/helpers/path_helper.php */