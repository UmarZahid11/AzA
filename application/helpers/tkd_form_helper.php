<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 *
 * A form builder class help create Forms from Standard TKD Models...
 *
 * @package		TKD Form Helper
 *
 * @since		Version 2.0 Moved To Class... from view.
 */

// ------------------------------------------------------------------------
/**
 * Tkd_form_helper
 */
class Tkd_form_helper
{
	var $formWrapper =  array(
		"class" => "cmxform form-horizontal tasi-form",
		"method" => "POST",
		"action" => "",
		"enctype" => "multipart/form-data",
		"wrap_class" => "col-md-4",
	);

	var $js_validation = array();
	var $form_fields = array();
	var $extra_content = ""; // Stuff to prepend inside form
	var $form_data = array();
	var $titles = array();
	var $_CI = ""; //CI object
	var $form_fields_html = ""; //HTML for form fields

	public function __construct($table)
	{
		$this->table = $table;
		$this->id = $this->table . "-form-id";
		$this->form_fields_html = "";
		$this->_CI = &get_instance();
	}

	public function clear_obj()
	{
		$this->js_validation = array();
		$this->form_fields = array();
		$this->form_data = array();
		$this->titles = array();
		$this->form_fields_html = "";
	}

	public function set_param($key, $val)
	{
		$this->{$key} = $val;
	}

	public function prepare_form()
	{
		if (is_array($this->form_fields)) {
			foreach ($this->form_fields as $table => $table_fields) {

				$this->titles[$table] = ucfirst(str_replace("_", " ", $table));
				// Form Fields
				if (is_array($table_fields)) {
					foreach ($table_fields as $field_key => $field) {
						// Fields
						$prepared_fields = $field;
						$prepared_fields['generate'] = $this->generate_field($field_key, $field);
						$this->render_field($prepared_fields);
					}
				}
			}
		}
	}

	public function gen_update_timestamp($p = array())
	{
		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';
		return '<input class=" form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '" type="hidden" value="' . date("Y-m-d H:i:s") . '"/>';
	}

	public function gen_text($p = '')
	{
		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';
		return '<input class=" form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '" type="' . $p['field']['type'] . '" value="' . $p['field_data'] . '"/>';
	}

	public function gen_number($p = '')
	{
		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';
		return '<input class=" form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '" type="' . $p['field']['type'] . '" value="' . (isset($p['field_data']) && $p['field_data'] ? $p['field_data'] : (isset($p['field']['value']) ? $p['field']['value'] : '0')) . '" min="' . (isset($p['field']['min']) ? $p['field']['min'] : 0) . '" step=".01" />';
	}

	public function gen_url($p = '')
	{
		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';
		return '<input class=" form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '" type="' . $p['field']['type'] . '" value="' . $p['field_data'] . '"/>';
	}

	public function gen_date2($p = '')
	{
		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';
		return '<input class=" form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '" type="date" value="' . $p['field_data'] . '"/>';
	}

	public function gen_readonly($p = '')
	{
		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';
		return '<input class=" form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '" type="' . $p['field']['type'] . '" value="' . $p['field_data'] . '" readonly/>';
	}

	public function gen_textarea_readonly($p = '')
	{
		return '<textarea readonly class="form-control ' . (isset($p['field']['attributes']['class']) ? $p['field']['attributes']['class'] : '') . '" id="' . $p['id'] . '" ' . $p['additional'] . ' rows="12" name="' . $p['field_name'] . '">' . $p['field_data'] . '</textarea>';
	}

	public function gen_password($p = '')
	{
		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';
		return '<input class=" form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '" type="' . $p['field']['type'] . '" value="' . $p['field_data'] . '"/>';
	}

	public function gen_hidden($p = '')
	{
		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';
		return '<input class=" form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '" type="' . $p['field']['type'] . '" value="' . $p['field_data'] . '"/>';
	}

	public function gen_label($p = array())
	{
		return '<label class="control-label text-left col-md-12">' . $p['field_data'] . '</label>';
	}

	public function gen_label_custom($p = array())
	{
		return $p['field']['field_data'][$p['field_data']];
		// return $p['field_data'];
	}

