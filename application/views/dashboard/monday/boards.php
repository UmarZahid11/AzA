<div class="dashboard-content posted-theme">
    <div class="float-right d-flex">
        <button data-fancybox data-animation-duration="700" data-src="#addBoardModal" href="javascript:;" class="btn btn-outline-custom" data-toggle="tooltip" title="" data-bs-placement="top">Add Board</button>
    </div>
    <img src="https://www.vectorlogo.zone/logos/monday/monday-icon.svg" style="width:20px;" />
    <h4>Monday</h4>
    <hr />

    <div class="row">
        <?php if (isset($boards) && $boards) : ?>
            <?php foreach ($boards as $board) : ?>
                <?php if (isset($board['boards']) && !empty($board['boards'])) : ?>
                    <?php foreach ($board['boards'] as $boardData) : ?>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-image">
                                    <img
                                        src="https://cdn.monday.com/images/quick_search_recent_board2.svg"
                                        class="card-img-top"
                                        src="..."
                                        alt="Card image cap"
                                        style="width:100%" 
                                    />
                                </div>
                                <div class="card-body">
                                    <div class="card-title">
                                        <h5
                                            title="<?= $boardData['name'] ?>"
                                            data-toggle="tooltip">
                                            <?= strip_string($boardData['name'], 18); ?>
                                        </h5>
                                    </div>
                                    <a href="<?= l('dashboard/monday/groups/' . $boardData['id']) ?>" class="btn btn-custom">View detail</a>
                                    <button data-fancybox data-animation-duration="700" data-src="#updateBoardModal<?= $boardData['id']; ?>" href="javascript:;" class="btn btn-warning" data-toggle="tooltip" title="" data-bs-placement="top">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form class="deleteBoardForm d-inline" data-id="<?= $boardData['id']; ?>" action="javascript:;" novalidate>
                                        <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                                        <input type="hidden" name="type" value="board" />
                                        <input type="hidden" name="id" value="<?= $boardData['id']; ?>" />
                                        <button class="btn btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    <div class="grid">
                                        <div style="display: none;" id="updateBoardModal<?= $boardData['id']; ?>" class="animated-modal">
                                            <h4>Save Board</h4>
                                            <form class="boardForm" data-id="<?= $boardData['id']; ?>" action="javascript:;" novalidate>
                                                <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                                                <input type="hidden" name="type" value="board" />
                                                <input type="hidden" name="id" value="<?= $boardData['id']; ?>" />
                                                <div class="row">
                                                    <div class="col-12 form-group">
                                                        <label>Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" name="name" maxlength="255" value="<?= $boardData['name'] ?>" required />
                                                    </div>
                                                    <!-- <div class="col-12 form-group">
                                                        <label>Kind <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="kind" required>
                                                            <option value="public" <?= $boardData['board_kind'] == 'public' ? 'selected' : ''; ?> >Public</option>
                                                            <option value="private" <?= $boardData['board_kind'] == 'private' ? 'selected' : ''; ?>>Private</option>
                                                        </select>
                                                    </div> -->
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
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="grid">
    <div style="display: none;" id="addBoardModal" class="animated-modal">
        <h4>Add Board</h4>
        <form class="boardForm" data-id="0" action="javascript:;" novalidate>
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <input type="hidden" name="type" value="board" />
            <div class="row">
                <div class="col-12 form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input class="form-control" name="name" maxlength="255" required />
                </div>
                <!-- <div class="col-12 form-group">
                    <label>Kind <span class="text-danger">*</span></label>
                    <select class="form-select" name="kind" required>
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select>
                </div> -->
            </div>
            <div class="form-group mt-2">
                <button type="submit" class="btn btn-custom w-50 offset-3">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function() {
        $('.boardForm').on('submit', function(e) {

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
                        // if(id == 0) {
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

        $('.deleteBoardForm').on('submit', function(e) {
            e.preventDefault();
            var button = $(this).find('button');
            var form = this;
            var id = $(this).data('id');

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: '<?= l('dashboard/monday/deleteBoard') ?>',
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