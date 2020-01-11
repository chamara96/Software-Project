<?php
session_start();
include("database.php");
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = session_id();
}
// $uid = $_SESSION['user'];  // set your user id settings
$lecturerId = 'EGLE04';  // set your user id settings
$sem = '6';

$datetime_string = date('c', time());

$module_list = mysqli_query($connection, " SELECT m.moduleTitle, m.moduleCode FROM modules m INNER JOIN module_conduct c ON m.moduleId=c.moduleId WHERE c.lecturerId='" . $lecturerId . "' ");


// $module_info_info = mysqli_fetch_assoc($module_list);
// $lecturer_info = mysqli_query($connection, " SELECT lecturers.lecturerName, lecturers.departmentId, modules.semester,modules.moduleCode,modules.moduleTitle,modules.moduleId FROM modules INNER JOIN lecturers ON modules.lecturerId = lecturers.lecturerId WHERE lecturers.lecturerId = ' " . $lecturerId . " ' AND modules.semester = ' " . $sem . " ' ");
// $res_info = mysqli_fetch_assoc($lecturer_info);


if (isset($_POST['action']) or isset($_GET['view']) or isset($_GET['show'])) {
    if (isset($_GET['view'])) {
        header('Content-Type: application/json');

        $start = mysqli_real_escape_string($connection, $_GET["start"]);
        $end = mysqli_real_escape_string($connection, $_GET["end"]);

        $result = mysqli_query($connection, "SELECT `id`, `start` ,`end` ,`module`, `color` FROM  `timetable` where (date(start) >= '$start' AND date(start) <= '$end') and lecturerId='" . $lecturerId . "'");
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
        echo json_encode($events);
        exit;
    } elseif (isset($_GET['show'])) {
        header('Content-Type: application/json');

        $start = mysqli_real_escape_string($connection, $_GET["start"]);
        $end = mysqli_real_escape_string($connection, $_GET["end"]);

        $result = mysqli_query($connection, "SELECT `id`, `start` ,`end` ,`module`, `color` FROM  `timetable` where (date(start) >= '$start' AND date(start) <= '$end') ");
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
        echo json_encode($events);
        exit;
    } elseif ($_POST['action'] == "add") {
        mysqli_query($connection, "INSERT INTO `timetable` (
                    `module` ,
                    `start` ,
                    `end` ,
                    `lecturerId` 
                    )
                    VALUES (
                    '" . mysqli_real_escape_string($connection, $_POST["module"]) . "',
                    '" . mysqli_real_escape_string($connection, date('Y-m-d H:i:s', strtotime($_POST["start"]))) . "',
                    '" . mysqli_real_escape_string($connection, date('Y-m-d H:i:s', strtotime($_POST["end"]))) . "',
                    '" . mysqli_real_escape_string($connection, $lecturerId) . "'
                    )");
        header('Content-Type: application/json');
        echo '{"id":"' . mysqli_insert_id($connection) . '"}';
        exit;
    } elseif ($_POST['action'] == "update") {
        mysqli_query($connection, "UPDATE `timetable` set 
            `start` = '" . mysqli_real_escape_string($connection, date('Y-m-d H:i:s', strtotime($_POST["start"]))) . "', 
            `end` = '" . mysqli_real_escape_string($connection, date('Y-m-d H:i:s', strtotime($_POST["end"]))) . "' 
            where lecturerId = '" . mysqli_real_escape_string($connection, $lecturerId) . "' and id = '" . mysqli_real_escape_string($connection, $_POST["id"]) . "'");
        exit;
    } elseif ($_POST['action'] == "delete") {
        mysqli_query($connection, "DELETE from `timetable` where lecturerId = '" . mysqli_real_escape_string($connection, $lecturerId) . "' and id = '" . mysqli_real_escape_string($connection, $_POST["id"]) . "'");
        if (mysqli_affected_rows($connection) > 0) {
            echo "1";
        }
        exit;
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>Event Calendar</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>

<body>

    <hr />

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/script_lec.js"></script>

    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script> -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>


    <!-- <link href="css/fullcalendar.css" rel="stylesheet" /> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.css" integrity="sha256-nJK+Jim06EmZazdCbGddx5ixnqfXA13Wlw3JizKK1GU=" crossorigin="anonymous" /> -->
    <link rel="stylesheet" href="https://bootswatch.com/4/lux/bootstrap.min.css">


    <!-- <link href="css/fullcalendar.print.css" rel="stylesheet" media="print" /> -->

    <script src="js/moment.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/moment/main.min.js" integrity="sha256-iCYfw93enxd8O5zz/jL4UamQ8bgrUfidO4C5500RSd4=" crossorigin="anonymous"></script> -->

    <script src="js/fullcalendar.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.js" integrity="sha256-F4ovzqUMsKm41TQVQO+dWHQA+sshyOUdmnDcTPMIHkM=" crossorigin="anonymous"></script> -->

    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
    <link href='../packages/core/main.css' rel='stylesheet' />
    <link href='../packages/bootstrap/main.css' rel='stylesheet' />
    <link href='../packages/timegrid/main.css' rel='stylesheet' />
    <link href='../packages/daygrid/main.css' rel='stylesheet' />
    <link href='../packages/list/main.css' rel='stylesheet' />

    <!-- <script src='../packages/core/main.js'></script>
    <script src='../packages/interaction/main.js'></script>
    <script src='../packages/bootstrap/main.js'></script>
    <script src='../packages/daygrid/main.js'></script>
    <script src='../packages/timegrid/main.js'></script>
    <script src='../packages/list/main.js'></script> -->

    <link rel="stylesheet" href="css/my.css">

    <a href="logout.php">logout</a>


    <div class="container">

        <!-- <table style="width:100%;border:2px solid;" >
            <tr>
                <th>Name</th>
                <?php echo "<td>" . $res_info['lecturerName'] . "</td>"; ?>

            </tr>

            <tr>
                <th>Department</th>
                <?php echo "<td>" . $res_info['departmentId'] . "</td>"; ?>
            </tr>

            <tr>
                <th>Semester</th>
                <?php echo "<td>" . $res_info['semester'] . "</td>"; ?>
            </tr>

            <tr>
                <th>Modules</th>
                <td>
                <?php
                foreach ($module_list as $k) {
                    echo "<li>" . $k['moduleCode'] . " - " . $k['moduleTitle'] . "</li>";
                }
                ?>
                </td>

            </tr>

        </table> -->


    </div>

    <br><br>

    <!-- add calander in this div -->
    <div class="container">
        <h1>My Lectures</h1>
        <div class="row">
            <div id="calendar">
            </div>
        </div>

        <br><br>
        <div class="container">
            <h1>Student Lectures</h1>
            <div class="row">
                <div id="calendar_s"></div>
            </div>
        </div>


        <!-- Modal -->
        <div id="createEventModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Event</h4>
                    </div>
                    <div class="modal-body">
                        <div class="control-group">
                            <label class="control-label" for="inputPatient">Event:</label>
                            <div class="field desc">

                                <!-- <select class="form-control" id="module" name="module" placeholder="Event">
                                    <option>AAA</option>
                                </select> -->
                                <select class="form-control" id="module" name="module" placeholder="Event">
                                    <?php
                                    foreach ($module_list as $k) {
                                        echo "<option value=' " . $k['moduleCode'] . " '> " . $k['moduleCode'] . " - " . $k['moduleTitle'] . " </option>";
                                    }
                                    ?>
                                </select>


                                <!-- <input class="form-control" id="title" name="title" placeholder="Event" type="text" value=""> -->
                            </div>
                        </div>

                        <input type="hidden" id="startTime" />
                        <input type="hidden" id="endTime" />



                        <div class="control-group">
                            <label class="control-label" for="when">When:</label>
                            <div class="controls controls-row" id="when" style="margin-top:5px;">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
                    </div>
                </div>

            </div>
        </div>


        <div id="calendarModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Event Details</h4>
                    </div>
                    <div id="modalBody" class="modal-body">
                        <h4 id="modalTitle" class="modal-title"></h4>
                        <div id="modalWhen" style="margin-top:5px;"></div>
                    </div>
                    <input type="hidden" id="eventID" />
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        <button type="submit" class="btn btn-danger" id="deleteButton">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal-->



</body>

</html>