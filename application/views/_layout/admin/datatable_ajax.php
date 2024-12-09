<? global $config; ?>
<!--main content start-->
<section class="wrapper">
  <!-- page start-->
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading">
          <?= $title ? $title : str_replace("_", "-", $this->router->class); ?>
          <? if (!$hide_delete_selected) { ?>
            <button data-pk="1" data-model="stock" class="admin_red delete_selected_rows btn pull-right">Delete Selected</button>
          <? } ?>
          <? if (!$hide_add_button) { ?>
            <a data-pk="1" href="<?= $config['admin_base_url'] . $this->router->class . "/add" ?>" data-model="stock" class="btn admin_green admin_add_button pull-right">Add <?= ucfirst($this->router->class); ?></a>
          <? } ?>
          <? if ($show_excel_button) { ?>
            <a data-pk="1" href="<?= $config['admin_base_url'] . $this->router->class . "/export_excel" . $excel_params ?>" class="btn admin_green pull-right">
              Export to Excel
            </a>
          <? } ?>
        </header>
        <div class="panel-body">
          <div class="adv-table">
            <table class="display table table-bordered table-striped" id="productsTable">
            </table>
          </div>
        </div>
      </section>
      <?
      $request_filter = urldecode($_SERVER['QUERY_STRING']);
      $this->load->view("admin/widget/modal"); ?>
    </div>
</section>

