<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha512-MoRNloxbStBcD8z3M/2BmnT+rg4IsMxPkXaGh2zD6LGNNFE80W3onsAhRcMAMrSoyWL9xD7Ert0men7vR8LUZg==" crossorigin="anonymous" /> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" />

<style>
  span.ui-helper-hidden-accessible {
    display: none;
  }

  .ui-menu .ui-menu-item a {
    color: #454545;
    z-index: 9999;
  }

  .ui-menu-item {
    border: 1px solid #ccc;
    padding: 5px 5px;
    list-style-type: none !important;
  }

  .ui-autocomplete {
    width: 25% !important;
    left: 415px !important;
    background: white !important;
  }
</style>

<? global $config;

$model_heads = explode(",", $dt_params['dt_headings']);

?>

<div class="inner-page-header">

  <h1><?= humanize($class_name) ?> <small>Record</small></h1>

</div>



<div class="row">

  <div class="col-md-12">

    <!-- BEGIN VALIDATION STATES-->

    <div class="portlet box green">

      <div class="portlet-title">

        <div class="caption">

          <i class="fa fa-briefcase"></i><?= humanize($class_name) ?>

          <small>Add Details to <?= humanize($class_name) ?></small>

        </div>

      </div>

      <div class="portlet-body form">

        <!-- BEGIN FORM-->

        <? $this->load->view("admin/widget/form_generator"); ?>

        <!-- END FORM-->

      </div>

      <!-- END VALIDATION STATES-->

    </div>

  </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous"></script>

<script>
  $(document).ready(function() {

    Metronic.init(); // init metronic core components

    QuickSidebar.init(); // init quick sidebar

    Demo.init(); // init demo features

    UIAlertDialogApi.init(); //UI Alert API

    <? if (isset($error))
      echo "AdminToastr.error('" . str_replace("\n", "", validation_errors('<div>', '</div></br>')) . "');";
    ?>

    var tagInputEle = $('#job-job_tags')
    tagInputEle.tagsinput();

    // $('#job-job_submission_deadline').attr('min', new Date(Date.now() - (3600 * 1000)).toISOString().split("T")[0]);

  });
</script>


<script>
  $(function() {
    $("#job-job_location").autocomplete({
      source: base_url + 'job/mapbox',
      select: function(event, ui) {
        event.preventDefault();
        $("#job-job_location").val(ui.item.id);
      }
    });
  });
</script>