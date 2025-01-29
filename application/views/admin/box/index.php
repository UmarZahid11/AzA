<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" integrity="sha512-BMbq2It2D3J17/C7aRklzOODG1IQ3+MHw3ifzBHMBwGO/0yUqYmsStgBjI0z5EYlaDEFnvYV7gNYdD3vFLRKsA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php
global $config;
?>
<div class="inner-page-header">

    <h1><?= humanize($class_name) ?> <small>User listing</small></h1>

</div>

<div class="row">

    <div class="col-md-12">

        <div class="portlet">

            <div class="portlet-title">

                <a href="<?= la('box/save') ?>">
                    <button class="btn default yellow-stripe float-right">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                            Add new box user
                        </span>
                    </button>
                </a>

            </div>

            <div class="portlet-body">

                <div class="table-container">

                    <div class="table-actions-wrapper">
                    </div>

                    <table class="table table-striped table-bordered table-hover" id="datatable_ajax">

                        <thead>

                            <tr role="row" class="heading">
                                <th></th>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Login</th>
                                <th>Created at</th>
                                <th>Space amount</th>
                                <th>Space used</th>
                                <th>Max upload size</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php if (property_exists($users, 'entries')) : ?>
                                <?php foreach ($users->entries as $key => $argv) : ?>

                                    <tr>
                                        <td><img src="<?php echo $argv->avatar_url ?>" /></td>
                                        <td><?php echo $argv->id ?></td>
                                        <td><?php echo $argv->name ?></td>
                                        <td><?php echo $argv->login ?></td>
                                        <td><?php echo $argv->created_at ?></td>
                                        <td><?php echo $argv->space_amount ?></td>
                                        <td><?php echo $argv->space_used ?></td>
                                        <td><?php echo $argv->max_upload_size ?></td>
                                        <td><span class="badge"><?php echo $argv->status ?></span></td>
                                        <td>
                                            <a title="Edit user" href="<?= la('box/save/' . $argv->id) ?>" target="_blank">
                                                <button class="btn-sm btn yellow" data-pk="<?= $argv->id ?>"><i class="fa fa-edit"></i></button>
                                            </a>
                                            <button data-toggle="modal" data-target="#deleteModal<?= $key ?>" data-bs-placement="top" title="Delete user" class="btn-sm btn red" data-model="model_job" data-pk="<?= $argv->id ?>">
                                                <i class="icon-trash"></i>
                                            </button>
                                            <div class="modal fade" id="deleteModal<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <i class="fa fa-exclamation-circle fa-2x"></i>
                                                                <p>Are you sure?</p>
                                                                <form class="deleteBoxUser" action="javascript:;" method="POST" novalidate>
                                                                    <input type="hidden" name="_token" />
                                                                    <input type="hidden" name="user_id" value="<?= $argv->id ?>" />
                                                                    <input type="hidden" name="method" value="delete" />
                                                                    <p><b>Delete this box user.</b></p>
                                                                    <button type="submit" class="btn btn-primary">Yes</button>
                                                                    <button type="button" class="btn btn-danger cancelBtn">No</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>
                            <?php else : ?>
                                <small class="text-danger">Authorize Box to view users</small>
                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('body').on('submit', '.deleteBoxUser', function(event) {
            event.preventDefault();
            if (!$(this)[0].checkValidity()) {
                event.stopPropagation();
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $(this).serialize()
            var url = base_url + 'box/saveData'

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    async: true,
                    dataType: "json",
                    success: function(response) {
                        resolve(response)
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                })
			}).then(
			    function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt)
                        $('.modal').hide()
                        $(".portlet-body").load(location.href + " .portlet-body>*", "");
                    } else {
                        AdminToastr.error(response.txt)
                    }
			    }
		    )
        })

        $('body').on('click', '.cancelBtn', function(event) {
            $('.modal').hide()
        })

    })
</script>