	public function gen_colorpicker($p = array())
	{
		$this->_CI->register_plugins(array("bootstrap-colorpicker"));
		$p['attributes'] = $p['field']['attributes'];
		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';
		return '<input class="colorpicker-default form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '" type="text" value="' . $p['field_data'] . '"/>';
	}

	// public function gen_checkbox($p=array())
	// {
	// 	$checked =  $p['field_data'] == 1 ? "checked" : $p['field']['default'] == 1 ? "checked" : "" ;
	//        $class = (array_filled($p['attributes']>0))?$p['attributes']['class'] : '';
	// 	return '<input class=" form-control '.$class .'" id="'.$p['id'].'" '.$p['additional'].' name="' . $p['field_name'] .'" type="'.$p['field']['type'].'" />';

	// }

	public function gen_checkbox($p = array())
	{
		$checked = ($p['field_data'] == 1 ? "checked" : ($p['field']['default'] == 1 ? "checked" : ""));

		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';

		return '<input class=" form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '" type="' . $p['field']['type'] . '" />';
	}

	public function gen_editor($p = array())
	{
		$class = (isset($p['field']['attributes']['class'])) ? $p['field']['attributes']['class'] : '';
		$this->_CI->register_plugins(array("ckeditor"));
		return '<textarea class="ckeditor  form-control ' . $class . '" id="' . $p['id'] . '" ' . $p['additional'] . ' name="' . $p['field_name'] . '">' . $p['field_data'] . '</textarea>';
	}

	public function gen_textarea($p = array())
	{
		return '<textarea class="form-control ' . (isset($p['field']['attributes']['class']) ? $p['field']['attributes']['class'] : '') . '" id="' . $p['id'] . '" ' . $p['additional'] . ' rows="12" name="' . $p['field_name'] . '">' . $p['field_data'] . '</textarea>';
	}

	public function gen_dropdown($p = '')
	{
		$class_field = (isset($field_class)) ? $field_class : '';
		$class = (isset($p['field']['attributes']['class'])) ? $p['field']['attributes']['class'] : '';
		$field_html = "<select data-selected=\"{$p['field_data']}\" name=\"{$p['field_name']}\" class=\"form-control select2me {$class_field} {$class}\" {$p['additional']} id=\"$p[id]\" >";
		$list_data = (isset($p['field']['list_data'])) ? $p['field']['list_data'] : array();
		if (!array_filled($list_data)) {
			$list_data_key = (isset($p['field']['list_data_key'])) ? $p['field']['list_data_key'] : $p['field_key'];
			$list_data = isset($this->_CI->_list_data[$list_data_key]) ? $this->_CI->_list_data[$list_data_key] : array();
		}
		$option_list = "";
		// Populate OPtions list
		if (array_filled($list_data)) {
			$option_list = generate_options_html($list_data, $p['field_data']);
		}

		$field_html .= $option_list . "</select>";
		return $field_html;
	}

	public function gen_dropdown_readonly($p = '')
	{
		$class_field = (isset($field_class)) ? $field_class : '';
		$class = (isset($p['field']['attributes']['class'])) ? $p['field']['attributes']['class'] : '';
		$field_html = "<select data-selected=\"{$p['field_data']}\" name=\"{$p['field_name']}\" class=\"form-control select2me {$class_field} {$class}\" {$p['additional']} id=\"$p[id]\" readonly >";
		$list_data = (isset($p['field']['list_data'])) ? $p['field']['list_data'] : array();
		if (!array_filled($list_data)) {
			$list_data_key = (isset($p['field']['list_data_key'])) ? $p['field']['list_data_key'] : $p['field_key'];
			$list_data = isset($this->_CI->_list_data[$list_data_key]) ? $this->_CI->_list_data[$list_data_key] : array();
		}
		$option_list = "";
		// Populate OPtions list
		if (array_filled($list_data)) {
			$option_list = generate_options_html($list_data, $p['field_data']);
		}

		$field_html .= $option_list . "</select>";
		return $field_html;
	}

	public function gen_select($p = '')
	{
		$class_field = (isset($field_class)) ? $field_class : '';
		$class = (isset($p['field']['attributes']['class'])) ? $p['field']['attributes']['class'] : '';
		$field_html = "<select name=\"{$p['field_name']}\" class=\"form-control {$class_field} {$class}\" {$p['additional']} id=\"$p[id]\" >";
		$list_data = (isset($p['field']['list_data'])) ? $p['field']['list_data'] : array();
		$option_list = "";
		// Populate OPtions list
		if (array_filled($list_data)) {
			$option_list = generate_options_html($list_data, $p['field_data']);
		}

		$field_html .= $option_list . "</select>";
		return $field_html;
	}

