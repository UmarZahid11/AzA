<?php
	global $config;
	$CI = &get_instance();
	$actions = (isset($CI->form_params['action'])) ? $CI->form_params['action'] : array();
	if(empty($actions)) {
		$actions = isset($CI->dt_params['action']) ? $CI->dt_params['action'] : array();
	}
?>

<form class="cmxform form-horizontal tasi-form" id="<?= $form_obj->id ?>" method="<?= $form_obj->formWrapper['method'] ?>" action="<?= $form_obj->formWrapper['action'] ?>" enctype="<?= $form_obj->formWrapper['enctype'] ?>">
	<div class="form-body">

		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			You have some form errors. Please check below.
		</div>
		<div class="alert alert-success display-hide">
			<button class="close" data-close="alert"></button>
			Your form validation is successful!
		</div>

		<?= $form_obj->form_fields_html ?>
		<?= $form_obj->extra_content; ?>
	</div>

	<div class="form-actions">
		<div class="row">
			<div class="col-md-offset-3 col-md-9">
				<? if (!isset($actions['hide_save'])) { ?>
					<button type="submit" name="submit" value="Save" class="btn green">Save</button>
				<? } ?>
				<? if (!isset($actions['hide_save_edit'])) { ?>
					<button type="submit" name="submit" value="SaveNEdit" class="btn green">Save and Continue</button>
				<? } ?>
				<? if (!isset($actions['hide_save_new'])) { ?>
					<button type="submit" name="submit" value="SaveNNew" class="btn green">Save and Add new</button>
				<? } ?>
			</div>
		</div>
	</div>
</form>

<?
/*	 validations:
*    required – Makes the element required.
*    remote – Requests a resource to check the element for validity.
*    minlength – Makes the element require a given minimum length.
*    maxlength – Makes the element require a given maxmimum length.
*    rangelength – Makes the element require a given value range.
*    min – Makes the element require a given minimum.
*    max – Makes the element require a given maximum.
*    range – Makes the element require a given value range.
*    email – Makes the element require a valid email
*    url – Makes the element require a valid url
*    date – Makes the element require a date.
*    dateISO – Makes the element require an ISO date.
*    number – Makes the element require a decimal number.
*    digits – Makes the element require digits only.
*    creditcard – Makes the element require a credit card number.
*    equalTo – Requires the element to be the same as another one
*	 compare_fields - 'js_rules'   => array('compare_fields' => array("#job_position-start_date","greater","Start Date")),
*	 is_dividend - 'js_rules'   => array('compare_fields' => array("#job_position-total_groups","","Max Team")),
*/
//Form validation
foreach ($form_obj->js_validation as $form => $validation_string) {
?>
	<script>
		$(document).ready(function() {
			$("#<?= $form_obj->id ?>").validate({
				rules: { <?= rtrim($validation_string, ",") ?> },
				errorElement: 'span',
				errorClass: 'has-error help-block',
				highlight: function(element, errorClass, validClass) {
					$(element).closest(".form-group").addClass("has-error");
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).closest(".form-group").removeClass("has-error");
				},
				invalidHandler: function(event, validator) {
					// 'this' refers to the form AdminToastr
					var errors = validator.numberOfInvalids();
					console.log(errors);

					if (errors) {
						var message = 'Failed to validate form. Total of ' + errors + ' invalid fields found.';
						AdminToastr.error(message, "Form Submission Failed");
					}
				}
			});
		});
	</script>
<?
}
?>