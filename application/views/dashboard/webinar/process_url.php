<div class="dashboard-content posted-theme text-center text-custom">
    <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    <input type="hidden" value="<?= $inserted; ?>" name="attendance_marked" />
</div>

<script>
    $(document).ready(function(){
        if($('input[name="attendance_marked"]').val()) {
            window.location.href = "<?php switch($attendance_type) { case ZOOM_TYPE_ORGANIZER: echo $webinar['webinar_start_url']; break; case ZOOM_TYPE_ATTENDEE: echo $webinar['webinar_join_url']; break; } ?>"
        } else {
            window.location.href = "<?= isset($webinar['webinar_id']) && $webinar['webinar_id'] ? (l('dashboard/webinar/detail/' . JWT::encode($webinar['webinar_id']))) : l('dashboard/webinar/listing') ?>"
        }
    })
</script>