	public function gen_select_disabled($p = '')
	{
		$class_field = (isset($field_class)) ? $field_class : '';
		$class = (isset($p['field']['attributes']['class'])) ? $p['field']['attributes']['class'] : '';
		$field_html = "<select disabled name=\"{$p['field_name']}\" class=\"form-control {$class_field} {$class}\" {$p['additional']} id=\"$p[id]\" >";
		$list_data = (isset($p['field']['list_data'])) ? $p['field']['list_data'] : array();
		$option_list = "";
		// Populate OPtions list
		if (array_filled($list_data)) {
			$option_list = generate_options_html($list_data, $p['field_data']);
		}

		$field_html .= $option_list . "</select>";
		return $field_html;
	}

	public function gen_select_readonly($p = '')
	{
		$class_field = (isset($field_class)) ? $field_class : '';
		$class = (isset($p['field']['attributes']['class'])) ? $p['field']['attributes']['class'] : '';
		$field_html = "<select readonly name=\"{$p['field_name']}\" class=\"form-control {$class_field} {$class}\" {$p['additional']} id=\"$p[id]\" >";
		$list_data = (isset($p['field']['list_data'])) ? $p['field']['list_data'] : array();
		$option_list = "";
		// Populate OPtions list
		if (array_filled($list_data)) {
			$option_list = generate_options_html($list_data, $p['field_data']);
		}

		$field_html .= $option_list . "</select>";
		return $field_html;
	}


	public function gen_multiselect($p = array())
	{
		$class_field = (isset($field_class)) ? $field_class : '';
		$class = (isset($p['field']['attributes']['class'])) ? $p['field']['attributes']['class'] : '';

		$this->_CI->register_plugins(array("jquery-multi-select"));
		$field_html = "<select multiple  name=\"{$p['field_name']}[]\" class=\"form-control dd-multiselect {$class_field} {$class}\" id=\"$p[id]\" >"; // data-selected=\"{$p['field_data']}\"
		$list_data = $p['field']['list_data'];
		if (!array_filled($list_data)) {
			$list_data_key = $p['field']['list_data_key'] ? $p['field']['list_data_key'] : $p['field_key'];
			$list_data = $this->_CI->_list_data[$list_data_key];
		}

		if (@unserialize($p['field_data']) !== FALSE) {
			$p['field_data'] = unserialize($p['field_data']);
		}

		$option_list = "";
		// Populate OPtions list
		if (array_filled($list_data)) {
			$option_list = generate_options_html($list_data, $p['field_data'], false);
		}

		$field_html .= $option_list . "</select>";

		return $field_html;
	}

	public function gen_switch($p = array())
	{
		$list_data = (isset($p['field']['list_data'])) ? $p['field']['list_data'] : array();
		$txtActive = ((array_filled($list_data)) && ($list_data[STATUS_ACTIVE])) ? $list_data[STATUS_ACTIVE] : "&nbsp;Yes&nbsp;";
		$txtInActive = ((array_filled($list_data)) && ($list_data[STATUS_INACTIVE])) ? $list_data[STATUS_INACTIVE] : "&nbsp;No&nbsp;";
		$this->_CI->register_plugins(array("bootstrap-switch"));
		//Check for fields from DB
		if (strlen($p['field_data'])) {
			$checked =  ($p['field_data']) ? "checked" : "";
		} else {
			$checked =  (isset($_GET['id']) && !$_GET['id'] && $p['field']['default']) ? "checked" : "";
		}
		$class = (array_filled($p['attributes'] > 0)) ? $p['attributes']['class'] : '';
		return '<input ' . $checked . ' name="' . $p['field_name'] . '" type="checkbox" class="make-switch ' . $class . '" ' . $p['additional'] . 'data-on-text="' . $txtActive . '" value="1" data-off-text="' . $txtInActive . '"/>';
	}

