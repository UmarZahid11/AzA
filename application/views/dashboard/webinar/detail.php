<div class="dashboard-content posted-theme">
    <div style="float:right;">
        <div class="side-large-text"></div>
    </div>
    <?php if ($webinar['webinar_userid'] == $this->userid) : ?>
        <a href="<?= l('dashboard/webinar/save/' . UPDATE . '/' . JWT::encode($webinar['webinar_id'])) ?>" class="btn btn-custom float-right"><i class="fa fa-edit text-white"></i> Edit</a>
    <?php endif; ?>
    <i class="fa fa-desktop"></i>
    <h4><?= __('Webinar details for') . ' ' . (isset($webinar['webinar_topic']) ? '"' . $webinar['webinar_topic'] . '"' : '') ?></h4>

    <hr />

    <ul>
        <?php if ($webinar['webinar_userid'] == $this->userid && $this->model_signup->hasPremiumPermission()) : ?>
            <?php if (in_array($webinar['webinar_current_status'], [ZOOM_WEBINAR_PENDING, ZOOM_WEBINAR_ENDED])) : ?>
                <li><a class="btn btn-custom" href="<?= l('dashboard/webinar/processURL/' . JWT::encode($webinar['webinar_id']) . '/' . ZOOM_TYPE_START_WEBINAR) ?>" data-id="<?= $webinar['webinar_id'] ?>">Start Webinar</a></li>
            <?php endif; ?>
            <li>UUID: <?= $webinar['webinar_uuid'] ?></li>
            <li>Webinar id: <?= $webinar['webinar_fetchid'] ?></li>
        <?php endif; ?>

        <?php if ($webinar['webinar_current_status'] == ZOOM_MEETING_STARTED) : ?>
            <li><a class="btn btn-custom" href="<?= l('dashboard/webinar/processURL/' . JWT::encode($webinar['webinar_id']) . '/' . ZOOM_TYPE_JOIN_WEBINAR) ?>" data-id="<?= $webinar['webinar_id'] ?>">Join Webinar</a></li>
        <?php else : ?>
            Note: <?= __('Join URL will be available once webinar has been started') ?>.
        <?php endif; ?>

        <li>Webinar Topic: <?= (isset($webinar['webinar_topic']) ? '"' . $webinar['webinar_topic'] . '"' : '') ?></li>
        <li>Password: <?= $webinar['webinar_password'] ?></li>
        <li>Duration: <?= $webinar['webinar_duration'] . ' min(s)' ?></li>

        <li>Start time: <?= $webinar['webinar_start_time'] . ' (' . date('d M, Y h:i a', strtotime($webinar['webinar_start_time'])) . ')' ?></li>
        <li>Timezone: <?= $webinar['webinar_timezone'] ?></li>

        <li>Agenda: <?= $webinar['webinar_agenda'] ?></li>

        <li>Contact person name: <?= $webinar['webinar_contact_name'] ?></li>
        <li>Contact person email: <a href="mailto:<?= $webinar['webinar_contact_email'] ?>"><?= $webinar['webinar_contact_email'] ?></a></li>

        <li>Webinar status: <b>
                <?php switch ($webinar['webinar_current_status']) {
                    case ZOOM_WEBINAR_PENDING:
                        echo 'Pending';
                        break;
                    case ZOOM_WEBINAR_STARTED:
                        echo 'Started';
                        break;
                    case ZOOM_WEBINAR_ENDED:
                        echo 'Ended';
                        break;
                }
                ?>
            </b>
        </li>

        <?php $json_decoded = json_decode($webinar['webinar_response']); ?>

        <li>Created on: <?= date('d M, Y h:i a', strtotime($webinar['webinar_createdon'])) ?></li>

        <hr />

        <li>Organizer: <?= $this->model_signup->profileName($webinar, false) ?></li>

    </ul>

</div>
