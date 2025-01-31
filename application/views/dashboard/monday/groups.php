<div class="dashboard-content posted-theme">
    <div class="float-right d-flex">
        <button data-fancybox data-animation-duration="700" data-src="#addGroupModal" href="javascript:;" class="btn btn-outline-custom" data-toggle="tooltip" title="" data-bs-placement="top">Add Group</button>
    </div>
    <img src="https://www.vectorlogo.zone/logos/monday/monday-icon.svg" style="width:20px;" />
    <h4>
        <a href="<?= l('dashboard/monday/boards') ?>">
            <?= $boardDetail['data']['boards'][0]['name'] ?>
        </a>
    </h4>
    <hr />

    <div class="row">
        <?php if (isset($boardGroups) && isset($boardGroups['data']['boards']) && !empty($boardGroups['data']['boards'])) : ?>
            <?php foreach ($boardGroups['data']['boards'] as $groups) : ?>
                <?php foreach ($groups['groups'] as $group) : ?>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <h5
                                    class="card-title">
                                    <?= ($group['title']); ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <a href="<?= l('dashboard/monday/items/' . $board_id . '/' . $group['id']) ?>" class="btn btn-custom">View detail</a>
                                <button data-fancybox data-animation-duration="700" data-src="#updateGroupModal<?= $group['id']; ?>" href="javascript:;" class="btn btn-warning" data-toggle="tooltip" title="" data-bs-placement="top">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form class="deleteGroupForm d-inline" data-id="<?= $group['id']; ?>" action="javascript:;" novalidate>
                                    <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                                    <input type="hidden" name="type" value="group" />
                                    <input type="hidden" name="board_id" value="<?= $board_id; ?>" />
                                    <input type="hidden" name="id" value="<?= $group['id']; ?>" />
                                    <button class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                <div class="grid">
                                    <div style="display: none;" id="updateGroupModal<?= $group['id']; ?>" class="animated-modal">
                                        <h4>Save Group</h4>
                                        <form class="groupForm" data-id="<?= $group['id']; ?>" action="javascript:;" novalidate>
                                            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                                            <input type="hidden" name="type" value="group" />
                                            <input type="hidden" name="board_id" value="<?= $board_id; ?>" />
                                            <input type="hidden" name="id" value="<?= $group['id']; ?>" />
                                            <div class="row">
                                                <div class="col-12 form-group">
                                                    <label>Name <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="name" maxlength="255" value="<?= $group['title'] ?>" required />
                                                </div>
                                            </div>
                                            <div class="form-group mt-2">
                                                <button type="submit" class="btn btn-custom w-50 offset-3">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="grid">
    <div style="display: none;" id="addGroupModal" class="animated-modal">
        <h4>Add Group</h4>
        <form class="groupForm" data-id="0" action="javascript:;" novalidate>
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <input type="hidden" name="type" value="group" />
            <input type="hidden" name="board_id" value="<?= $board_id ?>" />
            <div class="row">
                <div class="col-12 form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input class="form-control" name="name" maxlength="255" required />
                </div>
            </div>
            <div class="form-group mt-2">
                <button type="submit" class="btn btn-custom w-50 offset-3">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function() {
        $('.groupForm').on('submit', function(e) {

            e.preventDefault();
            if (!$(this)[0].checkValidity()) {
                event.stopPropagation();
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            var button = $(this).find('button');
            var form = this;
            var id = $(this).data('id');

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: '<?= l('dashboard/monday/saveData') ?>',
                    type: "POST",
                    data: $(this).serialize(),
                    async: true,
                    dataType: 'json',
                    success: function(response) {
                        resolve(response)
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function(jqXHR) {
                        $(button).attr('disabled', true);
                        $(button).html('Saving ...');
                    },
                    complete: function() {
                        $(button).attr('disabled', false);
                        $(button).html('Save');
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        $('.fancybox-close-small').trigger("click");
                        toastr.success(response.txt);
                        // if (id == 0) {
                            $(form).each(function() {
                                this.reset();
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        // }
                    } else {
                        toastr.error(response.txt);
                    }
                }
            );
        });

        $('.deleteGroupForm').on('submit', function(e) {
            e.preventDefault();
            var button = $(this).find('button');
            var form = this;
            var id = $(this).data('id');

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: '<?= l('dashboard/monday/deleteGroup') ?>',
                    type: "POST",
                    data: $(this).serialize(),
                    async: true,
                    dataType: 'json',
                    success: function(response) {
                        resolve(response)
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function(jqXHR) {
                        $(button).attr('disabled', true);
                        $(button).html('<i class="fa fa-loader fa-spin"></i>');
                    },
                    complete: function() {
                        $(button).attr('disabled', false);
                        $(button).html('<i class="fa fa-trash"></i>');
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        toastr.success(response.txt);
                        $(button).html('<i class="fa fa-check"></i>');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.txt);
                    }
                }
            );
        });
    });
</script>