	public function gen_fileupload($p = array())
	{
		global $config;
		$this->_CI->register_plugins(array("bootstrap-fileupload"));
		if ($p['field_data'])
			$image_path = $config['base_url'] . $p['form_data'][$p['table']][$p['field']['name_path']] . $p['field_data'];
		else
			$image_path = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image";
		ob_start();
?>
		<div class="">
			<div class="uploadfile uploadfile-new" data-provides="uploadfile">
				<div class="uploadfile-new thumbnail" style="max-width: 200px; max-height: 150px;">
					<img src="<?= $image_path ?>" alt="" />
				</div>
				<div class="uploadfile-preview uploadfile-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
					<?= count($p['form_data']) ? $p['field_data'] : "" ?>
				</div>
				<div>
					<span class="btn btn-file blue">
						<span class="uploadfile-new"><i class="fa fa-paper-clip"></i> Select image</span>
						<span class="uploadfile-exists"><i class="fa fa-undo"></i> Change</span>
						<!--<input type="file" class="default <?/*=(isset($field_class))?$field_class:''*/ ?>" name="<?/*=$p['field_name']*/ ?>" />-->
					</span>
					<a href="#" class="btn btn-danger uploadfile-exists" data-dismiss="uploadfile"><i class="fa fa-trash"></i> Remove</a>
					<span class="file-upload-custom-input">
						<input type="file" class="default <?= (isset($field_class)) ? $field_class : '' ?>" name="<?= $p['field_name'] ?>" />
					</span>

				</div>
				<?php
				if (isset($p['attributes']) && !empty($p['attributes'])) {
					foreach ($p['attributes'] as $key => $value) : ?>
						<span style="font-size: 10px;color:red;">
							<?= humanize($key) . ': ' . $value ?>
						</span>
						<br>
					<? endforeach;
					?>
				<?php
				}
				?>
			</div>
		</div>
	<?

		return ob_get_clean();
	}

	// Audio File upload
	public function gen_audiofileupload($p = array())
	{
		global $config;
		$this->_CI->register_plugins(array("bootstrap-fileupload"));
		if ($p['field_data'])
			$audio_path = $config['base_url'] . $p['form_data'][$p['table']][$p['field']['name_path']] . $p['field_data'];

		else
			$audio_path = "";

		ob_start();
	?>
		<div class="">
			<div class="uploadfile uploadfile-new" data-provides="uploadfile">
				<div class="uploadfile-new thumbnail" style="max-height: 150px;">
					<audio controls>
						<source src="<?= $audio_path ?>" type="audio/mp3">
						Your browser does not support the audio element.
					</audio>
				</div>
				<div class="uploadfile-preview uploadfile-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
					<?= count($p['form_data']) ? $p['field_data'] : "" ?>
				</div>
				<div>
					<span class="btn btn-file blue">
						<span class="uploadfile-new"><i class="fa fa-paper-clip"></i> Select</span>
						<span class="uploadfile-exists"><i class="fa fa-undo"></i> Change</span>
						<!--<input type="file" class="default <?/*=(isset($field_class))?$field_class:''*/ ?>" name="<?/*=$p['field_name']*/ ?>" />-->
					</span>
					<a href="#" class="btn btn-danger uploadfile-exists" data-dismiss="uploadfile"><i class="fa fa-trash"></i> Remove</a>
					<span class="file-upload-custom-input">
						<input type="file" class="default <?= (isset($field_class)) ? $field_class : '' ?>" name="<?= $p['field_name'] ?>" />
					</span>

				</div>
				<?php
				if (isset($p['attributes']) && !empty($p['attributes'])) {
					foreach ($p['attributes'] as $key => $value) : ?>
						<span style="font-size: 10px;color:red;">
							<?= humanize($key) . ': ' . $value ?>
						</span>
						<br>
					<? endforeach;
					?>
				<?php
				}
				?>
			</div>
		</div>
	<?

		return ob_get_clean();
	}

