$(document).ready(function () {

    // var calendarEl = document.getElementById('calendar');
    var calendar_s = $('#calendar_s').fullCalendar({
        // themeSystem: 'lux',
        header: {
            left: 'prev,next today',
            center: 'title',
            // right: 'agendaWeek,timeGridWeek,timeGridDay,listMonth'
            right: 'month,agendaWeek,listWeek'
        },
        // defaultView: 'month',
        defaultView: 'agendaWeek',
        editable: false,
        selectable: false,
        allDaySlot: false,
        eventLimit: true,
        businessHours: true,
        minTime: "07:00:00",
        maxTime: "19:00:00",
        navLinks: true,
        weekNumbers: true,

        daysOfWeek: ['4'],

        // eventColor: '#2cab1b',
        eventColor: "<?php echo $event['color']; ?>",

        events: "dashboard_student.php?view=1",


        eventClick: function (event, jsEvent, view) {
            endtime = $.fullCalendar.moment(event.end).format('h:mm');
            starttime = $.fullCalendar.moment(event.start).format('dddd, MMMM Do YYYY, h:mm');
            var mywhen = starttime + ' - ' + endtime;
            $('#modalTitle').html(event.title);
            $('#modalWhen').text(mywhen);
            $('#eventID').val(event.id);
            $('#calendarModal').modal();
        },

        //header and other values
        select: function (start, end, jsEvent) {
            endtime = $.fullCalendar.moment(end).format('h:mm');
            starttime = $.fullCalendar.moment(start).format('dddd, MMMM Do YYYY, h:mm');
            var mywhen = starttime + ' - ' + endtime;
            start = moment(start).format();
            end = moment(end).format();
            $('#createEventModal #startTime').val(start);
            $('#createEventModal #endTime').val(end);
            $('#createEventModal #when').text(mywhen);
            $('#createEventModal').modal('toggle');
        },
        eventDrop: function (event, delta) {
            $.ajax({
                url: 'dashboard_lecturer.php',
                data: 'action=update&title=' + event.title + '&start=' + moment(event.start).format() + '&end=' + moment(event.end).format() + '&id=' + event.id,
                type: "POST",
                success: function (json) {
                    //alert(json);
                }
            });
        },
        eventResize: function (event) {
            $.ajax({
                url: 'dashboard_lecturer.php',
                data: 'action=update&title=' + event.title + '&start=' + moment(event.start).format() + '&end=' + moment(event.end).format() + '&id=' + event.id,
                type: "POST",
                success: function (json) {
                    //alert(json);
                }
            });
        }
    });

    $('#submitButton').on('click', function (e) {
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        doSubmit();
    });

    $('#deleteButton').on('click', function (e) {
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        doDelete();
    });

    $('#changeButton').on('click', function (e) {
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        doChangePass();
    });

    function doChangePass() {
        $("#changePasswordModal").modal('hide');
        var username = $('#username').val();
        var pass_old = $('#password_old').val();
        var pass_new = $('#password_new').val();

        $.ajax({
            url: 'dashboard_student.php',
            data: 'action=changepass&username=' + username + '&passold=' + pass_old + '&passnew=' + pass_new,
            type: "POST",
            success: function (json) {
                // console.log(json);
                $('#modelTitleStatus').text(json);
                $("#exampleModalSmall01").modal('show');

            }
            // ,
            // error: function () {

            // }
        });
    }

    function doDelete() {
        $("#calendarModal").modal('hide');
        var eventID = $('#eventID').val();
        $.ajax({
            url: 'dashboard_lecturer.php',
            data: 'action=delete&id=' + eventID,
            type: "POST",
            success: function (json) {
                if (json == 1)
                    $("#calendar_s").fullCalendar('removeEvents', eventID);
                else
                    return false;


            }
        });
    }
    function doSubmit() {
        $("#createEventModal").modal('hide');
        var title = $('#title').val();
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();

        $.ajax({
            url: 'dashboard_lecturer.php',
            data: 'action=add&title=' + title + '&start=' + startTime + '&end=' + endTime,
            type: "POST",
            success: function (json) {
                $("#calendar_s").fullCalendar('renderEvent',
                    {
                        id: json.id,
                        title: title,
                        start: startTime,
                        end: endTime,
                    },
                    true);
            }
        });

    }
});