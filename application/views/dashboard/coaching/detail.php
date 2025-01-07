<?php $user_application = $this->model_coaching_application->getUserApplication($this->userid, $coaching['coaching_id']); ?>

<div class="dashboard-content posted-theme">
    <i class="fa fa-desktop"></i>
    <h4><a href="<?= l('dashboard/coaching') ?>"><i class="fa fa-arrow-left"></i></a> <?= __('Coaching details') . ' ' . (isset($coaching['coaching_title']) ? 'for "' . $coaching['coaching_title'] . '"' : '') ?></h4>

    <hr />

    <div class="row">
        <div class="col-6">
            <ul>
                <li>Coaching Topic: <?= (isset($coaching['coaching_title']) ? '"' . $coaching['coaching_title'] . '"' : NA) ?></li>
                <?php if ($this->model_signup->hasRole(ROLE_0) || ($user_application && $user_application['coaching_application_status'] == STATUS_ACTIVE)) : ?>
                    <li>Password: <?= $coaching['coaching_password'] ?? NA ?></li>
                <?php endif; ?>
                <li>Duration: <?= $coaching['coaching_duration'] . ' min(s)' ?></li>

                <li>Start time: <?= $coaching['coaching_start_time'] . ' (' . date('d M, Y h:i a', strtotime($coaching['coaching_start_time'])) . ')' ?></li>
                <?php if ($coaching['coaching_timezone']) : ?>
                    <li>Timezone: <?= $coaching['coaching_timezone'] ?></li>
                <?php endif; ?>

                <li>Contact person name: <?= $coaching['coaching_contact_name'] ?? NA ?></li>
                <li>Contact person email: <a href="mailto:<?= $coaching['coaching_contact_email'] ?>"><?= $coaching['coaching_contact_email'] ?? NA ?></a></li>

                <?php if ($this->model_signup->hasRole(ROLE_0)) : ?>
                    <li>UUID: <?= $coaching['coaching_uuid'] ?></li>
                    <li>Webinar id: <?= $coaching['coaching_fetchid'] ?></li>
                    <?php //if ($coaching['coaching_current_status'] == ZOOM_MEETING_PENDING) : 
                    ?>
                    <li>Start URL: <a href="<?= $coaching['coaching_start_url'] ?>" target="_blank"><?= $coaching['coaching_start_url'] ?></a></li>
                    <?php //endif; 
                    ?>
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
                <?php if (!$this->model_signup->hasRole(ROLE_0) && $user_application && $user_application['coaching_application_status'] == STATUS_ACTIVE) : ?>
                    <?php if ($coaching['coaching_current_status'] == COACHING_STARTED) : ?>
                        <li>
                            Join URL:
                            <a href="<?= $coaching['coaching_join_url'] ?>" target="_blank">
                                <?= $coaching['coaching_join_url'] ?>
                            </a>
                        </li>
                    <?php else : ?>
                        Note: <span class="text-danger"><?= __('Join URL will be available once coaching has been started') ?>.</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php $json_decoded = json_decode($coaching['coaching_response']); ?>

                <li>Created on: <?= date('Y-m-d H:i:s', strtotime($coaching['coaching_createdon'])) ?></li>

                <?php if ($this->model_signup->hasRole(ROLE_0) || ($user_application && $user_application['coaching_application_status'] == STATUS_ACTIVE)) : ?>
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
                <?php endif; ?>

                <hr />

                <a href="<?= l('dashboard/coaching'); ?>" target="_blank">See all coachings.</a>

            </ul>
        </div>

        <?php if (!$this->model_signup->hasRole(ROLE_0)) : ?>
            <div class="col-6" id="requestDiv">
                <h4>Request participation</h4>
                <?php if (!$this->model_coaching_application->userApplicationExists($this->userid, $coaching['coaching_id'])) : ?>
                    <form action="javascript:;" method="POST" id="requestForm" novalidate>
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
                            <?php if (isset($coaching_cost) && $coaching_cost > 0) : ?>
                                <div class="col-12 my-2">
                                    <label>Payment Method <span class="text-danger">*</span></label>
                                    <label class="radio">
                                        <input class="radio__input" type="radio" name="coaching_application[coaching_application_merchant]" value="<?= STRIPE ?>" checked required />
                                        <span
                                            class="radio__label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="120" height="60" fill-rule="evenodd" fill="#6772e5">
                                                <script xmlns="" />
                                                <path d="M101.547 30.94c0-5.885-2.85-10.53-8.3-10.53-5.47 0-8.782 4.644-8.782 10.483 0 6.92 3.908 10.414 9.517 10.414 2.736 0 4.805-.62 6.368-1.494v-4.598c-1.563.782-3.356 1.264-5.632 1.264-2.23 0-4.207-.782-4.46-3.494h11.24c0-.3.046-1.494.046-2.046zM90.2 28.757c0-2.598 1.586-3.678 3.035-3.678 1.402 0 2.897 1.08 2.897 3.678zm-14.597-8.345c-2.253 0-3.7 1.057-4.506 1.793l-.3-1.425H65.73v26.805l5.747-1.218.023-6.506c.828.598 2.046 1.448 4.07 1.448 4.115 0 7.862-3.3 7.862-10.598-.023-6.667-3.816-10.3-7.84-10.3zm-1.38 15.84c-1.356 0-2.16-.483-2.713-1.08l-.023-8.53c.598-.667 1.425-1.126 2.736-1.126 2.092 0 3.54 2.345 3.54 5.356 0 3.08-1.425 5.38-3.54 5.38zm-16.4-17.196l5.77-1.24V13.15l-5.77 1.218zm0 1.747h5.77v20.115h-5.77zm-6.185 1.7l-.368-1.7h-4.966V40.92h5.747V27.286c1.356-1.77 3.655-1.448 4.368-1.195v-5.287c-.736-.276-3.425-.782-4.782 1.7zm-11.494-6.7L34.535 17l-.023 18.414c0 3.402 2.552 5.908 5.954 5.908 1.885 0 3.264-.345 4.023-.76v-4.667c-.736.3-4.368 1.356-4.368-2.046V25.7h4.368v-4.897h-4.37zm-15.54 10.828c0-.897.736-1.24 1.954-1.24a12.85 12.85 0 0 1 5.7 1.47V21.47c-1.908-.76-3.793-1.057-5.7-1.057-4.667 0-7.77 2.437-7.77 6.506 0 6.345 8.736 5.333 8.736 8.07 0 1.057-.92 1.402-2.207 1.402-1.908 0-4.345-.782-6.276-1.84v5.47c2.138.92 4.3 1.3 6.276 1.3 4.782 0 8.07-2.368 8.07-6.483-.023-6.85-8.782-5.632-8.782-8.207z" />
                                            </svg>
                                        </span>
                                    </label>
                                    <label class="radio mt-2">
                                        <input class="radio__input" type="radio" name="coaching_application[coaching_application_merchant]" value="<?= PAYPAL ?>" required />
                                        <span class="radio__label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="120" height="60">
                                                <script xmlns="" />
                                                <path d="M58.505 27.333c0 1.64-.675 2.96-2.034 3.964S53.215 32.8 50.78 32.8h-1.35l-1.037 4.478H44.83l3.176-13.756h4.944c.9 0 1.68.064 2.348.185s1.246.338 1.73.635a3.08 3.08 0 0 1 1.101 1.174c.25.498.378 1.1.378 1.817zm-3.787.346c0-.54-.193-.933-.587-1.2s-.965-.386-1.73-.386h-1.43l-.95 4.116h1.28c1.077 0 1.92-.217 2.516-.66.603-.442.9-1.07.9-1.88zm10.34 8.506l-.756.5c-.297.193-.57.346-.82.458a6.17 6.17 0 0 1-.973.322c-.306.072-.724.113-1.254.113-.86 0-1.568-.24-2.114-.724s-.82-1.118-.82-1.9c0-.82.193-1.5.58-2.082s.965-1.03 1.73-1.367c.716-.322 1.568-.555 2.55-.7l3.208-.314.04-.177c.024-.08.032-.177.032-.28a.93.93 0 0 0-.6-.917c-.4-.177-1.013-.265-1.817-.265-.547 0-1.15.088-1.817.273l-1.487.458h-.306l.5-2.46c.386-.096 1-.2 1.8-.33s1.624-.185 2.428-.185c1.624 0 2.822.2 3.578.635.764.426 1.142 1.085 1.142 1.978a4.1 4.1 0 0 1-.032.482 3.62 3.62 0 0 1-.088.515l-1.624 7.06h-3.304zm.884-3.795l-1.672.177a6.27 6.27 0 0 0-1.327.297c-.378.137-.667.33-.86.57-.2.25-.297.57-.297.98 0 .354.13.6.394.756s.643.217 1.15.217c.33 0 .675-.072 1.053-.225a5.11 5.11 0 0 0 1.053-.58zm9.038 8.7h-3.66l2.645-4.012-1.704-10.162h3.4l.9 6.794 3.947-6.794h3.513z" fill="#123984" />
                                                <path d="M97.32 27.333c0 1.64-.675 2.96-2.034 3.964S92.03 32.8 89.595 32.8h-1.35l-1.037 4.478h-3.562l3.168-13.748h4.944c.9 0 1.68.064 2.348.185s1.246.338 1.73.635a3.08 3.08 0 0 1 1.101 1.174c.257.5.386 1.093.386 1.8zm-3.795.346c0-.54-.193-.933-.587-1.2s-.965-.386-1.73-.386h-1.43l-.95 4.116h1.278c1.077 0 1.92-.217 2.516-.66.603-.442.9-1.07.9-1.88zm10.348 8.506l-.756.5c-.297.193-.57.346-.82.458a6.17 6.17 0 0 1-.973.322c-.306.072-.724.113-1.254.113-.86 0-1.568-.24-2.114-.724s-.82-1.118-.82-1.9c0-.82.193-1.5.58-2.082s.965-1.03 1.73-1.367c.716-.322 1.568-.555 2.55-.7l3.208-.314.04-.177c.024-.08.032-.177.032-.28a.93.93 0 0 0-.6-.917c-.4-.177-1.013-.265-1.817-.265-.547 0-1.15.088-1.817.273l-1.487.458h-.306l.5-2.46c.386-.096 1-.2 1.8-.33s1.624-.185 2.428-.185c1.624 0 2.822.2 3.578.635.764.426 1.142 1.085 1.142 1.978a4.1 4.1 0 0 1-.032.482 3.62 3.62 0 0 1-.088.515l-1.624 7.06h-3.312zm.884-3.795l-1.672.177a6.27 6.27 0 0 0-1.327.297c-.378.137-.667.33-.86.57-.2.25-.297.57-.297.98 0 .354.13.6.394.756s.643.217 1.15.217c.33 0 .675-.072 1.053-.225a5.11 5.11 0 0 0 1.053-.58zm11.875-9.48l-3.32 14.375h-3.345l3.32-14.375z" fill="#009de2" />
                                                <g transform="matrix(.071673 0 0 .071673 -13.776444 3.122618)">
                                                    <path d="M754.6 298.1c0 54.6-22.7 98.7-68 132.2s-108.6 50.2-190 50.2h-45L417.1 630H298.2L408 171.2h161c30 0 56.1 2.1 78.5 6.2 22.3 4.1 41.6 11.2 57.8 21.3 16 10.1 28.3 23.2 36.7 39.3 8.4 16 12.6 36.1 12.6 60.1z" fill="#009cde" />
                                                    <path d="M421.1 634.9H292l112.2-468.7H569c30.1 0 56.8 2.1 79.3 6.2 22.8 4.2 42.8 11.6 59.5 21.9 16.7 10.5 29.7 24.4 38.5 41.2s13.2 37.8 13.2 62.5c0 56-23.5 101.9-70 136.2-46 34-110.9 51.2-193 51.2h-41.1zm-116.6-9.8h108.7l34.5-149.5h48.9c79.9 0 142.9-16.6 187.1-49.3 21.9-16.2 38.6-35.2 49.5-56.4s16.4-45.4 16.4-71.8c0-23.1-4.1-42.6-12.1-58-8-15.3-19.7-27.8-34.9-37.4-15.6-9.7-34.5-16.6-56.1-20.6-21.9-4-48-6.1-77.6-6.1h-157z" fill="#fff" />
                                                    <path d="M701.8 247c0 54.6-22.7 98.7-68 132.2s-108.6 50.2-190 50.2h-45l-34.5 149.5H245.4L351.3 120h164.9c30 0 56.1 2.1 78.5 6.2 22.3 4.1 41.6 11.2 57.8 21.3 16 10.1 28.3 23.2 36.7 39.3 8.4 16 12.6 36.1 12.6 60.2z" fill="#0f3572" />
                                                    <path d="M368.2 583.8h-129l108.1-468.7h168.9c30.1 0 56.8 2.1 79.3 6.2 22.8 4.2 42.8 11.6 59.5 21.9 16.7 10.5 29.7 24.4 38.5 41.2s13.2 37.8 13.2 62.5c0 56-23.5 101.9-70 136.2-46 34-110.9 51.2-193 51.2h-41.1zm-116.6-9.9h108.8l34.5-149.5h48.9c79.9 0 142.9-16.6 187.1-49.3 21.9-16.2 38.6-35.2 49.5-56.4s16.4-45.4 16.4-71.8c0-23.1-4.1-42.6-12.1-58-8-15.3-19.7-27.8-34.9-37.4-15.6-9.7-34.5-16.6-56.1-20.6-21.9-4-48-6.1-77.6-6.1h-161zm328-310.6c-.9 14-3.7 24.3-12.3 36.2-8.5 11.9-18.5 19.6-31.9 26-8.1 3.8-16.5 6.3-25.3 7.5s-19.3 1.9-31.6 1.9h-59.1l33.1-118.6h53.7c13.7 0 24.7.2 33 2.1 8.3 1.8 15.1 4.3 20.2 7.4 7.1 4.2 12.8 9.3 16.1 15.8 4.1 7.6 4.6 12.5 4.1 21.7z" fill="#fff" />
                                                </g>
                                            </svg>
                                        </span>
                                    </label>
                                    <label class="radio mt-2">
                                        <input class="radio__input" type="radio" name="coaching_application[coaching_application_merchant]" value="<?= PLAID ?>" required />
                                        <span class="radio__label">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="120" height="60" viewBox="0 0 130 50" preserveAspectRatio="xMidYMid meet">
                                                <g transform="translate(0.000000,48.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                                                    <path d="M113 462 c-34 -10 -64 -20 -68 -23 -3 -4 -14 -37 -25 -75 l-20 -69 25 -24 25 -25 -25 -26 -24 -25 19 -74 c10 -40 20 -74 22 -75 2 -2 34 -11 71 -21 68 -17 68 -17 91 4 28 27 41 27 60 -1 l16 -22 74 18 74 17 20 72 20 72 -26 28 -26 27 27 28 26 27 -16 65 c-9 36 -19 68 -23 73 -3 4 -37 16 -75 27 l-69 20 -26 -25 -26 -25 -24 25 c-13 14 -26 25 -29 24 -3 0 -34 -8 -68 -17z m77 -47 c11 -13 9 -20 -11 -41 l-24 -26 -28 34 -28 33 23 7 c38 10 55 9 68 -7z m156 5 l27 -11 -29 -30 -28 -29 -25 24 c-20 21 -22 27 -11 40 15 19 29 20 66 6z m-243 -68 l27 -28 -25 -24 c-23 -22 -27 -22 -41 -8 -11 11 -14 25 -9 52 4 20 10 36 14 36 4 0 19 -13 34 -28z m316 -40 c1 -32 -26 -39 -52 -14 l-21 22 29 30 30 31 6 -23 c4 -13 7 -33 8 -46z m-159 -17 l-24 -26 -25 25 -25 25 24 26 24 26 25 -25 25 -25 -24 -26z m-75 -31 c16 -25 16 -27 -4 -47 l-21 -21 -25 24 -25 24 22 23 c27 29 32 29 53 -3z m150 -43 l-25 -25 -22 22 -22 22 24 25 24 25 23 -22 24 -22 -26 -25z m-228 -33 l23 -22 -27 -28 c-15 -15 -30 -28 -34 -28 -15 0 -22 70 -9 85 16 20 19 19 47 -7z m148 -47 l-26 -26 -19 25 c-19 25 -19 25 2 48 l22 22 23 -22 24 -22 -26 -25z m154 55 c12 -14 6 -86 -7 -86 -3 0 -19 12 -34 27 l-28 27 22 23 c25 27 31 28 47 9z m-224 -86 c18 -20 18 -21 -1 -39 -15 -15 -25 -17 -53 -9 l-34 9 29 30 c33 35 36 35 59 9z m159 -9 l28 -29 -38 -11 c-31 -9 -41 -8 -56 6 -18 16 -18 17 4 40 27 29 29 29 62 -6z"/>
                                                    <path d="M550 240 c0 -83 1 -90 20 -90 16 0 20 7 20 30 0 26 4 30 28 30 35 0 62 27 62 62 0 38 -29 58 -83 58 l-47 0 0 -90z m85 30 c0 -8 -10 -16 -22 -18 -18 -3 -23 2 -23 18 0 16 5 21 23 18 12 -2 22 -10 22 -18z"/>
                                                    <path d="M710 240 l0 -90 50 0 c43 0 50 3 50 20 0 15 -7 20 -25 20 -24 0 -25 2 -25 70 0 68 -1 70 -25 70 -25 0 -25 -1 -25 -90z"/>
                                                    <path d="M866 253 c-16 -43 -31 -84 -33 -90 -3 -8 3 -13 16 -13 12 0 24 7 27 15 4 9 19 15 40 15 24 0 34 -5 34 -15 0 -9 10 -15 26 -15 29 0 29 0 -15 108 -39 96 -58 95 -95 -5z m63 -30 c1 -7 -6 -13 -14 -13 -17 0 -18 4 -8 39 6 23 7 23 14 5 4 -10 8 -25 8 -31z"/>
                                                    <path d="M1030 240 c0 -83 1 -90 20 -90 19 0 20 7 20 90 0 83 -1 90 -20 90 -19 0 -20 -7 -20 -90z"/>
                                                    <path d="M1110 240 l0 -90 46 0 c37 0 51 5 75 29 32 33 37 67 14 110 -17 34 -33 41 -91 41 l-44 0 0 -90z m101 24 c12 -33 -11 -74 -41 -74 -17 0 -20 7 -20 50 0 47 2 50 25 50 18 0 28 -7 36 -26z"/>
                                                </g>
                                            </svg>
                                        </span>
                                    </label>
                                </div>
                            <?php endif; ?>
                            <div class="col-12">
                                <button
                                    type="submit"
                                    id="requestBtn"
                                    class="btn btn-custom w-100"
                                    data-html="<?= (isset($coaching_cost) && $coaching_cost > 0) ? 'Pay for participation' : 'Request participation' ?>">
                                    <?= (isset($coaching_cost) && $coaching_cost > 0) ? 'Pay for participation' : 'Request participation' ?>
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    <?php if ($user_application) : ?>
                        <?php if ($user_application['coaching_application_status'] == 0) : ?>
                            <p>The request has been sent!</p>
                        <?php endif; ?>
                        <p>Status:
                            <strong>
                                <?php
                                switch ($user_application['coaching_application_status']) {
                                    case STATUS_INACTIVE:
                                        echo '<i class="text-warning">Pending</i>';
                                        break;
                                    case STATUS_ACTIVE:
                                        echo '<i class="text-success">Accepted</i>';
                                        break;
                                    case STATUS_REJECTED:
                                        echo '<i class="text-danger">Rejected</i>';
                                        break;
                                    default:
                                        echo '<i class="text-warning">Pending</i>';
                                }
                                ?>
                            </strong>
                        </p>
                        <?php if($coaching_cost > 0) : ?>
                            <p>Payment Status:
                                <strong>
                                    <?php
                                    switch ($user_application['coaching_application_payment_status']) {
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
                            <?php if ($user_application['coaching_application_payment_status'] == 0) : ?>
                                <?php
                                    $session = '';
                                    $session_url = '';
                                    switch($user_application['coaching_application_merchant']) {
                                        case STRIPE:
                                            $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
                                            try {
                                                $session = $stripe->checkout->sessions->retrieve($user_application['coaching_application_checkout_session_id']);
                                            } catch(\Exception $e) {}
                                            if ($session) {
                                                $session_url = $session->url;
                                            }
                                            break;
                                        case PAYPAL:
                                            $url = PAYPAL_URL . PAYPAL_CHECKOUT_URL . '/' . $user_application['coaching_application_checkout_session_id'];
                                            $headers = array();
                                            $headers[] = 'Content-Type: application/json';
                                            $headers[] = 'Authorization: Bearer ' . $paypalAccessToken;
                        
                                            $response = curlRequest($url, $headers);
                                            $session = json_decode($response);
                                            if($session && isset($session->status) && $session->status == 'PAYER_ACTION_REQUIRED') {
                                                foreach($session->links as $link) {
                                                    if($link->rel == 'payer-action') {
                                                        $session_url = $link->href;
                                                    }
                                                }
                                            }
                                            break;
                                    }
                                ?>
                                <?php if ($session_url) : ?>
                                    <a href="<?= $session_url ?>" class="btn btn-custom">Complete Payment</a>
                                <?php else: ?>
                                    <form action="javascipt:;" method="POST" id="newPaymentForm">
                                        <input type="hidden" name="_token" value="<?= $this->csrf_token ?>" />
                                        <input type="hidden" name="coaching_application[coaching_application_signup_id]" value="<?= $this->userid ?>" />
                                        <input type="hidden" name="coaching_application[coaching_application_coaching_id]" value="<?= $coaching['coaching_id'] ?>" />
                                        <label class="radio">
                                            <input class="radio__input" type="radio" name="coaching_application[coaching_application_merchant]" value="<?= STRIPE ?>" <?= $user_application['coaching_application_merchant'] == STRIPE ? 'checked' : '' ?> required />
                                            <span
                                                class="radio__label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="60" fill-rule="evenodd" fill="#6772e5">
                                                    <script xmlns="" />
                                                    <path d="M101.547 30.94c0-5.885-2.85-10.53-8.3-10.53-5.47 0-8.782 4.644-8.782 10.483 0 6.92 3.908 10.414 9.517 10.414 2.736 0 4.805-.62 6.368-1.494v-4.598c-1.563.782-3.356 1.264-5.632 1.264-2.23 0-4.207-.782-4.46-3.494h11.24c0-.3.046-1.494.046-2.046zM90.2 28.757c0-2.598 1.586-3.678 3.035-3.678 1.402 0 2.897 1.08 2.897 3.678zm-14.597-8.345c-2.253 0-3.7 1.057-4.506 1.793l-.3-1.425H65.73v26.805l5.747-1.218.023-6.506c.828.598 2.046 1.448 4.07 1.448 4.115 0 7.862-3.3 7.862-10.598-.023-6.667-3.816-10.3-7.84-10.3zm-1.38 15.84c-1.356 0-2.16-.483-2.713-1.08l-.023-8.53c.598-.667 1.425-1.126 2.736-1.126 2.092 0 3.54 2.345 3.54 5.356 0 3.08-1.425 5.38-3.54 5.38zm-16.4-17.196l5.77-1.24V13.15l-5.77 1.218zm0 1.747h5.77v20.115h-5.77zm-6.185 1.7l-.368-1.7h-4.966V40.92h5.747V27.286c1.356-1.77 3.655-1.448 4.368-1.195v-5.287c-.736-.276-3.425-.782-4.782 1.7zm-11.494-6.7L34.535 17l-.023 18.414c0 3.402 2.552 5.908 5.954 5.908 1.885 0 3.264-.345 4.023-.76v-4.667c-.736.3-4.368 1.356-4.368-2.046V25.7h4.368v-4.897h-4.37zm-15.54 10.828c0-.897.736-1.24 1.954-1.24a12.85 12.85 0 0 1 5.7 1.47V21.47c-1.908-.76-3.793-1.057-5.7-1.057-4.667 0-7.77 2.437-7.77 6.506 0 6.345 8.736 5.333 8.736 8.07 0 1.057-.92 1.402-2.207 1.402-1.908 0-4.345-.782-6.276-1.84v5.47c2.138.92 4.3 1.3 6.276 1.3 4.782 0 8.07-2.368 8.07-6.483-.023-6.85-8.782-5.632-8.782-8.207z" />
                                                </svg>
                                            </span>
                                        </label>
                                        <label class="radio my-2">
                                            <input class="radio__input" type="radio" name="coaching_application[coaching_application_merchant]" value="<?= PAYPAL ?>" <?= $user_application['coaching_application_merchant'] == PAYPAL ? 'checked' : '' ?> required />
                                            <span class="radio__label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="60">
                                                    <script xmlns="" />
                                                    <path d="M58.505 27.333c0 1.64-.675 2.96-2.034 3.964S53.215 32.8 50.78 32.8h-1.35l-1.037 4.478H44.83l3.176-13.756h4.944c.9 0 1.68.064 2.348.185s1.246.338 1.73.635a3.08 3.08 0 0 1 1.101 1.174c.25.498.378 1.1.378 1.817zm-3.787.346c0-.54-.193-.933-.587-1.2s-.965-.386-1.73-.386h-1.43l-.95 4.116h1.28c1.077 0 1.92-.217 2.516-.66.603-.442.9-1.07.9-1.88zm10.34 8.506l-.756.5c-.297.193-.57.346-.82.458a6.17 6.17 0 0 1-.973.322c-.306.072-.724.113-1.254.113-.86 0-1.568-.24-2.114-.724s-.82-1.118-.82-1.9c0-.82.193-1.5.58-2.082s.965-1.03 1.73-1.367c.716-.322 1.568-.555 2.55-.7l3.208-.314.04-.177c.024-.08.032-.177.032-.28a.93.93 0 0 0-.6-.917c-.4-.177-1.013-.265-1.817-.265-.547 0-1.15.088-1.817.273l-1.487.458h-.306l.5-2.46c.386-.096 1-.2 1.8-.33s1.624-.185 2.428-.185c1.624 0 2.822.2 3.578.635.764.426 1.142 1.085 1.142 1.978a4.1 4.1 0 0 1-.032.482 3.62 3.62 0 0 1-.088.515l-1.624 7.06h-3.304zm.884-3.795l-1.672.177a6.27 6.27 0 0 0-1.327.297c-.378.137-.667.33-.86.57-.2.25-.297.57-.297.98 0 .354.13.6.394.756s.643.217 1.15.217c.33 0 .675-.072 1.053-.225a5.11 5.11 0 0 0 1.053-.58zm9.038 8.7h-3.66l2.645-4.012-1.704-10.162h3.4l.9 6.794 3.947-6.794h3.513z" fill="#123984" />
                                                    <path d="M97.32 27.333c0 1.64-.675 2.96-2.034 3.964S92.03 32.8 89.595 32.8h-1.35l-1.037 4.478h-3.562l3.168-13.748h4.944c.9 0 1.68.064 2.348.185s1.246.338 1.73.635a3.08 3.08 0 0 1 1.101 1.174c.257.5.386 1.093.386 1.8zm-3.795.346c0-.54-.193-.933-.587-1.2s-.965-.386-1.73-.386h-1.43l-.95 4.116h1.278c1.077 0 1.92-.217 2.516-.66.603-.442.9-1.07.9-1.88zm10.348 8.506l-.756.5c-.297.193-.57.346-.82.458a6.17 6.17 0 0 1-.973.322c-.306.072-.724.113-1.254.113-.86 0-1.568-.24-2.114-.724s-.82-1.118-.82-1.9c0-.82.193-1.5.58-2.082s.965-1.03 1.73-1.367c.716-.322 1.568-.555 2.55-.7l3.208-.314.04-.177c.024-.08.032-.177.032-.28a.93.93 0 0 0-.6-.917c-.4-.177-1.013-.265-1.817-.265-.547 0-1.15.088-1.817.273l-1.487.458h-.306l.5-2.46c.386-.096 1-.2 1.8-.33s1.624-.185 2.428-.185c1.624 0 2.822.2 3.578.635.764.426 1.142 1.085 1.142 1.978a4.1 4.1 0 0 1-.032.482 3.62 3.62 0 0 1-.088.515l-1.624 7.06h-3.312zm.884-3.795l-1.672.177a6.27 6.27 0 0 0-1.327.297c-.378.137-.667.33-.86.57-.2.25-.297.57-.297.98 0 .354.13.6.394.756s.643.217 1.15.217c.33 0 .675-.072 1.053-.225a5.11 5.11 0 0 0 1.053-.58zm11.875-9.48l-3.32 14.375h-3.345l3.32-14.375z" fill="#009de2" />
                                                    <g transform="matrix(.071673 0 0 .071673 -13.776444 3.122618)">
                                                        <path d="M754.6 298.1c0 54.6-22.7 98.7-68 132.2s-108.6 50.2-190 50.2h-45L417.1 630H298.2L408 171.2h161c30 0 56.1 2.1 78.5 6.2 22.3 4.1 41.6 11.2 57.8 21.3 16 10.1 28.3 23.2 36.7 39.3 8.4 16 12.6 36.1 12.6 60.1z" fill="#009cde" />
                                                        <path d="M421.1 634.9H292l112.2-468.7H569c30.1 0 56.8 2.1 79.3 6.2 22.8 4.2 42.8 11.6 59.5 21.9 16.7 10.5 29.7 24.4 38.5 41.2s13.2 37.8 13.2 62.5c0 56-23.5 101.9-70 136.2-46 34-110.9 51.2-193 51.2h-41.1zm-116.6-9.8h108.7l34.5-149.5h48.9c79.9 0 142.9-16.6 187.1-49.3 21.9-16.2 38.6-35.2 49.5-56.4s16.4-45.4 16.4-71.8c0-23.1-4.1-42.6-12.1-58-8-15.3-19.7-27.8-34.9-37.4-15.6-9.7-34.5-16.6-56.1-20.6-21.9-4-48-6.1-77.6-6.1h-157z" fill="#fff" />
                                                        <path d="M701.8 247c0 54.6-22.7 98.7-68 132.2s-108.6 50.2-190 50.2h-45l-34.5 149.5H245.4L351.3 120h164.9c30 0 56.1 2.1 78.5 6.2 22.3 4.1 41.6 11.2 57.8 21.3 16 10.1 28.3 23.2 36.7 39.3 8.4 16 12.6 36.1 12.6 60.2z" fill="#0f3572" />
                                                        <path d="M368.2 583.8h-129l108.1-468.7h168.9c30.1 0 56.8 2.1 79.3 6.2 22.8 4.2 42.8 11.6 59.5 21.9 16.7 10.5 29.7 24.4 38.5 41.2s13.2 37.8 13.2 62.5c0 56-23.5 101.9-70 136.2-46 34-110.9 51.2-193 51.2h-41.1zm-116.6-9.9h108.8l34.5-149.5h48.9c79.9 0 142.9-16.6 187.1-49.3 21.9-16.2 38.6-35.2 49.5-56.4s16.4-45.4 16.4-71.8c0-23.1-4.1-42.6-12.1-58-8-15.3-19.7-27.8-34.9-37.4-15.6-9.7-34.5-16.6-56.1-20.6-21.9-4-48-6.1-77.6-6.1h-161zm328-310.6c-.9 14-3.7 24.3-12.3 36.2-8.5 11.9-18.5 19.6-31.9 26-8.1 3.8-16.5 6.3-25.3 7.5s-19.3 1.9-31.6 1.9h-59.1l33.1-118.6h53.7c13.7 0 24.7.2 33 2.1 8.3 1.8 15.1 4.3 20.2 7.4 7.1 4.2 12.8 9.3 16.1 15.8 4.1 7.6 4.6 12.5 4.1 21.7z" fill="#fff" />
                                                    </g>
                                                </svg>
                                            </span>
                                        </label>
                                        <label class="radio my-2">
                                            <input class="radio__input" type="radio" name="coaching_application[coaching_application_merchant]" value="<?= PLAID ?>" <?= $user_application['coaching_application_merchant'] == PLAID ? 'checked' : '' ?> required />
                                            <span class="radio__label">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="120" height="60" viewBox="0 0 130 50" preserveAspectRatio="xMidYMid meet">
                                                    <g transform="translate(0.000000,48.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                                                        <path d="M113 462 c-34 -10 -64 -20 -68 -23 -3 -4 -14 -37 -25 -75 l-20 -69 25 -24 25 -25 -25 -26 -24 -25 19 -74 c10 -40 20 -74 22 -75 2 -2 34 -11 71 -21 68 -17 68 -17 91 4 28 27 41 27 60 -1 l16 -22 74 18 74 17 20 72 20 72 -26 28 -26 27 27 28 26 27 -16 65 c-9 36 -19 68 -23 73 -3 4 -37 16 -75 27 l-69 20 -26 -25 -26 -25 -24 25 c-13 14 -26 25 -29 24 -3 0 -34 -8 -68 -17z m77 -47 c11 -13 9 -20 -11 -41 l-24 -26 -28 34 -28 33 23 7 c38 10 55 9 68 -7z m156 5 l27 -11 -29 -30 -28 -29 -25 24 c-20 21 -22 27 -11 40 15 19 29 20 66 6z m-243 -68 l27 -28 -25 -24 c-23 -22 -27 -22 -41 -8 -11 11 -14 25 -9 52 4 20 10 36 14 36 4 0 19 -13 34 -28z m316 -40 c1 -32 -26 -39 -52 -14 l-21 22 29 30 30 31 6 -23 c4 -13 7 -33 8 -46z m-159 -17 l-24 -26 -25 25 -25 25 24 26 24 26 25 -25 25 -25 -24 -26z m-75 -31 c16 -25 16 -27 -4 -47 l-21 -21 -25 24 -25 24 22 23 c27 29 32 29 53 -3z m150 -43 l-25 -25 -22 22 -22 22 24 25 24 25 23 -22 24 -22 -26 -25z m-228 -33 l23 -22 -27 -28 c-15 -15 -30 -28 -34 -28 -15 0 -22 70 -9 85 16 20 19 19 47 -7z m148 -47 l-26 -26 -19 25 c-19 25 -19 25 2 48 l22 22 23 -22 24 -22 -26 -25z m154 55 c12 -14 6 -86 -7 -86 -3 0 -19 12 -34 27 l-28 27 22 23 c25 27 31 28 47 9z m-224 -86 c18 -20 18 -21 -1 -39 -15 -15 -25 -17 -53 -9 l-34 9 29 30 c33 35 36 35 59 9z m159 -9 l28 -29 -38 -11 c-31 -9 -41 -8 -56 6 -18 16 -18 17 4 40 27 29 29 29 62 -6z"/>
                                                        <path d="M550 240 c0 -83 1 -90 20 -90 16 0 20 7 20 30 0 26 4 30 28 30 35 0 62 27 62 62 0 38 -29 58 -83 58 l-47 0 0 -90z m85 30 c0 -8 -10 -16 -22 -18 -18 -3 -23 2 -23 18 0 16 5 21 23 18 12 -2 22 -10 22 -18z"/>
                                                        <path d="M710 240 l0 -90 50 0 c43 0 50 3 50 20 0 15 -7 20 -25 20 -24 0 -25 2 -25 70 0 68 -1 70 -25 70 -25 0 -25 -1 -25 -90z"/>
                                                        <path d="M866 253 c-16 -43 -31 -84 -33 -90 -3 -8 3 -13 16 -13 12 0 24 7 27 15 4 9 19 15 40 15 24 0 34 -5 34 -15 0 -9 10 -15 26 -15 29 0 29 0 -15 108 -39 96 -58 95 -95 -5z m63 -30 c1 -7 -6 -13 -14 -13 -17 0 -18 4 -8 39 6 23 7 23 14 5 4 -10 8 -25 8 -31z"/>
                                                        <path d="M1030 240 c0 -83 1 -90 20 -90 19 0 20 7 20 90 0 83 -1 90 -20 90 -19 0 -20 -7 -20 -90z"/>
                                                        <path d="M1110 240 l0 -90 46 0 c37 0 51 5 75 29 32 33 37 67 14 110 -17 34 -33 41 -91 41 l-44 0 0 -90z m101 24 c12 -33 -11 -74 -41 -74 -17 0 -20 7 -20 50 0 47 2 50 25 50 18 0 28 -7 36 -26z"/>
                                                    </g>
                                                </svg>
                                            </span>
                                        </label>
                                        <button type="submit" class="btn btn-custom w-100" id="newPaymentFormBtn" data-html="Request new payment link">Request new payment link</button>
                                    </form>
                                <?php endif; ?>
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

<script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENTID ?>&currency=USD&intent=authorize&disable-funding=paylater,credit,card"></script>
<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>

<script>
    $(document).ready(function() {
        $('#requestForm').on('submit', function(event) {

            event.preventDefault()
            if (!$(this)[0].checkValidity()) {
                event.stopPropagation()
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: '<?= l('dashboard/coaching/saveApplication') ?>',
                    type: "POST",
                    data: $('#requestForm').serialize(),
                    async: true,
                    dataType: 'json',
                    success: function(response) {
                        resolve(response)
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $('#requestBtn').attr('disabled', true);
                        $('#requestBtn').html('Requesting ...');
                    },
                    complete: function() {
                        $('#requestBtn').attr('disabled', false);
                        $('#requestBtn').html($('#requestBtn').data('html'));
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        $('#requestBtn').html('Sent');
                        if(response.merchant == '<?= PLAID ?>') {
                            const handler = Plaid.create({
                                token: response.link_token,
                                onSuccess: (public_token, metadata) => {
                                    var data = {
                                        '_token': $('meta[name=csrf-token]').attr("content"),
                                        'link_token': response.link_token,
                                        'public_token': public_token,
                                        'link_session_id' : metadata.link_session_id,
                                        'account_id': metadata.account_id,
                                        'connection': 1,
                                        'coaching_id': <?= $coaching['coaching_id'] ?>,
                                    }
                                    var url = '<?= l('dashboard/coaching/processPlaidTransfer') ?>';

                                    new Promise((resolve, reject) => {
                                        $.ajax({
                                            type: "POST",
                                            url: url,
                                            data: data,
                                            dataType: 'JSON',
                                            async: true,
                                            success: function(response) {
                                                resolve(response)
                                            },
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                                            }
                                        });
                                    }).then(
                                        function(response) {
                                            if (response.status) {
                                                $.dialog({
                                                    backgroundDismiss: true,
                                                    title: '<?= __("Success!") ?>',
                                                    content: response.txt,
                                                    onClose: function() {
                                                        window.location.reload()
                                                    }
                                                });
                                            } else {
                                                $.dialog({
                                                    backgroundDismiss: true,
                                                    title: '<?= __("Error!") ?>',
                                                    content: response.txt,
                                                    onClose: function() {
                                                        // window.location.reload()
                                                    }
                                                });
                                            }
                                        }
                                    )
                                },
                                onLoad: () => {},
                                onExit: (err, metadata) => {
                                    console.log(err)
                                    if (err) {
                                        $.dialog({
                                            backgroundDismiss: true,
                                            title: '<?= __("Error!") ?>',
                                            content: err.error_message,
                                            onClose: function() {
                                                window.location.reload()
                                            }
                                        });
                                    }
                                    console.log(metadata)
                                    // Save data from the onExit handler
                                    // handler.report({
                                    //     error: error,
                                    //     institution: metadata.institution,
                                    //     link_session_id: metadata.link_session_id,
                                    //     plaid_request_id: metadata.request_id,
                                    //     status: metadata.status,
                                    // });
                                },
                                onEvent: (eventName, metadata) => {
                                    if(eventName == 'EXIT') {
                                    }
                                },
                                // required for OAuth; if not using OAuth, set to null or omit:
                                // receivedRedirectUri: window.location.href,
                            });
                            // Open Link
                            handler.open();
                        } else {
                            setTimeout(function() {
                                $('#requestDiv').html("<h4>Request participation</h4><p>The request has been sent!</p>");
                                if (response.session_url) {
                                    location.href = response.session_url;
                                }
                            }, 1000);
                        }
                    } else {
                        toastr.error(response.txt);
                    }
                }
            );
        });

        $('#newPaymentForm').on('submit', function(e) {
            event.preventDefault()

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: '<?= l('dashboard/coaching/saveApplication') ?>',
                    type: "POST",
                    data: $('#newPaymentForm').serialize(),
                    async: true,
                    dataType: 'json',
                    success: function(response) {
                        resolve(response)
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $('#newPaymentFormBtn').attr('disabled', true);
                        $('#newPaymentFormBtn').html('Requesting ...');
                    },
                    complete: function() {
                        $('#newPaymentFormBtn').attr('disabled', false);
                        $('#newPaymentFormBtn').html($('#newPaymentFormBtn').data('html'));
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        $('#newPaymentFormBtn').html('Sent');
                        if(response.merchant == '<?= PLAID ?>') {
                            const handler = Plaid.create({
                                token: response.link_token,
                                onSuccess: (public_token, metadata) => {
                                    var data = {
                                        '_token': $('meta[name=csrf-token]').attr("content"),
                                        'public_token': public_token,
                                        'link_session_id' : metadata.link_session_id,
                                        'account_id': metadata.account_id,
                                        'connection': 1,
                                        'coaching_id': <?= $coaching['coaching_id'] ?>,
                                    }
                                    var url = '<?= l('dashboard/coaching/processPlaidTransfer') ?>';

                                    new Promise((resolve, reject) => {
                                        $.ajax({
                                            type: "POST",
                                            url: url,
                                            data: data,
                                            dataType: 'JSON',
                                            async: true,
                                            success: function(response) {
                                                resolve(response)
                                            },
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                                            }
                                        });
                                    }).then(
                                        function(response) {
                                            if (response.status) {
                                                $.dialog({
                                                    backgroundDismiss: true,
                                                    title: '<?= __("Success!") ?>',
                                                    content: response.txt,
                                                    onClose: function() {
                                                        window.location.reload()
                                                    }
                                                });
                                            } else {
                                                $.dialog({
                                                    backgroundDismiss: true,
                                                    title: '<?= __("Error!") ?>',
                                                    content: response.txt,
                                                    onClose: function() {
                                                        // window.location.reload()
                                                    }
                                                });
                                            }
                                        }
                                    )
                                },
                                onLoad: () => {},
                                onExit: (err, metadata) => {
                                    console.log(err)
                                    if (err) {
                                        $.dialog({
                                            backgroundDismiss: true,
                                            title: '<?= __("Error!") ?>',
                                            content: err.error_message,
                                            onClose: function() {
                                                window.location.reload()
                                            }
                                        });
                                    }
                                    console.log(metadata)
                                    // Save data from the onExit handler
                                    // handler.report({
                                    //     error: error,
                                    //     institution: metadata.institution,
                                    //     link_session_id: metadata.link_session_id,
                                    //     plaid_request_id: metadata.request_id,
                                    //     status: metadata.status,
                                    // });
                                },
                                onEvent: (eventName, metadata) => {
                                    if(eventName == 'EXIT') {
                                    }
                                },
                                // required for OAuth; if not using OAuth, set to null or omit:
                                // receivedRedirectUri: window.location.href,
                            });
                            // Open Link
                            handler.open();
                        } else {
                            setTimeout(function() {
                                if (response.session_url) {
                                    location.href = response.session_url;
                                }
                            }, 1000);
                        }
                    } else {
                        toastr.error(response.txt);
                    }
                }
            );
        });
    });
</script>