	// General File upload
	public function gen_customfileupload($p = array())
	{
		global $config;
		$this->_CI->register_plugins(array("bootstrap-fileupload"));
		if ($p['field_data'])
			//$image_path = $config['base_url'] . $p['form_data'][$p['table']][ $p['field']['name_path'] ] . $p['field_data'] ;
			$image_path = $p['field_data'];
		else
			$image_path = g('admin_images_root') . "general-icon.png";
		ob_start();
	?>
		<div class="">
			<div class="uploadfile uploadfile-new" data-provides="uploadfile">
				<div class="uploadfile-new thumbnail" style="max-width: 200px; max-height: 150px;">
					<?
					if ($p['field_data']) { ?>
						<label><?= $image_path ?></label>
					<? } else { ?>
						<img src="<?= $image_path ?>" alt="" />
					<? }
					?>
				</div>
				<div class="uploadfile-preview uploadfile-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
					<?= count($p['form_data']) ? $p['field_data'] : "" ?>
				</div>
				<div>
					<span class="btn btn-file blue">
						<span class="uploadfile-new"><i class="fa fa-paper-clip"></i> Select</span>
						<span class="uploadfile-exists"><i class="fa fa-undo"></i> Change</span>
						<!--<input type="file" class="default <?/*=(isset($field_class))?$field_class:''*/ ?>" name="<?/*=$p['field_name']*/ ?>" />-->
					</span>
					<a href="#" class="btn btn-danger uploadfile-exists" data-dismiss="uploadfile"><i class="fa fa-trash"></i> Remove</a>
					<span class="file-upload-custom-input">
						<input type="file" class="default <?= (isset($field_class)) ? $field_class : '' ?>" name="<?= $p['field_name'] ?>" />
					</span>

				</div>
				<?php
				if (isset($p['attributes']) && !empty($p['attributes'])) {
					foreach ($p['attributes'] as $key => $value) : ?>
						<span style="font-size: 10px;color:red;">
							<?= humanize($key) . ': ' . $value ?>
						</span>
						<br>
					<? endforeach;
					?>
				<?php
				}
				?>
			</div>
		</div>
	<?

		return ob_get_clean();
	}

	// Video upload
	public function gen_videoupload($p = array())
	{
		global $config;
		$this->_CI->register_plugins(array("bootstrap-fileupload"));
		if ($p['field_data'])
			//$image_path = $config['base_url'] . $p['form_data'][$p['table']][ $p['field']['name_path'] ] . $p['field_data'] ;
			$image_path = $config['base_url'] . $p['form_data'][$p['table']][$p['field']['name_path']] . $p['field_data'];
		else
			$image_path = g('admin_images_root') . "video-icon.png";
		ob_start();
	?>
		<div class="">
			<div class="uploadfile uploadfile-new" data-provides="uploadfile">
				<?
				if ($p['field_data']) { ?>
					<div class="uploadfile-new thumbnail" style="min-width:150px;max-width: 350px; max-height: 350px; ">
						<video width="250" height="250" controls>
							<source src="<?= $image_path ?>" type="video/mp4">
							<source src="<?= $image_path ?>" type="video/mov">
							Your browser does not support the video tag.
						</video>
					</div>
				<? } else { ?>
					<div class="uploadfile-new thumbnail" style="min-width:150px;max-width: 150px; max-height: 150px; ">
						<img src="<?= $image_path ?>" alt="" />
					</div>
				<? }
				?>

				<div class="uploadfile-preview uploadfile-exists thumbnail" style="min-width:150px;max-width: 350px; max-height: 267px; line-height: 20px;">
					<?= count($p['form_data']) ? $p['field_data'] : "" ?>
				</div>
				<div>
					<span class="btn btn-file blue">
						<span class="uploadfile-new"><i class="fa fa-paper-clip"></i> Select</span>
						<span class="uploadfile-exists"><i class="fa fa-undo"></i> Change</span>
						<!--<input type="file" class="default <?/*=(isset($field_class))?$field_class:''*/ ?>" name="<?/*=$p['field_name']*/ ?>" />-->
					</span>
					<a href="#" class="btn btn-danger uploadfile-exists" data-dismiss="uploadfile"><i class="fa fa-trash"></i> Remove</a>
					<span class="file-upload-custom-input">
						<input type="file" class="default <?= (isset($field_class)) ? $field_class : '' ?>" name="<?= $p['field_name'] ?>" />
					</span>

				</div>
				<?php
				if (isset($p['attributes']) && !empty($p['attributes'])) {
					foreach ($p['attributes'] as $key => $value) : ?>
						<span style="font-size: 10px;color:red;">
							<?= humanize($key) . ': ' . $value ?>
						</span>
						<br>
					<? endforeach;
					?>
				<?php
				}
				?>
			</div>
		</div>
<?

		return ob_get_clean();
	}

