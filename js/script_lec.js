$(document).ready(function () {

    // var calendarEl = document.getElementById('calendar');
    var calendar = $('#calendar').fullCalendar({
        // themeSystem: 'lux',
        header: {
            left: 'prev,next today',
            center: 'title',
            // right: 'agendaWeek,timeGridWeek,timeGridDay,listMonth'
            right: 'month,agendaWeek,listWeek'
        },
        // defaultView: 'month',
        defaultView: 'agendaWeek',
        editable: true,
        selectable: true,
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

        events: "dashboard_lecturer.php?view=1",

        eventRender: function (event, element) {
            $(element).popover({
                html : true,
                title: event.name,
                content: event.note,
                trigger: 'hover',
                placement: 'top',
                container: 'body'
            });
        },
        // eventRender: function(event, element) { 
        //     element.find('.fc-title').append("<br/>ghggh"); 
        // },


        eventClick: function (event, jsEvent, view) {
            endtime = $.fullCalendar.moment(event.end).format('h:mm');
            starttime = $.fullCalendar.moment(event.start).format('dddd, MMMM Do YYYY, h:mm');

            starttime_modal = $.fullCalendar.moment(event.start).format('YYYY-MM-DD H:mm:ss');
            endtime_modal = $.fullCalendar.moment(event.end).format('YYYY-MM-DD H:mm:ss');

            // eventId = $.fullCalendar.moment(event.id);
            $('#location_list').empty();
            $.ajax({
                url: 'location.php',
                data: 'start_time=' + starttime_modal + '&end_time=' + endtime_modal + '&eventid=' + event.id,
                type: "POST",
                success: function (json) {

                    json = JSON.parse(json);
                    $('#location_list').empty();
                    $('#location_list').append('<option selected disabled>Available Locations</option>');
                    json.forEach(function (module) {
                        $('#location_list').append('<option value=' + module.locationId + '>' + module.code + ' - ' + module.name + '</option>')
                    });


                }
            });

            $.ajax({
                url: 'location.php',
                data: 'action=refresh&id=' + event.id,
                type: "POST",
                success: function (json) {
                    json = JSON.parse(json);
                    $('#modalLocation').text(json.name);
                    $('#note').val(json.note);

                }
            });




            var mywhen = starttime + ' - ' + endtime;
            $('#modalTitle').html(event.module);
            $('#modalWhen').text(mywhen);
            $('#eventID').val(event.id);
            $('#calendarModal').modal();

            $('#start_time').val(starttime_modal);
            $('#end_time').val(endtime_modal);
        },

        //header and other values
        select: function (start, end, jsEvent) {
            endtime = $.fullCalendar.moment(end).format('h:mm');
            starttime = $.fullCalendar.moment(start).format('dddd, MMMM Do YYYY, h:mm');
            var mywhen = starttime + ' - ' + endtime;
            start = moment(start).format();
            end = moment(end).format();

            starttime_modal = $.fullCalendar.moment(start).format('YYYY-MM-DD H:mm:ss');
            endtime_modal = $.fullCalendar.moment(end).format('YYYY-MM-DD H:mm:ss');

            // eventId = $.fullCalendar.moment(event.id);
            $('#addnewlocation').empty();
            $.ajax({
                url: 'location.php',
                data: 'start_time=' + starttime_modal + '&end_time=' + endtime_modal,
                type: "POST",
                success: function (json) {

                    json = JSON.parse(json);
                    // alert(json);
                    $('#addnewlocation').empty();
                    $('#addnewlocation').append('<option selected disabled>Available Locations</option>');
                    json.forEach(function (module) {
                        $('#addnewlocation').append('<option value=' + module.locationId + '>' + module.code + ' - ' + module.name + '</option>')
                    });


                }
            });


            $('#createEventModal #startTime').val(start);
            $('#createEventModal #endTime').val(end);
            $('#createEventModal #when').text(mywhen);
            $('#createEventModal').modal('toggle');

        },

        eventDrop: function (event, delta) {
            $.ajax({
                url: 'dashboard_lecturer.php',
                data: 'action=update&module=' + event.module + '&start=' + moment(event.start).format() + '&end=' + moment(event.end).format() + '&id=' + event.id,
                type: "POST",
                success: function (json) {
                    //alert(json);
                }
            });
        },

        eventResize: function (event) {
            $.ajax({
                url: 'dashboard_lecturer.php',
                data: 'action=update&module=' + event.module + '&start=' + moment(event.start).format() + '&end=' + moment(event.end).format() + '&id=' + event.id,
                type: "POST",
                success: function (json) {
                    //alert(json);
                }
            });
        }

    });

    $('#submitButton').on('click', function (e) {
        // We don't want this to act as a link so cancel the link action
        var checknull = $('#addnewlocation').val();
        // $('#location_list').val();
        if (checknull == null) {
            alert("Please Select Location");
        } else {
            e.preventDefault();
            doSubmit();
        }

    });

    $('#deleteButton').on('click', function (e) {
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        doDelete();
    });

    $('#changeLocationBtn').on('click', function (e) {
        // We don't want this to act as a link so cancel the link action
        var checknull = $('#location_list').val();
        // $('#location_list').val();
        if (checknull == null) {
            alert("Please Select Location");
        } else {
            e.preventDefault();
            doChangeLocation();
        }
        // alert(nonc);

    });


    $('#changeButton').on('click', function (e) {
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        doChangePass();
    });


    $('#setnote').on('click', function (e) {
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        doAddNotes();
    });

    function doDelete() {
        $("#calendarModal").modal('hide');
        var eventID = $('#eventID').val();
        $.ajax({
            url: 'dashboard_lecturer.php',
            data: 'action=delete&id=' + eventID,
            type: "POST",
            success: function (json) {
                if (json == 1)
                    $("#calendar").fullCalendar('removeEvents', eventID);
                else
                    return false;


            }
        });
    }

    function doSubmit() {
        $("#createEventModal").modal('hide');
        var module = $('#module').val();
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();
        var location = $('#addnewlocation').val();

        $.ajax({
            url: 'dashboard_lecturer.php',
            data: 'action=add&module=' + module + '&start=' + startTime + '&end=' + endTime + '&location=' + location,
            type: "POST",
            success: function (json) {
                alert(json);
                $("#calendar").fullCalendar('renderEvent',
                    {
                        id: json.id,
                        module: module,
                        start: startTime,
                        end: endTime,
                        test1: 'abcd',
                    },
                    true);


            }
        });

    }

    function doChangePass() {
        $("#changePasswordModal").modal('hide');
        var username = $('#username').val();
        var pass_old = $('#password_old').val();
        var pass_new = $('#password_new').val();

        $.ajax({
            url: 'dashboard_lecturer.php',
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

    function doChangeLocation() {
        $("#calendarModal").modal('hide');

        var eventID = $('#eventID').val();
        var new_location = $('#location_list').val();
        // var new_location = document.getElementById('location_list');;

        $.ajax({
            url: 'location.php',
            data: 'action=changelocation&id=' + eventID + '&newloc=' + new_location,
            type: "POST",
            success: function (json) {

            }
        });
    }

    function doAddNotes() {
        $("#calendarModal").modal('hide');
        var eventID = $('#eventID').val();
        var note = $('#note').val();

        $.ajax({
            url: 'location.php',
            data: 'action=addnote&id=' + eventID + '&note=' + note,
            type: "POST",
            success: function (json) {
                $('#note').val(" ");
                // if (json == 1)
                //     $("#calendar").fullCalendar('removeEvents', eventID);
                // else
                //     return false;
                // alert(json);
            }
        });

    }


    /////////student view///////////////////////////////////////////////////////////////////////////

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

        events: "dashboard_lecturer.php?show_std=6",

    });




});

