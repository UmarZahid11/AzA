<? global $config; ?>

<div class="inner-page-header">
    <h1>Box <small>Record</small></h1>
</div>

<div class="row">

    <div class="col-md-12">

        <div class="portlet box green">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-edit"></i> Box User <small>Save details</small>
                </div>
            </div>

            <div class="portlet-body">
                <form id="box_form" action="javascript:;" method="POST" novalidate>
                    <input type="hidden" name="_token" />
                    <?php if ($user && property_exists($user, 'login')) : ?>
                        <input type="hidden" name="user_id" value="<?= $user && property_exists($user, 'id') ? $user->id : '' ?>" />
                        <input type="hidden" name="method" value="put" />
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Login <span class="text-danger">*</span></label>
                        <?php if ($user && property_exists($user, 'login')) : ?>
                            <input type="email" name="login" class="form-control" value="<?= $user && property_exists($user, 'login') ? $user->login : '' ?>" <?= $user && property_exists($user, 'id') ? 'readonly' : '' ?> required />
                        <?php else : ?>
                            <select class="form-select" name="login" required>
                                <option value="">Select login email</option>
                                <?php foreach ($non_box_users as $key => $value) : ?>
                                    <option value="<?= $value['signup_email'] ?>"><?= $value['signup_email'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= $user && property_exists($user, 'name') ? $user->name : '' ?>" required />
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="number" name="phone" class="form-control" value="<?= $user && property_exists($user, 'phone') ? $user->phone : '' ?>" />
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?= $user && property_exists($user, 'address') ? $user->address : '' ?>" />
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="">Select status</option>
                            <?php foreach (BOX_STATUS as $value) : ?>
                                <option value="<?= $value ?>" <?= $user && property_exists($user, 'status') && $user->status == $value ? 'selected' : '' ?>><?= ucfirst($value) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn green" id="boxBtn">Save</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('body').on('submit', '#box_form', function() {
            var boxBtn = $('#boxBtn')

            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $(this).serialize()
            var url = base_url + 'box/saveData'

            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: "json",
                success: function(response) {
                    resolve(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function() {
                    boxBtn.attr('disabled', true)
                    boxBtn.html('Saving ...')
                },
                complete: function() {
                    boxBtn.attr('disabled', false)
                    boxBtn.html('Save')
                }
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt, 'Success');
                        if(response.redirect) {
                            location.href = response.redirect
                        }
                    } else {
                        AdminToastr.error(response.txt, 'Error');
                    }
                }
            )
        })
    })
</script>