	public function gen_datetimelocal($p = array())
	{
		$p['field_data'] = ($p['field_data'] == "0000-00-00" || !$p['field_data']) ?  date("Y-m-d") : $p['field_data'];
		return '
              <input type="datetime-local" value="' . $p['field_data'] . '" id="' . $p['id'] . '" size="16" name="' . $p['field_name'] . '" class="form-control form-control-inline input-medium default-date-picker">
			';
	}
	
    public function gen_date($p = array())
	{
		$this->_CI->register_plugins(array("bootstrap-datepicker"));
		$p['field_data'] = ($p['field_data'] == "0000-00-00" || !$p['field_data']) ?  date("Y-m-d") : $p['field_data'];
		return '
              <div class="col-md-3 col-xs-11">
                  <input type="text" value="' . $p['field_data'] . '" id="' . $p['id'] . '" size="16" name="' . $p['field_name'] . '" class="form-control form-control-inline input-medium default-date-picker">
              </div>
			';
	}

	public function gen_datetime($p = array())
	{
		$this->_CI->register_plugins(array("bootstrap-datetimepicker"));
		// $p['field_data'] = ($p['field_data'] == "0000-00-00" || !$p['field_data']) ?  date("Y-m-d H:i") : $p['field_data'];
		$p['field_data'] = ($p['field_data'] == "0000-00-00" || !$p['field_data']) ? '' : $p['field_data'];
		return '
              <div class="input-group date form_datetime-component">
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-danger date-set"><i class="icon-calendar"></i></button>
                    </span>
					<input size="16" type="text" value="' . $p['field_data'] . '" id="' . $p['id'] . '" name="' . $p['field_name'] . '" btn class="form-control default-datetime-picker ' . (isset($p['field']['attributes']['class']) ? $p['field']['attributes']['class'] : '') . '">
              </div>
		';
	}

	public function gen_select2($p = '')
	{
		$this->_CI->register_plugins(array("select2"));
		$id = "select2_cus_" . $p['field_key'];
		$field_html = '<input type="hidden" name="' . $p['field_name'] . '" class="bigdrop form-control" id="' . $id . '" style="width:' . (isset($p['field']['dt_attributes']['width']) ? $p['field']['dt_attributes']['width'] : '100%') . '"/>';

		$field_html .= '<script>

        $(document).ready(function () {
            $("#' . $id . '").select2({
                placeholder: "' . $p['field']['label'] . '",
                minimumInputLength: 3,
                ajax: {
                    url: "' . $p['field']['dt_attributes']['url'] . '",
                    dataType: "json",
                    quietMillis: 250,
                    data: function (term, page) { // page is the one-based page number tracked by Select2
                        return {
                            q: term, //search term
                            page: page // page number
                        };
                    },
                    results: function (data, page) {
						console.log(data.total_count)
                        var more = (page * 30) < data.total_count; // whether or not there are more results available

                        // notice we return the value of more so Select2 knows if more results can be loaded
                        return {results: data.items, more: more};
                    }
                },
                formatResult: repoFormatResult, // omitted for brevity, see the source of this page
                formatSelection: repoFormatSelection, // omitted for brevity, see the source of this page
                dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                escapeMarkup: function (m) {
                    return m;
                } // we do not want to escape markup since we are displaying html in results
            });
        });

        </script>';

		return $field_html;
	}


