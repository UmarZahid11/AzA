<? global $config; ?>

<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->

<!--[if !IE]><!-->

<html lang="en">

<!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <meta content="width=device-width, initial-scale=1.0" name="viewport" />

  <meta http-equiv="Content-type" content="text/html; charset=utf-8">

  <link rel="shortcut icon" href="<?= Links::img($logo[0]['logo_image_path'], $logo[0]['logo_favicon']) ?>">



  <title><?= $title; ?></title>

  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />

  <?

  //Put Meta Data

  foreach ($meta_data as $meta_name => $meta_val)

    echo '<meta name="' . $meta_name . '" content="' . $meta_val . '">';

  ?>



  <meta charset="<?= (isset($meta_charset)) ? $meta_charset : "UTF-8" ?>">



  <link href="<?= $config['plugins_root'] ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

  <link href="<?= $config['plugins_root'] ?>simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />

  <link href="<?= $config['plugins_root'] ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <link href="<?= $config['plugins_root'] ?>uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />



  <? foreach ($css_files as $file) { ?>

    <link rel="stylesheet" href="<?= $config['admin_css_root'] . $file; ?>" type="text/css" />

  <? } ?>



</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="login example2">

  <!-- BEGIN SIDEBAR TOGGLER BUTTON -->

  <!-- END SIDEBAR TOGGLER BUTTON -->

  <!-- BEGIN LOGO -->

  <!-- END LOGO -->

  <?= $content_block; ?>



  <!-- END LOGIN -->

  <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

  <!-- BEGIN CORE PLUGINS -->

  <!--[if lt IE 9]>

<script src="../../assets/global/plugins/respond.min.js"></script>

<script src="../../assets/global/plugins/excanvas.min.js"></script>

<![endif]-->

  <script src="<?= $config['plugins_root'] ?>jquery.min.js" type="text/javascript"></script>

  <script src="<?= $config['plugins_root'] ?>jquery-migrate.min.js" type="text/javascript"></script>

  <script src="<?= $config['plugins_root'] ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

  <script src="<?= $config['plugins_root'] ?>jquery.blockui.min.js" type="text/javascript"></script>

  <script src="<?= $config['plugins_root'] ?>jquery.cokie.min.js" type="text/javascript"></script>

  <script src="<?= $config['plugins_root'] ?>uniform/jquery.uniform.min.js" type="text/javascript"></script>

  <!-- END CORE PLUGINS -->

  <!-- BEGIN PAGE LEVEL PLUGINS -->

  <script src="<?= $config['plugins_root'] ?>jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>

  <!-- END PAGE LEVEL PLUGINS -->

  <!-- BEGIN PAGE LEVEL SCRIPTS -->

  <!-- js placed at the end of the document so the pages load faster -->

  <? foreach ($js_files as $file) {

  ?>

    <script src="<?= $config['admin_js_root'] . $file; ?>"></script>

  <?

  } ?>

  <!-- END PAGE LEVEL SCRIPTS -->

  <script>
    jQuery(document).ready(function() {

      Metronic.init(); // init metronic core components

      Layout.init(); // init current layout

      Login.init();

      Demo.init();

    });
  </script>

</body>

</html>