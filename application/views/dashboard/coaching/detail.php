<div class="dashboard-content posted-theme">
    <i class="fa fa-desktop"></i>
    <h4><a href="<?= l('dashboard/coaching') ?>"><i class="fa fa-arrow-left"></i></a> <?= __('Coaching details') . ' ' . (isset($coaching['coaching_title']) ? 'for "' . $coaching['coaching_title'] . '"' : '') ?></h4>

    <hr />

    <div class="row">
        <div class="col-6">
            <ul>
                <li>Coaching Topic: <?= (isset($coaching['coaching_title']) ? '"' . $coaching['coaching_title'] . '"' : NA) ?></li>
                <li>Password: <?= $coaching['coaching_password'] ?? NA ?></li>
                <li>Duration: <?= $coaching['coaching_duration'] . ' min(s)' ?></li>

                <li>Start time: <?= $coaching['coaching_start_time'] . ' (' . date('d M, Y h:i a', strtotime($coaching['coaching_start_time'])) . ')' ?></li>
                <?php if($coaching['coaching_timezone']) : ?>
                    <li>Timezone: <?= $coaching['coaching_timezone'] ?></li>
                <?php endif; ?>

                <li>Contact person name: <?= $coaching['coaching_contact_name'] ?? NA ?></li>
                <li>Contact person email: <a href="mailto:<?= $coaching['coaching_contact_email'] ?>"><?= $coaching['coaching_contact_email'] ?? NA ?></a></li>

                <?php if ($this->model_signup->hasRole(ROLE_0)) : ?>
                    <li>UUID: <?= $coaching['coaching_uuid'] ?></li>
                    <li>Webinar id: <?= $coaching['coaching_fetchid'] ?></li>
                    <?php if ($coaching['coaching_current_status'] == ZOOM_MEETING_PENDING) : ?>
                        <li>Start URL: <a href="<?= $coaching['coaching_start_url'] ?>" target="_blank"><?= $coaching['coaching_start_url'] ?></a></li>
                    <?php endif; ?>
                <?php endif; ?>

                <li>Coaching status: <b>
                        <?php switch ($coaching['coaching_current_status']) {
                            case COACHING_PENDING:
                                echo 'Pending';
                                break;
                            case COACHING_STARTED:
                                echo 'Started';
                                break;
                            case COACHING_ENDED:
                                echo 'Ended';
                                break;
                        }
                        ?>
                    </b>
                </li>

                <?php if ($coaching['coaching_response2']) : ?>
                    <li>Coaching interval:
                        <?php $decoded_response2 = json_decode($coaching['coaching_response2']); ?>
                        <?php echo (isset($decoded_response2->payload->object->start_time) ? date('d M, Y h:i a', strtotime($decoded_response2->payload->object->start_time)) : '') .
                            (isset($decoded_response2->payload->object->end_time) ? ' - ' . date('d M, Y h:i a', strtotime($decoded_response2->payload->object->end_time)) : '') ?>
                    </li>
                <?php endif; ?>

                <!-- check if user coaching joining request is accepted -->
                <?php if(!$this->model_signup->hasRole(ROLE_0)) : ?>
                    <?php if ($coaching['coaching_current_status'] == COACHING_STARTED) : ?>
                        <li>Join URL: <a href="<?= $coaching['coaching_join_url'] ?>" target="_blank"><?= $coaching['coaching_join_url'] ?></a></li>
                    <?php else : ?>
                        Note: <span class="text-danger"><?= __('Join URL will be available once coaching has been started') ?>.</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php $json_decoded = json_decode($coaching['coaching_response']); ?>

                <li>Created on: <?= date('Y-m-d H:i:s', strtotime($coaching['coaching_createdon'])) ?></li>
                <li>Coaching recording:
                    <?php
                    if ($coaching_recording && is_object($coaching_recording) && property_exists($coaching_recording, 'recording_files')) {
                        if (is_array($coaching_recording->recording_files) && count($coaching_recording->recording_files) > 0) {
                            foreach ($coaching_recording->recording_files as $key => $value) {
                                echo '<ul>';
                                if (property_exists($value, 'play_url')) {
                                    echo '<li><a href="' . $value->play_url . '" target="_blank"><i class="fa fa-play"></i> Play</a> ';
                                }
                                if (property_exists($value, 'play_url')) {
                                    echo '<a href="' . $value->download_url . '" target="_blank"><i class="fa fa-download"></i> Download</a></li>';
                                }
                                echo '</ul>';
                            }
                        }
                        if (property_exists($coaching_recording, 'password')) {
                            echo '<li>Password: ' . $coaching_recording->password . '</li>';
                        }
                        if (property_exists($coaching_recording, 'recording_play_passcode')) {
                            echo '<li>Recording play passcode : ' . $coaching_recording->recording_play_passcode . '</li>';
                        }
                    } else {
                        if ($coaching_recording && is_object($coaching_recording) && property_exists($coaching_recording, 'message')) {
                            echo '<span class="text-danger">' . $coaching_recording->message . '</span>';
                        } else {
                            echo '<span class="text-danger">This recording does not exist.</span>';
                        }
                    }
                    ?>
                </li>

                <hr />

                <a href="<?= l('dashboard/coaching'); ?>" target="_blank">See all coachings.</a>

            </ul>
        </div>
    
        <?php if(!$this->model_signup->hasRole(ROLE_0)) : ?>
            <div class="col-6" id="requestDiv">
                <h4>Request participation</h4>
                <?php if(!$this->model_coaching_application->userApplicationExists($this->userid, $coaching['coaching_id'])) : ?>
                    <form action="javascript:;" method="POST" id="requestForm">
                        <input type="hidden" name="_token" value="<?= $this->csrf_token ?>" />
                        <input type="hidden" name="coaching_application[coaching_application_signup_id]" value="<?= $this->userid ?>" />
                        <input type="hidden" name="coaching_application[coaching_application_coaching_id]" value="<?= $coaching['coaching_id'] ?>" />
                        <div class="row">
                            <div class="col-6 my-2">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="coaching_application[coaching_application_email]" class="form-control" value="<?= $this->user_data['signup_email'] ?>" maxlength="255" required />
                            </div>
                            <div class="col-6 my-2">
                                <label>Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="coaching_application[coaching_application_phone]" class="form-control" value="<?= $this->user_data['signup_phone'] ?? '' ?>" maxlength="255" required />
                            </div>
                            <div class="col-12 my-2">
                                <label>Message</label>
                                <textarea name="coaching_application[coaching_application_message]" class="form-control" maxlength="1000"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" id="requestBtn" class="btn btn-custom w-100">Request</button>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    <?php $user_application = $this->model_coaching_application->getUserApplication($this->userid, $coaching['coaching_id']); ?>
                    <?php if($user_application) : ?>
                        <?php if($user_application['coaching_application_status'] == 0) : ?>
                            <p>The request has been sent!</p>
                        <?php endif; ?>
                        <p>Status: 
                            <strong>
                                <?php 
                                    switch($user_application['coaching_application_status']) {
                                        case 0:
                                            echo '<i class="text-warning">Pending</i>';
                                            break;
                                        case 1:
                                            echo '<i class="text-success">Accepted</i>';
                                            break;
                                        case 2:
                                            echo '<i class="text-danger">Rejected</i>';
                                            break;
                                        default:
                                            echo '<i class="text-warning">Pending</i>';
                                    }
                                ?>
                            </strong>
                        </p>
                        <p>Payment Status: 
                            <strong>
                                <?php 
                                    switch($user_application['coaching_application_payment_status']) {
                                        case 0:
                                            echo '<i class="text-warning">Unpaid</i>';
                                            break;
                                        case 1:
                                            echo '<i class="text-success">Paid</i>';
                                            break;
                                        default:
                                            echo '<i class="text-warning">Unpaid</i>';
                                    }
                                ?>
                            </strong>
                        </p>
                        <?php if($user_application['coaching_application_payment_status'] == 0) : ?>
                            <?php
                                $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
                                $session_url = '';
                                $session = $stripe->checkout->sessions->retrieve($user_application['coaching_application_checkout_session_id']);
                                if($session) {
                                    $session_url = $session->url;
                                }
                            ?>
                            <?php if($session_url) : ?>
                                <a href="<?= $session_url ?>" class="btn btn-custom">Complete Payment</a>
                            <?php else: ?>
                                <form action="javascipt:;" method="POST" id="newPaymentForm">
                                    <input type="hidden" name="_token" value="<?= $this->csrf_token ?>" />
                                    <input type="hidden" name="coaching_application[coaching_application_signup_id]" value="<?= $this->userid ?>" />
                                    <input type="hidden" name="coaching_application[coaching_application_coaching_id]" value="<?= $coaching['coaching_id'] ?>" />
                                    <button type="submit" class="btn btn-custom" id="newPaymentFormBtn">Request new payment link</button>
                                </form>
                            <?php endif; ?>                       
                        <?php endif; ?>
                    <?php else : ?>
                        <p class="text-danger"><?= NA ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#requestForm').on('submit', function() {
            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: '<?= l('dashboard/coaching/saveApplication') ?>',
                    type: "POST",
                    data: $('#requestForm').serialize(),
                    async: true,
                    dataType: 'json',
                    success: function (response) {
                        resolve(response)
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $('#requestBtn').attr('disabled', true);
                        $('#requestBtn').html('Requesting ...');
                    },
                    complete: function() {
                        $('#requestBtn').attr('disabled', false);
                        $('#requestBtn').html('Request');
                    }
                });
            }).then(
                function(response) {
                    if(response.status) {
                        $('#requestBtn').html('Sent');
                        setTimeout(function() {
                            $('#requestDiv').html("<h4>Request participation</h4><p>The request has been sent!</p>");
                            if(response.session_url) {
                                location.href = response.session_url;
                            }
                        }, 1000);
                    } else {
                        toastr.error(response.txt);
                    }
                }
            );
        });
    });
</script>