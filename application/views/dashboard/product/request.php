<div class="dashboard-content posted-theme">
    <div class="float-right">
        <?php if (JWT::decode($userid, CI_ENCRYPTION_SECRET)) : ?>
            <a href="<?= l('dashboard/product/request/' . $reference) ?>" class="btn btn-custom" data-toggle="tooltip" data-bs-placement="top" title="View received requests"><?= $btn_title ?></a>
        <?php else : ?>
            <a href="<?= l('dashboard/product/request/' . $reference . '/' . $page . '/' . $limit . '/' . JWT::encode($this->userid)) ?>" class="btn btn-custom" data-toggle="tooltip" data-bs-placement="top" title="View my sent requests"><?= $btn_title ?></a>
        <?php endif; ?>
    </div>
    <i class="fa fa-paper-plane"></i>
    <h4><?= $title ?> <a data-toggle="tooltip" data-bs-placement="top" title="<?= $tooltip_title ?>"><i class="fa fa-question-circle"></i></a></h4>
    <hr />
    <table class="style-1">
        <thead>
            <tr>
                <th><?= ucfirst($reference) ?></th>
                <th><?= ucfirst($reference) . ' ' . 'owner' ?></th>
                <th><?= __('Requestor') ?></th>
                <th class="col-4"><?= __('Description') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Createdon') ?></th>
                <th><?= __('Action') ?></th>
            </tr>
            <form action="<?= l('dashboard/product/request/' . $reference . '/' . $page . '/' . $limit . '/' . ($userid)) ?>" method="POST">
                <tr>
                    <th><input type="text" class="form-control" name="product_name" value="<?= isset($product_name) && $product_name ? $product_name : '' ?>" /></th>
                    <th></th>
                    <th><input type="text" class="form-control" name="product_request_signup" value="<?= isset($product_request_signup) && $product_request_signup ? $product_request_signup : '' ?>" /></th>
                    <th></th>
                    <th>
                        <select class="form-select" name="product_request_current_status">
                            <option value="">Select status</option>
                            <option value="<?= REQUEST_PENDING ?>" <?= isset($product_request_current_status) && $product_request_current_status == REQUEST_PENDING ? 'selected' : '' ?>>Pending</option>
                            <option value="<?= REQUEST_ACCEPTED ?>" <?= isset($product_request_current_status) && $product_request_current_status == REQUEST_ACCEPTED ? 'selected' : '' ?>>Accepted</option>
                            <option value="<?= REQUEST_REJECTED ?>" <?= isset($product_request_current_status) && $product_request_current_status == REQUEST_REJECTED ? 'selected' : '' ?>>Rejected</option>
                            <option value="<?= REQUEST_COMPLETE ?>" <?= isset($product_request_current_status) && $product_request_current_status == REQUEST_COMPLETE ? 'selected' : '' ?>>Completed</option>
                            <option value="<?= REQUEST_INCOMPLETE ?>" <?= isset($product_request_current_status) && $product_request_current_status == REQUEST_INCOMPLETE ? 'selected' : '' ?>>Incomplete</option>
                            <option value="<?= REQUEST_UPDATED ?>" <?= isset($product_request_current_status) && $product_request_current_status == REQUEST_UPDATED ? 'selected' : '' ?>>Updated</option>
                        </select>
                    </th>
                    <th></th>
                    <th><button type="submit" class="btn"><i class="fa fa-search"></i></button></th>
                </tr>
            </form>
        </thead>
        <?php if (isset($requests) && count($requests) > 0) : ?>
            <tbody>
                <?php foreach ($requests as $key => $value) : ?>
                    <tr>
                        <td>
                            <a href="<?= l('dashboard/product/detail/' . $value['product_slug']) ?>" target="_blank">
                                <?= $value['product_name'] ?>
                            </a>
                        </td>
                        <td>
                            <?php $owner = $this->model_signup->find_by_pk($value['product_signup_id']); ?>
                            <a href="<?= l('dashboard/profile/detail/' . JWT::encode($owner['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $owner['signup_type']) ?>" target="_blank">
                                <small>
                                    <?= $this->model_signup->profileName($owner, FALSE); ?>
                                </small>
                            </a>
                        </td>
                        <td>
                            <a href="<?= l('dashboard/profile/detail/' . JWT::encode($value['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $value['signup_type']) ?>" target="_blank">
                                <small>
                                    <?= $this->model_signup->profileName($value, FALSE); ?>
                                </small>
                            </a>
                        </td>
                        <td>
                            <?= $value['product_request_description'] ? strip_string($value['product_request_description']) : NA ?>
                        </td>
                        <td>
                            <?php switch ($value['product_request_current_status']) {
                                case REQUEST_PENDING:
                                    echo 'Pending';
                                    break;
                                case REQUEST_ACCEPTED:
                                    echo 'Accepted';
                                    break;
                                case REQUEST_REJECTED:
                                    echo 'Rejected';
                                    break;
                                case REQUEST_COMPLETE:
                                    echo 'Completed';
                                    break;
                                case REQUEST_INCOMPLETE:
                                    echo 'Incomplete';
                                    break;
                                case REQUEST_UPDATED:
                                    echo 'Updated';
                                    break;
                            }
                            ?>
                        </td>
                        <td>
                            <small><?= isset($value['product_request_createdon']) && validateDate($value['product_request_createdon'], 'Y-m-d H:i:s') ? date('d M, Y h:i a', strtotime($value['product_request_createdon'])) : 'Not Available' ?></small>
                        </td>
                        <td>
                            <?php if ($value['product_reference_type'] == PRODUCT_REFERENCE_TECHNOLOGY) : ?>
                                <a data-fancybox data-animation-duration="700" data-src="#viewModal<?= $key ?>" href="javascript:;" data-toggle="tooltip" title="View details" data-bs-placement="top">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <div class="grid">
                                    <div style="display: none;" id="viewModal<?= $key ?>" class="animated-modal">
                                        <h2>Request details</h2>
                                        <?php if ($value['product_request_proposed_fee']) : ?>
                                            <div>
                                                <h5>Proposed Fee</h5>
                                                <p><?= price($value['product_request_proposed_fee']) ?></p>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h5>Description</h5>
                                            <p><?= $value['product_request_description'] ?? NA ?></p>
                                        </div>
                                        <div>
                                            <h5>Attachment</h5>
                                            <?php if ($value['product_request_attachment']) : ?>
                                                <a href="<?= l($value['product_request_attachment_path'] . $value['product_request_attachment']) ?>" target="_blank">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                            <?php else : ?>
                                                <?= NA ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($value['product_signup_id'] == $this->userid) : ?>
                                            <hr />
                                            <form id="responseFrom" action="javascript:;" novalidate>
                                                <input type="hidden" name="_token" />
                                                <input type="hidden" name="product_request_id" value="<?= $value['product_request_id'] ?>" />
                                                <input type="hidden" name="product_request[product_request_product_id]" value="<?= $value['product_request_product_id'] ?>" />
                                                <input type="hidden" name="product_request[product_request_signup_id]" value="<?= $value['product_request_signup_id'] ?>" />
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-select font-12" name="product_request[product_request_current_status]" required>
                                                        <option <?= in_array($value['product_request_current_status'], [REQUEST_COMPLETE, REQUEST_INCOMPLETE]) ? 'disabled' : ''; ?> value="<?= REQUEST_PENDING ?>" <?= $value['product_request_current_status'] == REQUEST_PENDING ? 'selected' : '' ?>>Pending</option>
                                                        <option <?= in_array($value['product_request_current_status'], [REQUEST_COMPLETE, REQUEST_INCOMPLETE]) ? 'disabled' : ''; ?> value="<?= REQUEST_ACCEPTED ?>" <?= $value['product_request_current_status'] == REQUEST_ACCEPTED ? 'selected' : '' ?>>Accept</option>
                                                        <option <?= in_array($value['product_request_current_status'], [REQUEST_COMPLETE, REQUEST_INCOMPLETE]) ? 'disabled' : ''; ?> value="<?= REQUEST_REJECTED ?>" <?= $value['product_request_current_status'] == REQUEST_REJECTED ? 'selected' : '' ?>>Reject</option>
                                                        <option <?= in_array($value['product_request_current_status'], [REQUEST_COMPLETE]) ? 'disabled' : ''; ?> value="<?= REQUEST_UPDATED ?>" <?= $value['product_request_current_status'] == REQUEST_UPDATED ? 'selected' : '' ?>>Updated</option>
                                                        <option value="<?= REQUEST_COMPLETE ?>" <?= $value['product_request_current_status'] == REQUEST_COMPLETE ? 'selected' : '' ?>>Complete</option>
                                                        <option value="<?= REQUEST_INCOMPLETE ?>" <?= $value['product_request_current_status'] == REQUEST_INCOMPLETE ? 'selected' : '' ?>>Incomplete</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Response</label>
                                                    <textarea class="form-control" name="product_request[product_request_response]" placeholder="Add response to this request" maxlength="5000"><?= $value['product_request_response'] ?? '' ?></textarea>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <button class="btn btn-custom">Save</button>
                                                </div>
                                            </form>
                                        <?php else : ?>
                                            <div>
                                                <h5>Status</h5>
                                                <p><?= ucfirst(REQUEST_STATUS[$value['product_request_current_status']]) ?? 'Not available'; ?>
                                            </div>
                                            <div>
                                                <h5>Response</h5>
                                                <p><?= $value['product_request_response'] ?? NA ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($value['product_reference_type'] == PRODUCT_REFERENCE_SERVICE) : ?>
                                <a href="<?= l('dashboard/product/handle/' . JWT::encode($value['product_request_id'])) ?>" data-toggle="tooltip" data-bs-placement="top" title="View details">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <?php if ($value['product_signup_id'] == $this->userid || $value['product_request_signup_id'] == $this->userid) : ?>
                                    <a href="<?= l('dashboard/meeting/listing/' . JWT::encode($value['product_request_id']) . '/1/' . PER_PAGE . '/' . MEETING_REFERENCE_PRODUCT) ?>" data-toggle="tooltip" data-bs-placement="top" title="View all meetings for this request">
                                        <i class="fa-regular fa-server"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <table>
                <small><?= __('No ' . $reference . ' requests available.') ?></small>
            </table>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($requests_count) && ($requests_count) > 0) : ?>
    <div class="row mt-4">
        <div class="col-lg-12">

            <nav aria-label="Page navigation example mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($page <= 1) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page <= 1) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/product/request/') . $reference . '/' . $prev . '/' . $limit . '/' . $userid;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/product/request/') . $reference . '/' . $i . '/' . $limit . '/' . $userid; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/product/request/') . $reference . '/' . $next . '/' . $limit . '/' . $userid;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('body').on('click', '.delete_meeting', function() {

            var data = {
                id: $(this).data('id')
            }
            var url = base_url + 'dashboard/meeting/delete';

            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to delete this meeting!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Ok") ?>'],
            }).then((isConfirm) => {
                if (isConfirm) {

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
                            beforeSend: function() {
                                showLoader()
                            },
                            complete: function() {
                                hideLoader()
                            }
                        })
            		}).then(
            		    function(response) {
            		        if (response.status) {
                                swal("Success", response.txt, "success");
                                $(".dashboard-content").load(location.href + " .dashboard-content>*", "");
                            } else {
                                swal("Error", response.txt, "error").then(() => {
                                    if (response.url != undefined && response.url != '') {
                                        location.href = response.url;
                                    }
                                });
                            }
            		    }
        		    )
                } else {
                    swal("Cancelled", "Action aborted", "error");
                }
            })
        })

        $('body').on('submit', '#responseFrom', function() {
            if (!$('#responseFrom')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#responseFrom').addClass('was-validated');
                $('#responseFrom').find(":invalid").first().focus();
                return false;
            } else {
                $('#responseFrom').removeClass('was-validated');
            }

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $(this).serialize()
            var url = base_url + 'dashboard/product/saveRequest'

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
                    beforeSend: function() {
                        showLoader()
                    },
                    complete: function() {
                        hideLoader()
                    }
                })
    		}).then(
    		    function(response) {
    		        if (response.status) {
                        AdminToastr.success(response.txt)
                        $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                            $('[data-toggle="tooltip"]').tooltip({
                                html: true,
                            })
                        });
                    } else {
                        AdminToastr.error(response.txt)
                    }
    		    }
		    )
        })
    })
</script>