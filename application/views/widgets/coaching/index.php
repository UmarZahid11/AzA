<section class="booking-sec">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</section>

<script>
    var calendar;

    async function datetime(timezone) {
        return await fetch("https://worldtimeapi.org/api/timezone/" + timezone)
            .then(response => response.json())
            .then(data => (data.datetime));
    }

    // coaching calendar
    document.addEventListener('DOMContentLoaded', function() {

        if (document.getElementById('calendar') != null)
        var calendarEl = document.getElementById('calendar');

        var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        datetime(timezone).then(
            function(now) {
                try {
                    var COACHING_PENDING = '<?= COACHING_PENDING ?>'
                    var COACHING_STARTED = '<?= COACHING_STARTED ?>'
                    var COACHING_ENDED = '<?= COACHING_ENDED ?>'

                    calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: '<?= ($this->session->has_userdata('site_lang_code') && $this->session->userdata('site_lang_code')) ? $this->session->userdata('site_lang_code') : 'en' ?>',
                        selectable: true,
                        eventColor: '#to',
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        selectMirror: true,
                        dateClick: function(info) {},
                        unselectAuto: true,
                        select: function(info) {},
                        eventClick: function(info) {
                            var eventObj = info.event;

                            var current_email = '<?= $this->user_data['signup_email'] ?>';
                            var zoomType = eventObj.extendedProps.type;
                            var url = eventObj.extendedProps.coaching_url;

                            $.dialog({
                                backgroundDismiss: true,
                                title: zoomType.charAt(0).toUpperCase() + zoomType.slice(1) + ' Detail',
                                content: 'Topic: ' + eventObj.title + '.<br/>' +
                                    'From: <b>' + moment(eventObj.startStr).format('MMMM Do YYYY, h:mm a') + '</b><br/>' +
                                    'To: ' + '<b>' + moment(eventObj.endStr).format('MMMM Do YYYY, h:mm a') + '</b>' +
                                    (
                                        (eventObj.extendedProps.start_url && eventObj.extendedProps.join_url) ?
                                        (
                                            (eventObj.extendedProps.requester == current_email) ?
                                            (
                                                (eventObj.extendedProps.current_status == COACHING_PENDING) ?
                                                '<br/><a class="btn btn-custom" target="_blank" href="' + eventObj.extendedProps.start_url + '">Start coaching</a>' : ''
                                            ) :
                                            (
                                                (eventObj.extendedProps.current_status == COACHING_STARTED) ?
                                                '<br/><a target="_blank" class="btn btn-custom" href="' + eventObj.extendedProps.join_url + '">Join coaching</a>' : ''
                                            )
                                        ) :
                                        ('')
                                    ) +
                                    (url ? ('<br/><a target="_blank" class="btn btn-custom mt-2" href="' + url + '">Details</a>') : '')
                            });
                        },
                        selectOverlap: function(event) {
                            return !event.block;
                        },
                        eventColor: '#8204aa',
                        // editable: true,
                        events: <?= (isset($calendar_events) && count($calendar_events) > 0) ? json_encode($calendar_events) : "[]" ?>,
                        eventSourceFailure: function(errorObj) {
                            console.log(errorObj)
                        },
                    });

                    calendar.render();
                } catch (e) {
                    console.log(e)
                }
            }
        ).catch((e) => {
            $('#calendar').html('<p class="text-center text-danger">Calendar is currently unavailable!</p><hr />');
        });
    });
</script>