<!--main content end-->
<? $paginate_uri = $pg_config['paginate_uri'] ? $pg_config['paginate_uri'] : "paginate"; ?>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    var aSelected = [];
    msg = "<?= $this->uri->segment(7) == '' ? '' : urldecode($this->uri->segment(7)) ?>";
    if (msg != '') {
      $('#msg .modal-body').html(msg);
      $('#msg').modal('show');
    }
    newTable = $('#productsTable').dataTable({
      "bProcessing": true,
      "bServerSide": true,
      "iDisplayLength": <?= $this->$model->_per_page ? $this->$model->_per_page : 20; ?>,
      "aaSorting": [
        [0, "desc"]
      ],
      "aoColumns": <?php echo $products_column ?>,
      "aoColumnDefs": [{
        bSortable: false,
        aTargets: [-1]
      }],
      "sAjaxSource": "<?= $config['admin_base_url'] . $this->router->class; ?>/<?= $paginate_uri ?>?<?= $request_filter ?>",
      "fnServerParams": function(aoData) {
        aoData.push(
          <?
          $configuration = "";
          if (is_array($pg_config)) {
            foreach ($pg_config as $key => $value) {
              $configuration .= "{ \"name\": \"pg_config[$key]\" , \"value\":\"$value\"},";
            }
          }

          echo $configuration;
          ?> {
            "name": "model",
            "value": "<?= $model ?>"
          }, {
            "name": "dt_fields",
            "value": "<?= $dt_fields ?>"
          }
        );
      },

      "fnRowCallback": function(nRow, aData, iDisplayIndex) {
        if (jQuery.inArray(aData.DT_RowId, aSelected) !== -1) {
          $(nRow).addClass('row_selected');
        }
      },

      "fnDrawCallback": function() {
        if ($('.switch').length > 0) {
          $('.switch')['bootstrapSwitch']();
          $(".switch").click(awesome_flip);
          $(".switch label").click(awesome_flip);
        }
        $(".btn_delete_product").click(delete_function);
        $("#productsTable tr").click(select_rows);
        hide_loader();
        console.log($('#productsTable tbody'));
        $('#productsTable tbody').sortable({
          'update': function(event, ui) {
            var data = {};
            item = ui.item;
            var previous = item.prev();
            var next = item.next();
            data.previous_order = previous.find(".order_field_val").val();
            data.next_order = next.find(".order_field_val").val();
            data.order = item.find(".order_field_val").val();
            data.id = item.find(".order_field_val").attr("data-item-id");
            data.model = '<?= $model ?>';
            url = $js_config.base_url + "admin/<?= $this->router->class ?>/reorder";
            result = make_request(url, data);
            reload_table();
          }
        });

      },

      "fnPreDrawCallback": function() {
        show_loader();
      },

      "bDestroy": true,
      "oLanguage": {
        "sZeroRecords": 'No Record Found!'
      },
      "bPaginate": true,
      "bLengthChange": true
    });


    $(".switch").click(awesome_flip);
    $(".switch label").click(awesome_flip);

    $(".btn_delete_product").click(delete_function);
    $("#productsTable tr").click(select_rows);


    var awesome_flip = function() {

      if ($(this).hasClass("switch"))
        var switch_obj = $(this);
      else
        var switch_obj = $(this).closest(".switch");

      var params = {};

      params.model = switch_obj.attr("data-model");
      params.pk = switch_obj.attr("data-pk");
      params.val = switch_obj.attr("data-value");
      params.field = switch_obj.attr("data-field");

      jQuery.ajax({
        type: "POST",
        url: "<?= $config['admin_base_url'] . $this->router->class; ?>/update",
        data: {
          params: params
        },
        beforeSend: function() {

        },
        success: function(response) {
          if (response == 1) {
            switch_obj.attr("data-value", (params.val == 1 ? 0 : 1));
            $('#success').modal('show');
            $('#success .modal-body').html('<?= ucfirst(str_replace("_", "", $this->router->class)); ?> updated successfully!');
          } else {
            $('#error').modal('show');
            $('#error .modal-body').html('Error in updating <?= ucfirst(str_replace("_", "", $this->router->class)); ?>!');
          }
        }
      })
    };

    var delete_function = function() {
      $('#delete').modal();
      delete_btn = $(this);
      var params = {};
      params.model = delete_btn.attr("data-model");
      params.pk = delete_btn.attr("data-pk");
      $("#yes").click(function() {
        jQuery.ajax({
          type: "POST",
          url: "<?= $config['admin_base_url'] . $this->router->class; ?>/delete",
          data: {
            params: params
          },
          beforeSend: function() {},
          success: function(response) {
            if (response == 1) {
              $('#delete').modal('hide');
              $('#success').modal('show');
              $('#success .modal-body').html('<?= ucfirst(str_replace("_", "", $this->router->class)); ?> deleted successfully!');
              delete_btn.closest("tr").remove();
            } else {
              $('#error').modal('show');
              $('#error .modal-body').html('Error in deleting <?= ucfirst(str_replace("_", "", $this->router->class)); ?>!');
            }
          }
        })
      })

    };

    var select_rows = function() {
      var this_row = $(this);
      this_row.toggleClass("selected_row");
    }

    $(".delete_selected_rows").click(function() {
      var selected_rows = $(".selected_row");
      var total = selected_rows.length;
      if (total == 0) {
        $('#msg .modal-body').html("Please select rows to delete");
        $('#msg').modal('show');
      } else {
        del_modal = $('#delete').modal();
        delete_btn = $(this);
      }
    });

    $("#delete #yes").click(function() {
      var params = {};
      params.pk = "";
      $(".selected_row").find(".btn_delete_product").each(function() {
        params.model = $(this).attr("data-model");
        params.pk += $(this).attr("data-pk") + ",";
      });

      jQuery.ajax({
        type: "POST",
        url: "<?= $config['admin_base_url'] . $this->router->class; ?>/delete_selected",
        data: {
          params: params
        },
        beforeSend: function() {},
        success: function(response) {
          $('#delete').modal('hide');
          $('#success').modal('show');
          $('#success .modal-body').html('<?= ucfirst(str_replace("_", "", $this->router->class)); ?> deleted successfully!');
          console.log(delete_btn.parent("tr"));
          $(".selected_row").remove();
        },
        complete: function(response) {
          if (response == "") {
            $('#delete').modal('hide');
            $('#error').modal('show');
            $('#error .modal-body').html('Error in deleting <?= ucfirst(str_replace("_", "", $this->router->class)); ?>!');
          }
        }
      });
    });

  });

  function reload_table() {
    newTable.fnDraw();
  }
</script>