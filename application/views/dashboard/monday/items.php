<div class="dashboard-content posted-theme">
    <div class="float-right d-flex">
        <button data-fancybox data-animation-duration="700" data-src="#addItemModal" href="javascript:;" class="btn btn-outline-custom" data-toggle="tooltip" title="" data-bs-placement="top">Add Item</button>
    </div>
    <img src="https://www.vectorlogo.zone/logos/monday/monday-icon.svg" style="width:20px;" />
    <h4>
        <a href="<?= l('dashboard/monday/boards') ?>">
            <?= $boardDetail['data']['boards'][0]['name']; ?> <i class="fa fa-arrow-right"></i>
        </a>
        <a href="<?= l('dashboard/monday/groups/' . $boardDetail['data']['boards'][0]['id']); ?>">
            <?= $groupDetail['title']; ?>
        </a>
    </h4>
    <hr />

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive"> 
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <?php if(isset($boardColumns) && isset($boardColumns['data']['boards'][0]['columns'])) : ?>
                            <tr>
                                <?php foreach($boardColumns['data']['boards'][0]['columns'] as $column) : ?>
                                    <?php if($column['title'] != 'Subitems') : ?>
                                        <th data-id="<?= $column['id'] ?>"><?= $column['title'] ?></th>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <th>.</th>
                            </tr>
                        <?php endif; ?>
                    </thead>
                    <tbody>
                        <?php if(isset($items) && !empty($items)) : ?>
                            <?php foreach($items as $item) : ?>
                                <?php if($item['group']['id'] == $group_id) : ?>
                                    <tr>
                                        <td><?= $item['name'] ?></td>
                                        <?php foreach($item['column_values'] as $item_column_values) : ?>
                                            <?php if($item_column_values['id'] != 'subitems_mkmgcb1b') : ?>
                                                <td><?= ($item_column_values['text']); ?></td>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <td>
                                            <form class="deleteItemForm d-inline" data-id="<?= $item['id']; ?>" action="javascript:;" novalidate>
                                                <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                                                <input type="hidden" name="type" value="item" />
                                                <input type="hidden" name="board_id" value="<?= $board_id; ?>" />
                                                <input type="hidden" name="group_id" value="<?= $group_id; ?>" />
                                                <input type="hidden" name="id" value="<?= $item['id']; ?>" />
                                                <button class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if(isset($items) && $items) : ?>
                    <div class="d-flex justify-content-center">
                        <?php if($cursor) : ?>
                            <a href="<?= l('dashboard/monday/items/' . $board_id . '/' . $group_id . '/' . $limit . '/' . $cursor) ?>">Next</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="grid">
    <div style="display: none;" id="addItemModal" class="animated-modal">
        <h4>Add Item</h4>
        <form class="itemForm" data-id="0" action="javascript:;" novalidate>
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <input type="hidden" name="type" value="item" />
            <input type="hidden" name="board_id" value="<?= $board_id ?>" />
            <input type="hidden" name="group_id" value="<?= $group_id ?>" />

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
        $('.itemForm').on('submit', function(e) {

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

        $('.deleteItemForm').on('submit', function(e) {
            e.preventDefault();
            var button = $(this).find('button');
            var form = this;
            var id = $(this).data('id');

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: '<?= l('dashboard/monday/deleteItem') ?>',
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