	public function generate_field($field_key, $field)
	{
		global $config;

		$p = array();
		$p['field'] = $field;
		$p['field_key'] = $field_key;
		$p['field_html'] = "";
		$p['wrap_class'] = $this->formWrapper['wrap_class'];
		$p['field_name'] = $field['table'] . "[{$field['name']}]";
		$p['form_data'] = ($_POST) ? $_POST : $this->form_data;
		if (!$this->form_data && !$_POST && (isset($field['default'])) ? $field['default'] : '')
			$p['form_data'][$field['table']][$field['name']] = $field['default'];
		$p['table'] = $field['table'];

		$return = array();

		if ((isset($p['field_name'])) && (isset($p['field']['js_rules']))) {
			if (is_array($field['js_rules']))
				$this->js_validation[$this->id] .= "'" . $p['field_name'] . "' : " . json_encode($field['js_rules']) . ",";
			else
				$this->js_validation[$this->id] = "'" . $p['field_name'] . "' : '" . $field['js_rules'] . "',";
		}

		if (isset($p['form_data']['relation_data'][$field_key][$field['name']])) {
			$p['field_data'] = $p['form_data']['relation_data'][$field_key][$field['name']];
		} else
			$p['field_data'] = (isset($p['form_data'][$p['table']][$field_key])) ? $p['form_data'][$p['table']][$field_key] : '';

		// if($p['field']['prepare'])
		// $p['field_data'] = prepare_value($p['field_data'] , $p['field_data']['prepare']) ;

		if (strpos($p['field']['rules'], "htmlentities"))
			$p['field_data'] = html_entity_decode($p['field_data']);
		$p['additional'] = (isset($p['field']['attributes']['additional']) ? $p['field']['attributes']['additional'] : '');
		$p['attributes'] = (isset($p['field']['attributes'])) ? $p['field']['attributes'] : '';
		$p['id'] = (isset($p['attributes']['id'])) ? $p['attributes']['id'] : $p['field_name'];
		$p['id'] = str_replace("]", "", str_replace("[", "-", $p['id']));

		$return['id'] = str_replace(array('[', ']'), array("-"), $p['id']);
		switch ($p['field']['type']) {
				// Handle low level customization
			case ("editor"):
			case ("textarea"):
				$p['wrap_class'] = "col-md-9";
				break;
			case ("update_timestamp"):
			case ("hidden"):
			case ("text"):
			case ("colorpicker"):
			case ("checkbox"):
			case ("switch"):
			case ("multiselect"):
			case ("dropdown"):
			case ("fileupload"):
			case ("date"):
			case ("datetime"):
			case ("label"):
			case ("label_custom"):
				// Do nothing as of now..
				break;

			case ("none"):
				return "";
				break;

			default:
				$field_html = "Unknow field type";
				break;
		}
		$function = "gen_" . $p['field']['type'];
		$field_html = $this->$function($p);

		$return['wrap_class'] = $p['wrap_class'];
		$return['field_html'] = $field_html;
		$return['field_label'] = ucfirst(str_replace("_", " ", $p['field']['label']));
		$return['field_error'] = form_error($p['field_name']);

		if (strpos($p['field']['rules'], 'required') !== false)
			$return['field_label'] .= '<span class="required">* </span>';

		if ($p['field']['type'] != "hidden") {
			$return['field_error'] = form_error($p['field_name']) ? "has-error" : "";
		}

		return $return;
	}

	public function render_field($field = array())
	{
		if (isset($field['generate']) && array_filled($field['generate']))
			extract($field['generate']);

		$field_error = (isset($field['generate']['field_error'])) ? $field['generate']['field_error'] : '';
		$field_class = (isset($field['generate']['field_class'])) ? $field['generate']['field_class'] : '';
		$field_label = (isset($field['generate']['field_label'])) ? $field['generate']['field_label'] : '';
		$wrap_class  = (isset($field['generate']['wrap_class'])) ? $field['generate']['wrap_class'] : '';
		$field_html  = (isset($field['generate']['field_html'])) ? $field['generate']['field_html'] : '';

		$help_block = "";

		if (isset($field['help']))
			$help_block = '<span class="help-block">' . $field['help'] . ' </span>';

		if ((isset($field['type'])) && ($field['type'] == "hidden"))
			$this->form_fields_html .= $field['generate']['field_html'];
		elseif ((isset($field['type'])) && ($field['type'] == "none"))
			$this->form_fields_html .= "";
		else
			$this->form_fields_html .= '<div class="form-group ' . $field_error . '">
										<label class="control-label col-md-2 ' . $field_class . '">
												' . str_replace("'", '', $field_label) . '
										</label>
							          	<div class="' . $wrap_class . '">
							            	' . $field_html . '
							            	' . form_error("field_class") . '
							            	' . $help_block . '
							            </div>
							        </div>';
	}

	public function render_form($field = array())
	{
		$data['form_obj'] = $this;
		$this->_CI->load->view("_layout/form_widget",  $data);
		// Clear Obj for other form use...
		$this->clear_obj();
	}
}

