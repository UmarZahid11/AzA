<?
global $config;
?>
<html>

<head>
</head>

<body>
	<p>Hello <?= strtoupper($form_input['signup_firstname']) ?>,&nbsp;</p>

	<h2>Welcome to <?= g('site_name') ?>,</h2>
	<p> Thank you for registering with <?= g('site_name') ?>. Your account has been created successfully</p>


	<p>Thank you,</p>

	<p><?= g('site_name') ?></p>

</body>

</html>