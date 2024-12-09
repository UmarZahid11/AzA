<?php
global $config;
?>

<style>
    .portlet.box>.portlet-title>.caption {
        padding: 18px 0 9px 0;
    }
</style>

<div class="inner-page-header">

    <h1><?= humanize($class_name) ?> <small>Details</small></h1>

</div>
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-money"></i>
            <strong>Milestone Payment #<?= $job_milestone_payment_detail['job_milestone_payment_id'] ?> </strong>
            <small> / <?= date("Y-m-d", strtotime($job_milestone_payment_detail['job_milestone_payment_createdon'])) ?></small>
        </div>
        <div class="tools">
            <?php if($job_milestone_payment_detail['job_milestone_payment_method'] == MILESTONE_STRIPE_PAYMENT): ?>
                <button class="btn btn-primary" id="run-cron-manually" data-toggle="tooltip" title="Transfer escrow amount for this milestone manually." data-placement="top" data-id="<?= $job_milestone_payment_detail['job_milestone_payment_id'] ?>">
                    Run transfer for this payment
                </button>
            <?php endif; ?>
            <a onclick="print_div();" class="label label-white">
                <i class="fa fa-print"></i>
            </a>
        </div>
    </div>

    <div class="portlet-body form">
        <div class="invoice container" id="invoice" style="padding: 20px;">
            <div class="row invoice-logo">
                <div class="col-xs-4 invoice-logo-space">
                    <img style="height: 100px;" src="<?= get_image($this->layout_data['logo'][0]['logo_image_path'], $this->layout_data['logo'][0]['logo_image']) ?>" alt="logo" class="main-tem-logo" />
                </div>
                <div class="col-xs-4">
                    <strong>Platform fees paid:</strong> <?= isValidDate($job_milestone_payment_detail['job_milestone_payment_createdon'], 'Y-m-d H:i:s') ? date('M, d Y H:i a', strtotime($job_milestone_payment_detail['job_milestone_payment_createdon'])) : NA; ?><br />
                    <strong>Platform fees received:</strong> <?= isValidDate($job_milestone_payment_detail['job_milestone_payment_createdon'], 'Y-m-d H:i:s') ? date('M, d Y H:i a', strtotime($job_milestone_payment_detail['job_milestone_payment_createdon'])) : NA; ?><br />
                    <strong>Money Status: </strong> <?= $this->model_job_milestone_payment->get_money_status($job_milestone_payment_detail['job_milestone_payment_money_position_status']); ?>
                </div>
                <div class="col-xs-4">
                    <strong>Created on:</strong> <?= isValidDate($job_milestone_payment_detail['job_milestone_payment_createdon'], 'Y-m-d H:i:s') ? date('M, d Y H:i a', strtotime($job_milestone_payment_detail['job_milestone_payment_createdon'])) : NA; ?><br />
                    <strong>Last updated by:</strong> <?= $job_milestone_payment_detail['job_milestone_payment_last_updated_by'] ? ($this->model_signup->find_by_pk($job_milestone_payment_detail['job_milestone_payment_last_updated_by']) ? $this->model_signup->profileName($this->model_signup->find_by_pk($job_milestone_payment_detail['job_milestone_payment_last_updated_by']), FALSE) : NA) : NA ?><br />
                    <strong>Last updated on:</strong> <?= isValidDate($job_milestone_payment_detail['job_milestone_payment_updatedon'], 'Y-m-d H:i:s') ? date('M, d Y H:i a', strtotime($job_milestone_payment_detail['job_milestone_payment_updatedon'])) : NA; ?><br />
                </div>

            </div>
            <hr>
            <div class="row">
                <div class="col-xs-4">
                    <h3><strong>Billing Info:</strong></h3>
                    <ul class="list-unstyled">
                        <li><strong> First Name: </strong><?= $job_milestone_payment_detail['signup_firstname'] ?? NA; ?> </li>
                        <li><strong> Last Name: </strong><?= $job_milestone_payment_detail['signup_lastname'] ?? NA; ?></li>
                        <li><strong> Email: </strong><a href="mailto:<?= $job_milestone_payment_detail['signup_email']; ?>"><?= $job_milestone_payment_detail['signup_email']; ?></a> </li>
                        <li><strong> Phone: </strong><a href="<?= $job_milestone_payment_detail['signup_phone'] ? 'tel:' . $job_milestone_payment_detail['signup_phone'] : 'javascript:;'; ?>"><?= $job_milestone_payment_detail['signup_phone'] ?? NA; ?> </a></li>
                        <li><strong> Address: </strong><?= $job_milestone_payment_detail['signup_address'] ?? NA; ?> </li>
                    </ul>
                </div>

                <div class="col-xs-4">
                    <h3><strong>Job Info:</strong></h3>
                    <ul class="list-unstyled">
                        <li><strong> Title: </strong><a href="<?= l('job/detail/') . $job_milestone_payment_detail['job_slug'] ?>" target="_blank"><?= $job_milestone_payment_detail['job_title'] ?? NA; ?></a> </li>
                        <li><strong> Short detail: </strong><?= $job_milestone_payment_detail['job_short_detail'] ?? NA; ?> </li>
                        <a type="button" class="" data-toggle="modal" data-target="#jobDescModal"><i class="fa fa-ellipsis-h"></i></a>
                        <li><strong> Type: </strong><?= $job_milestone_payment_detail['job_type'] ?? NA; ?> </li>
                        <li><strong> Location: </strong><?= $job_milestone_payment_detail['job_location'] ?? NA; ?> </li>
                        <li><strong> Tags: </strong><?= $job_milestone_payment_detail['job_tags'] ?? NA; ?> </li>

                        <!-- Modal -->
                        <div class="modal fade" id="jobDescModal" tabindex="-1" role="dialog" aria-labelledby="jobDescModalTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h4>Job Detail</h4>
                                        <?= $job_milestone_payment_detail['job_detail'] ?? NA; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>

                <div class="col-xs-4">
                    <h3><strong>Payment Info:</strong></h3>

                    <ul class="list-unstyled">
                        <li><strong>Payment Status: </strong> <?= $this->model_job_milestone_payment->get_payment_status($job_milestone_payment_detail['job_milestone_payment_status']); ?></li>
                        <li><strong>Total Amount: </strong><?= price($job_milestone_payment_detail['job_milestone_payment_amount']) ?></li>
                        <li><strong>Charge ID: </strong>
                            <p style="word-wrap: break-word;"><?= $job_milestone_payment_detail['job_milestone_payment_charge_id'] ?? NA; ?></p>
                            <a type="button" class="" data-toggle="modal" data-target="#paymentResponseModalLong"><i class="fa fa-ellipsis-h"></i></a>
                        </li>

                        <?php if($job_milestone_payment_detail['job_milestone_payment_method'] == MILESTONE_STRIPE_PAYMENT): ?>
                            <li><strong>Receipt URL: </strong>
                                <?php
                                    if($job_milestone_payment_detail['job_milestone_payment_receipt_url']) {
                                        echo '<a href="' .  $job_milestone_payment_detail['job_milestone_payment_receipt_url'] . '" target="_blank" data-toggle="tooltip" title="View payment receipt for charge." data-placement="top" ><i class="fa fa-link"></i></a>';
                                    } else {
                                        if(NULL !== json_decode($job_milestone_payment_detail['job_milestone_payment_response'])->receipt_url) {
                                            echo '<a href="' . json_decode($job_milestone_payment_detail['job_milestone_payment_response'])->receipt_url . '" target="_blank" data-toggle="tooltip" title="View payment receipt for charge." data-placement="top" ><i class="fa fa-link"></i></a>';
                                        }
                                    }
                                ?>
                            </li>
                        <?php endif; ?>

                        <!-- Modal -->
                        <div class="modal fade" id="paymentResponseModalLong" tabindex="-1" role="dialog" aria-labelledby="paymentResponseModalLongTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <?php if ($job_milestone_payment_detail['job_milestone_payment_response']) : ?>
                                            <pre><?= $job_milestone_payment_detail['job_milestone_payment_response'] ?></pre>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <li><strong>Transaction ID: </strong>
                            <p style="word-wrap: break-word;"><?= $job_milestone_payment_detail['job_milestone_payment_transaction_id'] ?? NA; ?></p>
                        </li>
                    </ul>
                </div>

            </div>
            <hr />
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h4><strong>Milestone:</strong></h4>

                    </div>
                    <div class="col-md-12 col-xs-12">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Detail</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding:10px 0; vertical-align:middle;">
                                        <?= $job_milestone_payment_detail['job_milestone_title'] ?>
                                    </td>
                                    <td style="padding:10px 0; vertical-align:middle;">
                                        <?= $job_milestone_payment_detail['job_milestone_text'] ?>
                                    </td>
                                    <td style="padding:10px 0; vertical-align:middle;">
                                        <?= price($job_milestone_payment_detail['job_milestone_amount']) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>

            <hr />

            <div class="row">
                <div class="col-md-12 col-xs-12 invoice-block text-right">
                    <ul class="list-unstyled amounts">
                        <li><strong style="color:#333"><?= $job_milestone_payment_detail['job_milestone_payment_money_position_status'] == MILESTONE_PAYMENT_PAID ? 'Milestone amount Paid' : 'Milestone amount due' ?> </strong>
                            : <?= price($job_milestone_payment_detail['job_milestone_payment_due']) ?> </li>

                        <li><strong style="color:#333">Platform fee</strong>
                            : <?= price($job_milestone_payment_detail['job_milestone_payment_amount'] - $job_milestone_payment_detail['job_milestone_payment_due']) ?> </li>

                        <li><strong style="color:#333">Total</strong>
                            : <?= price($job_milestone_payment_detail['job_milestone_payment_amount']) ?> </li>
                    </ul>
                    <br>
                </div>
            </div>

            <small>Questions? Email: <a href="mailto:<?= g('db.admin.email') ?>"><?= g('db.admin.email') ?></a></small>

        </div>
    </div>
