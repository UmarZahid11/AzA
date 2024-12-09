var Calendar = function () {
    //function to initiate Full CAlendar
    var runCalendar = function () {
        var $modal = $('#event-management');
        $('#event-categories div.event-category').each(function () {
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };
            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true, // will cause the event to go back to its
                revertDuration: 50 //  original position after the drag
            });
        });
        /* initialize the calendar
         -----------------------------------------------------------------*/
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var form = '';
        var event_select_date ="";

        //console.log(calendar_event);

        var calendar = $('#calendar').fullCalendar({
            buttonText: {
                prev: '<i class="fa fa-chevron-left"></i>',
                next: '<i class="fa fa-chevron-right"></i>'
            },
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: calendar_event
            /*events:[
                {
                 title: 'Meeting with Boss',
                 start: new Date(y, m, 1),
                 className: 'label-default'
                 }, {
                 title: 'Bootstrap Seminar',
                 start: new Date(y, m, d - 5),
                 end: new Date(y, m, d - 2),
                 className: 'label-teal'
                 }, {
                 title: 'Lunch with Nicole',
                 start: new Date(y, m, d - 3, 12, 0),
                 className: 'label-green',
                 allDay: false
                 }
            ]*/
            ,
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            drop: function (date, allDay) { // this function is called when something is dropped
                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('eventObject');
                var $categoryClass = $(this).attr('data-class');
                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);
                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;
                if ($categoryClass)
                    copiedEventObject['className'] = [$categoryClass];
                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
            },
            selectable: true,
            selectHelper: true,
            select: function (start, end, allDay) {
                $modal.modal({
                    backdrop: 'static'
                });
                //console.log(start);
                event_select_date = start.getFullYear() + '-' + (start.getMonth()+1) + '-' + start.getDate();
                //event_select_date = start;
                //console.log(event_select_date);
                form = $("<form id='event-form' action='" + base_url + "admin_event/save'></form>");
                form.append("<div class='row'></div>");
                form.find(".row").append("<input type='hidden' value='"+ event_select_date +"' name='cdate'>").append("<div class='col-md-6'><div class='form-group'><label class='control-label'>New Event Name</label><input class='form-control' placeholder='Insert Event Name' type=text name='title'/></div></div>").append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Category</label><select class='form-control' name='category'></select></div></div>").find("select[name='category']").append("<option value='label-default'>Work</option>").append("<option value='label-green'>Home</option>").append("<option value='label-purple'>Holidays</option>").append("<option value='label-teal'>Generic</option>").append("<option value='label-beige'>To Do</option>");
                $modal.find('.remove-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').click(function () {
                    form.submit();

                });
                $modal.find('form').on('submit', function () {

                    var obj = $("#event-form");
                    // Get form data
                    var data = obj.serialize();
                    // Get post url
                    var url = obj.attr('action');
                    // Submit action
                    var response = AjaxRequest.fire(url, data);
                    if(response.status){
                        AdminToastr.success(response.txt, 'Success' , {positionClass:"toast-top-full-width"} );
                        location.reload();
                    }
                    else{
                        //AdminToastr.error(response.txt, 'Error' , {positionClass:"toast-top-full-width"} );
                        AdminToastr.error(response.txt, "Error");
                        // Add return
                        return false;
                    }


                    title = form.find("input[name='title']").val();
                    $categoryClass = form.find("select[name='category'] option:checked").val();
                    if (title !== null) {
                        calendar.fullCalendar('renderEvent', {
                                //id: '1122',
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay,
                                className: $categoryClass
                            }, true // make the event "stick"
                        );
                    }
                    $modal.modal('hide');
                    return false;
                });
                calendar.fullCalendar('unselect');
            },

            // Updated event name
            eventClick: function (calEvent, jsEvent, view) {
                //console.log(calEvent);
                var form = $("<form id='event-update' action='" + base_url + "admin_event/update/" + calEvent.id +  "'></form>");
                form.append("<label>Change event name</label>");
                form.append("<div class='input-group'><input class='form-control' type=text value='" + calEvent.title + "' /><span class='input-group-btn'><button type='submit' class='btn btn-success'><i class='fa fa-check'></i> Save</button></span></div>");
                $modal.modal({
                    backdrop: 'static'
                });
                $modal.find('.remove-event').show().end().find('.save-event').hide().end().find('.modal-body').empty().prepend(form).end().find('.remove-event').unbind('click').click(function () {
                    calendar.fullCalendar('removeEvents', function (ev) {
                        if(ev._id == calEvent._id){
                            // Get form data
                            var data = {id: ev._id};
                            // Get post url
                            var url = base_url + "admin_event/delete";
                            // Submit action
                            var response = AjaxRequest.fire(url, data);
                            if(response.status){
                                //AdminToastr.success(response.txt, 'Success' , {positionClass:"toast-top-full-width"} );

                                //$modal.modal('hide');
                                location.reload();
                            }
                            else{
                                //AdminToastr.error(response.txt, 'Error' , {positionClass:"toast-top-full-width"} );
                                //AdminToastr.error(response.txt, "Error");
                            }
                            return (ev._id == calEvent._id);
                        }
                        //return (ev._id == calEvent._id);
                    });
                    $modal.modal('hide');
                });
                $modal.find('form').on('submit', function () {
                    calEvent.title = form.find("input[type=text]").val();
                    calendar.fullCalendar('updateEvent', calEvent);

                    var obj = $("#event-update");
                    // Get form data
                    var data = {"admin_event[admin_event_name]": calEvent.title};
                    // Get post url
                    var url = obj.attr('action');
                    // Submit action
                    var response = AjaxRequest.fire(url, data);
                    if(response.status){
                        AdminToastr.success(response.txt, 'Success' , {positionClass:"toast-top-full-width"} );

                        $modal.modal('hide');
                        location.reload();
                    }
                    else{
                        //AdminToastr.error(response.txt, 'Error' , {positionClass:"toast-top-full-width"} );
                        AdminToastr.error(response.txt, "Error");
                    }

                    return false;
                });
            }
        });
    };
    return {
        init: function () {
            runCalendar();
        }
    };
}();