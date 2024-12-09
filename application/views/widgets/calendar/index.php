<a href="<?= l('dashboard/webinar/attendance') ?>" class="btn btn-custom float-right" data-toggle="tooltip" data-bs-placement="left" title="See my attended webinar(s) list.">Attended webinar(s)</a>
<h3 data-toggle="tooltip" data-bs-placement="left" title="<?= __("All user up and coming webinars will be shown below.") ?>"><?= __("Calendar") ?></h3>
<hr />
<a href="<?= l(TUTORIAL_PATH . CALENDAR_TUTORIAL) ?>" target="_blank"><i class="fa fa-film"></i> Calendar Tutorial</a>
<hr />
<div id="calendar"></div>

<!-- Button trigger modal -->
<a class="dynamoModalBtn d-none" data-fancybox data-animation-duration="700" data-src="#dynamoModal" href="javascript:;"></a>
<!-- Modal -->
<div class="grid">
    <div style="display: none; padding: 10px !important;" id="dynamoModal" class="animated-modal">
        <div class="modal-body dynamoModalBody">
            <ul class="nav nav-tabs">
                <li class="active w-100 text-center">
                    <a data-toggle="tab" href="#availabilityTab" id="availabilityTab">Mark Availability</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="availabilityTab" class="tab-pane fade show in active">
                    <form class="slotAvailabilityForm" action="javascript:;" method="POST" novalidate>
                        <input type="hidden" name="_token" />
                        <input type="hidden" name="type" value="SLOT_AVAILABLE" />
                        <input type="hidden" name="signup_availability_id" />

                        <!-- dynamo -->
                        <input type="hidden" name="start_time" />
                        <input type="hidden" name="end_time" />

                        <small class="fromtimeSlot"></small><br />
                        <small class="totimeSlot"></small>

                        <div class="form-group">
                            <label>Slot Title <span class="text-danger">*</span></label>
                            <input class="form-control font-13" placeholder="Enter slot title" name="slot_title" value="" maxlength="250" required />
                        </div>
                        <div class="form-group mt-2">
                            <button class="btn btn-custom" id="slotAvailabilityFormBtn" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var calendar;

    async function saveAvailabilitySlot() {
        var data = $('.slotAvailabilityForm').serialize()
        var url = base_url + 'dashboard/custom/save_availability_slot'

		return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: "json",
                success: function(response) {
                    resolve(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function() {
                    $('#slotAvailabilityFormBtn').attr('disabled', true)
                    $('#slotAvailabilityFormBtn').html('Saving ...')
                },
                complete: function() {
                    $('.fancybox-close-small').trigger('click')
                    $('#slotAvailabilityFormBtn').attr('disabled', false)
                    $('#slotAvailabilityFormBtn').html('Save')
                    $('input[name="slot_title"]').val('')
                }
            });
        })
    }

    async function deleteAvailabilitySlot(id) {
        var data = {
            'id': id,
        }
        var url = base_url + 'dashboard/custom/delete_availability_slot'
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: "json",
                success: function(response) {
                    resolve(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function() {
                    showLoader()
                },
                complete: function() {
                    hideLoader()
                }
            });
        })
    }
    
    function deleteSwal(id) {
        $('.jconfirm-closeIcon').click()
        swal({
            title: "<?= __('Delete this slot?') ?>",
            text: 'Remove your availability?',
            icon: "warning",
            className: "text-center",
            buttons: ["<?= __('No') ?>", "<?= __('Yes') ?>"],
        }).
        then((isConfirm) => {
            if (isConfirm) {
                deleteAvailabilitySlot(id).then(
                    function(response) {
                        if (response.status) {
                            swal({
                                title: "Success",
                                text: response.txt,
                                icon: "success",
                            }).then(() => {
                                calendar.getEventById(id).remove()
                            })
                        } else {
                            swal("Error", response.txt, "error");
                        }
                    }
                )
            } else {
                swal("Cancelled", "Action aborted", "error");
            }
        })
    }
    
    function editSwal(id, title, startStr, endStr) {
        $('.jconfirm-closeIcon').click()
        $('#availabilityTab').html('Update Availability')
        $('input[name=signup_availability_id]').val(id)
        $("input[name=start_time]").val(startStr)
        $("input[name=end_time]").val(endStr)
        $('.fromtimeSlot').html('From: ' + moment(startStr).format('MMMM D YYYY, h:mm a'))
        $('.totimeSlot').html('To: ' + moment(endStr).format('MMMM D YYYY, h:mm a'))
        try {
            title = title.split('-')[0]
            title = title.split('(')[0]
            $('input[name="slot_title"]').val(title)
        } catch(e) { console.log(e) }
        $('.dynamoModalBtn').trigger('click')
    }

    async function datetime(timezone) {
        return await fetch("https://worldtimeapi.org/api/timezone/" + timezone)
            .then(response => response.json())
            .then(data => (data.datetime));
    }
        
    // webinar calendar
    document.addEventListener('DOMContentLoaded', function() {

        if (document.getElementById('calendar') != null)
            var calendarEl = document.getElementById('calendar');

        // if(moment(info.endStr).format('MMMM Do YYYY, h:mm:ss a') <= moment(info.startStr).add(1, 'hour').format('MMMM Do YYYY, h:mm:ss a')) {
        
        var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        
        datetime(timezone).then(
            function(now) {
                try {
                    var ZOOM_WEBINAR_PENDING = '<?= ZOOM_WEBINAR_PENDING ?>'
                    var ZOOM_WEBINAR_STARTED = '<?= ZOOM_WEBINAR_STARTED ?>'
                    var ZOOM_WEBINAR_ENDED = '<?= ZOOM_WEBINAR_ENDED ?>'
        
                    calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: '<?= ($this->session->has_userdata('site_lang_code') && $this->session->userdata('site_lang_code')) ? $this->session->userdata('site_lang_code') : 'en' ?>',
                        selectable: (<?= ($this->userid === (int) $this->user_data['signup_id']) ? 'true' : 'false' ?>),
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
                        select: function(info) {
        
                            var startStr = moment(info.startStr)
                            var endStr = moment(info.endStr)
        
                            var now = moment(new Date()).format('MMMM Do YYYY, h:mm:ss a')
                            var startTime = startStr.format('MMMM Do YYYY, h:mm:ss a')
                            var endTime = endStr.format('MMMM Do YYYY, h:mm:ss a')
                            
                            var startMoment = moment(startStr, 'MMMM Do YYYY, h:mm:ss a')
                            var endMoment = moment(endStr, 'MMMM Do YYYY, h:mm:ss a')

                            // if(moment(new Date(), 'MMMM Do YYYY, h:mm:ss a').isBefore(moment(startStr, 'MMMM Do YYYY, h:mm:ss a'))) {
                            if(moment(now, 'MMMM Do YYYY, h:mm:ss a').isBefore(moment(startStr, 'MMMM Do YYYY, h:mm:ss a'))) {
                                if (info.view.type != 'dayGridMonth') {
                                    // if (moment(info.endStr).format('MMMM Do YYYY, h:mm:ss a') <= moment(info.startStr).add(1, 'hour').format('MMMM Do YYYY, h:mm:ss a')) {
                                    if(endMoment.diff(startMoment, 'minutes') <= 60) {
                                        $('.dynamoModal-dialog').show()
                                        $('#availabilityTab').html('Mark Availability')
                                        $("input[name=start_time]").val(info.startStr)
                                        $("input[name=end_time]").val(info.endStr)
                                        $('.fromtimeSlot').html('From: ' + moment(info.startStr).format('MMMM D YYYY, h:mm a'))
                                        $('.totimeSlot').html('To: ' + moment(info.endStr).format('MMMM D YYYY, h:mm a'))
                                        $('.dynamoModalBtn').trigger('click')
                                    } else {
                                        $.dialog({
                                            backgroundDismiss: true,
                                            title: 'Error',
                                            content: 'Maximum event duration is 1 hour.'
                                        });
                                    }
                                } else {
                                    $.dialog({
                                        backgroundDismiss: true,
                                        title: 'Error',
                                        content: 'Select week or day grid to mark your availability.'
                                    });
                                }
                            } else {
                                $.dialog({
                                    backgroundDismiss: true,
                                    title: 'Error',
                                    content: 'Select future time slot to mark your availability.'
                                });
                            }
                        },
                        eventClick: function(info) {
                            var eventObj = info.event;

                            var type = eventObj.backgroundColor == "#2c3e50" ? 'slot' : 'meeting'
                            var current_email = '<?= $this->user_data['signup_email'] ?>';
                            var zoomType = eventObj.extendedProps.type;
                            var url = '';
        
                            switch (zoomType) {
                                case '<?= CALENDAR_TYPE_MEETING ?>':
                                    var url = eventObj.extendedProps.meeting_url
                                    break;
                                case '<?= CALENDAR_TYPE_WEBINAR ?>':
                                    var url = eventObj.extendedProps.webinar_url
                                    break;
                            }
        
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
                                                (eventObj.extendedProps.current_status == ZOOM_WEBINAR_PENDING) ?
                                                '<br/><a class="btn btn-custom" target="_blank" href="' + eventObj.extendedProps.start_url + '">Start meeting</a>' : ''
                                            ) :
                                            (
                                                (eventObj.extendedProps.current_status == ZOOM_WEBINAR_STARTED) ?
                                                '<br/><a target="_blank" class="btn btn-custom" href="' + eventObj.extendedProps.join_url + '">Join meeting</a>' : ''
                                            )
                                        ) :
                                        ('')
                                    ) +
                                    (url ? ('<br/><a target="_blank" class="btn btn-custom mt-2" href="' + url + '">Details</a>') : '') +
                                    (
                                        ((eventObj.extendedProps.email == current_email) && (!eventObj.extendedProps.start_url)) ?
                                        (
                                            '<br /><button class="btn btn-custom" onclick="deleteSwal(' + eventObj.id + ')" type="button">Delete</button>' +
                                            '<button class="btn btn-custom" onclick="editSwal(' + eventObj.id + ',`' + eventObj.title + '`,`' + eventObj.startStr + '`,`' + eventObj.endStr + '`)" type="button">Edit</button>'
                                        ) : ('')
                                    )
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
        )

        $('body').on('submit', '.slotAvailabilityForm', function() {
            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            saveAvailabilitySlot().then(
                function(response) {
                    if (response.status) {
                        swal({
                            title: "Success",
                            text: response.txt,
                            icon: "success",
                        }).then(() => {
                            calendar.removeAllEvents()
                            calendar.addEventSource(response.slots)
                        })
                    } else {
                        $.dialog({
                            backgroundDismiss: true,
                            title: 'Error',
                            content: response.txt,
                        })
                    }
                }
            )
        })

        $('.deleteSwal').on('click', function() {
            var eventObj = $(this).data('object')
            deleteSwal(eventObj)
        })
    })
    
</script>    