</div>

<script>
    function print_div() {
        var printContents = document.getElementById('invoice').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents
    }

    $(document).ready(function() {
        $('body').on('click', '#run-cron-manually', function() {
            var runCronBtn = '#run-cron-manually'
            var data = {
                'force_manual' : true,
                '_token': $('meta[name=csrf-token]').attr("content")
            }
            if ($(this).data('id')) {
                data.id = $(this).data('id')
            }
            var url = '<?= $config['base_url'] . 'job_milestone/milestone_payment_transfer' ?>'

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
                        $(runCronBtn).attr('disabled', true)
                        $(runCronBtn).html('Running ...')
                    },
                    complete: function() {
                        $(runCronBtn).attr('disabled', false)
                        $(runCronBtn).html('Run transfer for this payment')
                    }
                })
			}).then(
			    function(response) {
                    if (response.status) {
                        AdminToastr.success(response.message)
                        if (response.refresh) {
                            $("#invoice").load(location.href + " #invoice>*", "")
                        }
                    } else {
                        $('.Error').html('')
                        $('.Error').append(
                            '<label class="label label-danger">' + response.message + '</label>' +
                            '<p>' + response.reason + '</p>'
                        )
                        AdminToastr.error(response.message)
                    }
                }
            )
        })
    })
</script>