<?
global $config;
$form = new Tkd_form_helper($class_name);
$form->title = ucfirst(str_replace("_", " ", (isset($table)?$table:'')));

if(isset($formWrapper['action']))
	$form->formWrapper['action'] = $formWrapper['action'] ;

if(isset($extra_content))
	$form->set_param('extra_content',$extra_content);

$form->set_param('form_fields',$form_fields);
$form->set_param('form_data',$form_data);
$form->prepare_form();
$form->render_form()
?>