<div class="dashboard-content posted-theme">
    <div class="float-right d-flex">
        <span class="dropdown float-right">
            <button><i class="fa fa-bars"></i></button>
            <label>
                <input type="checkbox" />
                <ul>
                    <li>
                        <a href="<?= l('dashboard/meeting/listing/' . JWT::encode($meeting['meeting_reference_id']) . '/1/' . PER_PAGE . '/' . $meeting['meeting_reference_type']) ?>" data-toggle="tooltip" title="View all meetings with this applicant."><i class="fa fa-eye"></i> See all meetings</a>
                    </li>
                    <?php if ($meeting['meeting_signup_id'] == $this->userid) : ?>
                        <li>
                            <a href="<?= l('dashboard/meeting/save/' . UPDATE . '/' . JWT::encode($meeting['meeting_reference_id']) . '/' . $meeting['meeting_id']) ?>" data-toggle="tooltip" title="Edit this meeting"><i class="fa fa-edit"></i> Edit</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </label>
        </span>
    </div>
    <i class="fa fa-desktop"></i>
    <h4><?= __('Meeting details') . ' ' . (isset($meeting['meeting_topic']) ? 'for "' . $meeting['meeting_topic'] . '"' : '') ?></h4>

    <hr />

    <?php $applicant = $this->model_signup->find_by_pk($applicant_id); ?>
    <ul>
        <li>Meeting Topic: <?= (isset($meeting['meeting_topic']) ? '"' . $meeting['meeting_topic'] . '"' : NA) ?></li>
        <li>Password: <?= $meeting['meeting_password'] ?? NA ?></li>
        <li>Duration: <?= $meeting['meeting_duration'] . ' min(s)' ?></li>

        <li>Start time: <?= $meeting['meeting_start_time'] . ' (' . date('d M, Y h:i a', strtotime($meeting['meeting_start_time'])) . ')' ?></li>
        <li>Timezone: <?= $meeting['meeting_timezone'] ?></li>

        <li>Agenda: <?= $meeting['meeting_agenda'] ?? NA ?></li>

        <li>Contact person name: <?= $meeting['meeting_contact_name'] ?? NA ?></li>
        <li>Contact person email: <a href="mailto:<?= $meeting['meeting_contact_email'] ?>"><?= $meeting['meeting_contact_email'] ?? NA ?></a></li>

        <?php if ($meeting['meeting_signup_id'] == $this->userid) : ?>
            <li>UUID: <?= $meeting['meeting_uuid'] ?></li>
            <li>Meeting id: <?= $meeting['meeting_fetchid'] ?></li>
            <?php if ($meeting['meeting_current_status'] == ZOOM_MEETING_PENDING) : ?>
                <li>Start URL: <a href="<?= $meeting['meeting_start_url'] ?>" target="_blank"><?= $meeting['meeting_start_url'] ?></a></li>
            <?php endif; ?>
        <?php endif; ?>

        <li>Meeting status: <b>
                <?php switch ($meeting['meeting_current_status']) {
                    case ZOOM_MEETING_PENDING:
                        echo 'Pending';
                        break;
                    case ZOOM_MEETING_STARTED:
                        echo 'Started';
                        break;
                    case ZOOM_MEETING_ENDED:
                        echo 'Ended';
                        break;
                }
                ?>
            </b>
        </li>

        <?php if ($meeting['meeting_response2']) : ?>
            <li>Meeting interval:
                <?php $decoded_response2 = json_decode($meeting['meeting_response2']); ?>
                <?php echo (isset($decoded_response2->payload->object->start_time) ? date('d M, Y h:i a', strtotime($decoded_response2->payload->object->start_time)) : '') .
                    (isset($decoded_response2->payload->object->end_time) ? ' - ' . date('d M, Y h:i a', strtotime($decoded_response2->payload->object->end_time)) : '') ?>
            </li>
        <?php endif; ?>

        <?php if ($applicant['signup_id'] == $this->userid) : ?>
            <?php if ($meeting['meeting_current_status'] == ZOOM_MEETING_STARTED) : ?>
                <li>Join URL: <a href="<?= $meeting['meeting_join_url'] ?>" target="_blank"><?= $meeting['meeting_join_url'] ?></a></li>
            <?php else : ?>
                Note: <?= __('Join URL will be available once meeting has been started') ?>.
            <?php endif; ?>
        <?php endif; ?>

        <?php $json_decoded = json_decode($meeting['meeting_response']); ?>

        <li>Created on: <?= date('Y-m-d H:i:s', strtotime($meeting['meeting_createdon'])) ?></li>
        <li>Meeting recording:
            <?php
            if ($meeting_recording && is_object($meeting_recording) && property_exists($meeting_recording, 'recording_files')) {
                if (is_array($meeting_recording->recording_files) && count($meeting_recording->recording_files) > 0) {
                    foreach ($meeting_recording->recording_files as $key => $value) {
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
                if (property_exists($meeting_recording, 'password')) {
                    echo '<li>Password: ' . $meeting_recording->password . '</li>';
                }
                if (property_exists($meeting_recording, 'recording_play_passcode')) {
                    echo '<li>Recording play passcode : ' . $meeting_recording->recording_play_passcode . '</li>';
                }
            } else {
                if ($meeting_recording && is_object($meeting_recording) && property_exists($meeting_recording, 'message')) {
                    echo '<span class="text-danger">' . $meeting_recording->message . '</span>';
                } else {
                    echo '<span class="text-danger">This recording does not exist.</span>';
                }
            }
            ?>
        </li>

        <hr />

        <?php if ($meeting['meeting_reference_type'] == MEETING_REFERENCE_PRODUCT) : ?>
        <?php elseif ($meeting['meeting_reference_type'] == MEETING_REFERENCE_APPLICATION) : ?>

            <li>Organizer: <?= $this->model_signup->profileName($meeting, false) ?></li>
            <li>Job applicant: <?= $this->model_signup->profileName($applicant, false) ?></li>
            <li>See the applicant's detail <a href="<?= l('dashboard/profile/detail/') . JWT::encode($applicant['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $applicant['signup_type'] ?>" target="_blank"><?= __('here') ?>&nbsp;<i class="fa fa-external-link"></i></a></li>

            <hr />

            <li>Job title: <?= $meeting['job_title'] ?></li>
            <li>Job description: <?= $meeting['job_detail'] ?></li>
            <li>See the job detail <a href="<?= l('dashboard/job/detail/') . $meeting['job_slug'] ?>" target="_blank"><?= __('here') ?>&nbsp;<i class="fa fa-external-link"></i></a></li>

        <?php endif; ?>

        <a href="<?= l('dashboard/meeting/listing/' . JWT::encode($meeting['meeting_reference_id']) . '/0/' . PER_PAGE . '/' . $meeting['meeting_reference_type']) ?>" target="_blank">See all meetings for this job</a>

    </ul>
</div>