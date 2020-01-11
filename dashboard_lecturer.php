<?php
session_start();
include("database.php");

// if (!isset($_SESSION['user'])) {
//     $_SESSION['user'] = session_id();
// }

if (!isset($_SESSION['logged_regNo']) or !isset($_SESSION['logged_roleType'])) {
    header('location:index.php');
} else {
    $lecturerId = $_SESSION['logged_regNo'];
}

if (!isset($_SESSION['role'])) {
    // echo $_GET['link'];
    $_SESSION['role'] = '6';
}

// $_SESSION['user']

// $uid = $_SESSION['user'];  // set your user id settings
// $lecturerId = 'EGLE04';  // set your user id settings
// $sem = '8';

$isDefaultPass = mysqli_query($connection, " SELECT `password`,`username` FROM `login` WHERE regNo='" . $lecturerId . "' ");
$row_isDefaultPass = mysqli_fetch_array($isDefaultPass);

if ($row_isDefaultPass["password"] == md5($row_isDefaultPass["username"])) {
    // $msg = "Dafualt Pass";
    $_SESSION['dafualt'] = '1';
} else {
    $_SESSION['dafualt'] = '0';
}


$sem_info = mysqli_query($connection, " SELECT `year`, `sem_start_date`, `sem_end_date` FROM `sem_info` ");
$sem_info_val = mysqli_fetch_assoc($sem_info);

$start_date = $sem_info_val['sem_start_date'];
$end_date = $sem_info_val['sem_end_date'];


$datetime_string = date('c', time());

$module_list = mysqli_query($connection, " SELECT m.moduleTitle, m.moduleCode FROM modules m INNER JOIN module_conduct c ON m.moduleId=c.moduleId WHERE c.lecturerId='" . $lecturerId . "' ");

$sem_list = mysqli_query($connection, "SELECT semester FROM sem_info ORDER BY semester ASC");

// $module_info_info = mysqli_fetch_assoc($module_list);
$lecturer_info = mysqli_query($connection, " SELECT l.lecturerId,l.lecturerName,l.email,d.departmentName FROM lecturers l INNER JOIN department d ON l.departmentId=d.departmentId WHERE lecturerId='" . $lecturerId . "' ");
$res_info = mysqli_fetch_assoc($lecturer_info);


if (isset($_POST['action']) or isset($_GET['view']) or isset($_GET['show_std'])) {
    if (isset($_GET['view'])) {
        header('Content-Type: application/json');

        // include("tech_module.php");
        // echo $final_module_not_in;

        $start = mysqli_real_escape_string($connection, $_GET["start"]);
        $end = mysqli_real_escape_string($connection, $_GET["end"]);

        // t.id, t.start ,t.end ,t.module, t.color, l.code,l.name, t.lecturerId

        // $result = mysqli_query($connection, "SELECT `id`, `start` ,`end` ,`module`, `color`,l.code, `lecturerId` FROM  `timetable` INNER JOIN locations l ON timetable.locationId=locations.locationId WHERE (date(start) >= '$start' AND date(start) <= '$end') and lecturerId='" . $lecturerId . "' ");
        $result = mysqli_query($connection, "SELECT * FROM  timetable t INNER JOIN locations l ON t.locationId=l.locationId WHERE (date(t.start) >= '$start' AND date(t.start) <= '$end') and t.lecturerId='" . $lecturerId . "' ");
        // $result = mysqli_query($connection, "SELECT * FROM  timetable INNER JOIN locations USING (locationId) INNER JOIN modules ON timetable.module=modules.moduleCode WHERE (date(t.start) >= '$start' AND date(t.start) <= '$end') and t.lecturerId='" . $lecturerId . "' ");
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }

        array_walk_recursive($events, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });

        // echo json_encode($value);


        echo json_encode($events);
        exit;
    } elseif (isset($_GET['show_std'])) {
        header('Content-Type: application/json');

        $sem_no = $_SESSION['role'];

        $start = mysqli_real_escape_string($connection, $_GET["start"]);
        $end = mysqli_real_escape_string($connection, $_GET["end"]);

        // $result = mysqli_query($connection, "SELECT `id`, `start` ,`end` ,`module`, `color` FROM  `timetable` where (date(start) >= '$start' AND date(start) <= '$end') ");
        $result = mysqli_query($connection, " SELECT DISTINCT t.id,t.start,t.end,t.module,t.color FROM timetable t INNER JOIN (SELECT m.moduleCode,m.semester FROM modules m) AS R WHERE R.semester='" . $sem_no . "' AND t.module NOT IN (" . $_SESSION['final_module_not_in'] . ") ");
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
        echo json_encode($events);

        // echo "<h1>Student Lectures</h1>";
        // echo "<div class='row' id='abcdef'>";
        // echo   "<div id='calendar_s'></div>";
        // echo "</div>";

        exit;
    } elseif ($_POST['action'] == "add") {
        mysqli_query($connection, "INSERT INTO `timetable` (
                    `module` ,
                    `start` ,
                    `end` ,
                    `lecturerId` ,
                    `color`,
                    `locationId`
                    )
                    VALUES (
                    '" . mysqli_real_escape_string($connection, $_POST["module"]) . "',
                    '" . mysqli_real_escape_string($connection, date('Y-m-d H:i:s', strtotime($_POST["start"]))) . "',
                    '" . mysqli_real_escape_string($connection, date('Y-m-d H:i:s', strtotime($_POST["end"]))) . "',
                    '" . mysqli_real_escape_string($connection, $lecturerId) . "',
                    '#ff569a',
                    '" . mysqli_real_escape_string($connection, $_POST["location"]) . "'
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
    } elseif ($_POST['action'] == "changepass") {
        // $updateQuery = "UPDATE login SET login.password='" . mysqli_real_escape_string($connection, $_POST["passnew"]) . "' WHERE login.regNo='" . $lecturerId . "' AND login.password='" . mysqli_real_escape_string($connection, $_POST["passold"]) . "' ";
        // mysqli_query($connection, " ");
        $getOldPass = mysqli_query($connection, " SELECT `password` FROM `login` WHERE regNo='" . $lecturerId . "' ");
        $row = mysqli_fetch_array($getOldPass);

        $p_new = md5($_POST["passnew"]);

        if (mysqli_real_escape_string($connection, md5($_POST["passold"])) == $row["password"]) {
            mysqli_query($connection, " UPDATE login SET login.password='" . mysqli_real_escape_string($connection, $p_new) . "' WHERE login.regNo='" . $lecturerId . "' ");
            $message = "Password Changed";
        } else
            $message = "Current Password is not correct";



        // if (mysqli_query($connection, $updateQuery)) {
        //     // echo "Record updated successfully";
        //     echo json_encode("Successxx");
        // } else {
        //     // echo "Error updating record: " . mysqli_error($connection);
        //     echo json_encode("Errorcc");
        // }

        echo json_encode($message);

        exit;
    }
}

if (isset($_POST['Submit'])) {

    $day_name = $_POST['day_name'];
    $s_time = $_POST['s_time'];
    $e_time = $_POST['e_time'];
    $module_select = $_POST['mod_list'];
    $location = $_POST['lec_location'];
    $lec_id = $lecturerId;

    // checking empty fields
    if (empty($day_name) || empty($s_time) || empty($e_time) || empty($module_select) || empty($lec_id)) {

        echo "<h1>empty</h1>";
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
    } else {
        getDateForSpecificDayBetweenDates($lec_id, $start_date, $end_date, $day_name, $module_select, $s_time, $e_time, $location);
        header('location:index.php');
        exit;
    }
}


function getDateForSpecificDayBetweenDates($lecId, $startDate, $endDate, $day_number, $module_select, $timeStart, $timeEnd, $location)
{
    global $connection;
    //  = mysqli_connect('localhost', 'root', '', 'lecturescheduler_db') or die(mysqli_error($connection));

    // $timeStart = "10:30:00";
    // $timeEnd = "15:30:00";
    $lecturerId = $lecId;
    $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

    $endDate = strtotime($endDate);
    $days = array('1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday', '7' => 'Sunday');

    for ($i = strtotime($days[$day_number], strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i)) {
        // $date_array[] = date('Y-m-d H:i:s', $i);
        $s = date('Y-m-d', $i) . " " . date($timeStart);
        $e = date('Y-m-d', $i) . " " . date($timeEnd);

        // echo $s . "-" . $e . "<br>";

        $sql = "INSERT INTO `timetable`(`lecturerId`,`module`,`color`,`start`, `end`,`locationId`) VALUES ('$lecturerId','$module_select','$color','$s','$e','$location') ";

        if (mysqli_query($connection, $sql)) {
            echo "Records inserted successfully.";
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($connection);
        }
    }
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>My Dashboard</title>
    <meta name="description" content="A responsive bootstrap 4 admin dashboard template by hencework" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- vector map CSS -->
    <link href="vendors/vectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet" type="text/css" />

    <!-- Toggles CSS -->
    <link href="vendors/jquery-toggles/css/toggles.css" rel="stylesheet" type="text/css">
    <link href="vendors/jquery-toggles/css/themes/toggles-light.css" rel="stylesheet" type="text/css">

    <!-- Toastr CSS -->
    <link href="vendors/jquery-toast-plugin/dist/jquery.toast.min.css" rel="stylesheet" type="text/css">

    <!-- Custom CSS -->
    <link href="dist/css/style.css" rel="stylesheet" type="text/css">

    <!-- my======================================== -->

    <script type="text/javascript" src="assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>


    <link rel="stylesheet" href="https://bootswatch.com/4/lux/bootstrap.min.css">

    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
    <link href='../packages/core/main.css' rel='stylesheet' />
    <link href='../packages/bootstrap/main.css' rel='stylesheet' />
    <link href='../packages/timegrid/main.css' rel='stylesheet' />
    <link href='../packages/daygrid/main.css' rel='stylesheet' />
    <link href='../packages/list/main.css' rel='stylesheet' />

    <link rel="stylesheet" href="dist/css/animate.css">
    <link rel="stylesheet" href="css/my.css">

    <!-- my================================================= -->


    <script>
        $(document).ready(function() {
            var myvar = '<?php echo $_SESSION['dafualt']; ?>';

            if (myvar == '1') {
                // localStorage.setItem('isshow', 1);
                // Show popup here
                $('#modelTitleStatus').text('You use defualt password, Please Change.');
                $('#exampleModalSmall01').modal('show');
            } else {
                $('#modelTitleStatus').text('');
                $('#exampleModalSmall01').modal('hide');
            };


            $("#selectSemNo").change(function() {
                var semesterno = $("#selectSemNo").val();
                var lecid = '<?php echo $lecturerId; ?>';
                $.ajax({
                    url: 'data_post.php',
                    method: 'post',
                    data: 'semesterno=' + semesterno + '&lecid=' + lecid
                }).done(function(modules) {
                    console.log(modules);
                    modules = JSON.parse(modules);
                    $('#mod_list').empty();
                    modules.forEach(function(module) {
                        $('#mod_list').append('<option value=' + module.moduleCode + '>' + module.moduleCode + ' - ' + module.moduleTitle + '</option>')
                    })
                })
            });




        });

        // Time Picker Initialization
        // $('#input_starttime').pickatime({});
    </script>



</head>

<body>
    <!-- Preloader -->
    <div class="preloader-it">
        <div class="loader-pendulums"></div>
    </div>
    <!-- /Preloader -->


    <!-- HK Wrapper -->
    <div class="hk-wrapper hk-vertical-nav">


        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-xl navbar-dark fixed-top hk-navbar">
            <a id="navbar_toggle_btn" class="navbar-toggle-btn nav-link-hover" href="javascript:void(0);"><i class="ion ion-ios-menu"></i></a>
            <a class="navbar-brand" href="#">
                <img class="brand-img d-inline-block" src="img/dashboard6.png" alt="brand" />
            </a>
            <ul class="navbar-nav hk-navbar-content">

                <li class="nav-item">
                    <a id="settings_toggle_btn" class="nav-link nav-link-hover" href="javascript:void(0);"><i class="ion ion-ios-settings"></i></a>
                </li>

                <li class="nav-item dropdown dropdown-authentication">
                    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media">
                            <div class="media-img-wrap">
                                <div class="avatar">
                                    <img src="dist/img/avatar12.jpg" alt="user" class="avatar-img rounded-circle">
                                </div>
                                <span class="badge badge-success badge-indicator"></span>
                            </div>
                            <div class="media-body">
                                <span><?php echo $res_info['lecturerName'] ?><i class="zmdi zmdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
                        <a class="btn dropdown-item" data-toggle="modal" data-target="#changePasswordModal"><i class="dropdown-icon zmdi zmdi-settings"></i><span>Settings</span></a>
                        <a class="dropdown-item" href="logout.php"><i class="dropdown-icon zmdi zmdi-power"></i><span>Log out</span></a>
                    </div>
                </li>

            </ul>
        </nav>
        <!-- /Top Navbar -->


        <!-- Vertical Nav -->
        <nav class="hk-nav hk-nav-light">
            <a href="javascript:void(0);" id="hk_nav_close" class="hk-nav-close"><span class="feather-icon"><i data-feather="x"></i></span></a>
            <div class="nicescroll-bar">
                <div class="navbar-nav-wrap">
                    <ul class="navbar-nav flex-column">

                        <li class="nav-item active">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#dash_drp123">
                                <i class="ion ion-ios-keypad"></i>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                            <ul id="dash_drp123" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">

                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="modal" data-target="#addlectureModal">Add Lectures</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <hr class="nav-separator">

                        <?php
                        foreach ($sem_list as $s) {
                            echo "<li class='nav-item'>";
                            echo "<a class='nav-link' href='lecdata.php?role=" . $s['semester'] . "'>";
                            echo "<i class='ion ion-ios-person-add'></i>";
                            echo "<span class='nav-link-text'>Semester " . $s['semester'] . "</span>";
                            echo "</a>";
                            echo "</li>";
                        }
                        ?>

                    </ul>

                    <hr class="nav-separator">
                    <!-- <div class="nav-header">
                        <span>User Interface</span>
                        <span>UI</span>
                    </div> -->



                </div>
            </div>
        </nav>
        <div id="hk_nav_backdrop" class="hk-nav-backdrop"></div>
        <!-- /Vertical Nav -->

        <!-- Setting Panel -->
        <div class="hk-settings-panel">
            <div class="nicescroll-bar position-relative">
                <div class="settings-panel-wrap">
                    <div class="settings-panel-head">
                        <img class="brand-img d-inline-block align-top" src="dist/img/logo-light.png" alt="brand" />
                        <a href="javascript:void(0);" id="settings_panel_close" class="settings-panel-close"><span class="feather-icon"><i data-feather="x"></i></span></a>
                    </div>
                    <hr>
                    <h6 class="mb-5">Layout</h6>
                    <p class="font-14">Choose your preferred layout</p>
                    <div class="layout-img-wrap">
                        <div class="row">
                            <a href="javascript:void(0);" class="col-6 mb-30 active">
                                <img class="rounded opacity-70" src="dist/img/layout1.png" alt="layout">
                                <i class="zmdi zmdi-check"></i>
                            </a>
                            <a href="dashboard2.html" class="col-6 mb-30">
                                <img class="rounded opacity-70" src="dist/img/layout2.png" alt="layout">
                                <i class="zmdi zmdi-check"></i>
                            </a>
                            <a href="dashboard3.html" class="col-6">
                                <img class="rounded opacity-70" src="dist/img/layout3.png" alt="layout">
                                <i class="zmdi zmdi-check"></i>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <h6 class="mb-5">Navigation</h6>
                    <p class="font-14">Menu comes in two modes: dark & light</p>
                    <div class="button-list hk-nav-select mb-10">
                        <button type="button" id="nav_light_select" class="btn btn-outline-primary btn-sm btn-wth-icon icon-wthot-bg"><span class="icon-label"><i class="fa fa-sun-o"></i> </span><span class="btn-text">Light Mode</span></button>
                        <button type="button" id="nav_dark_select" class="btn btn-outline-light btn-sm btn-wth-icon icon-wthot-bg"><span class="icon-label"><i class="fa fa-moon-o"></i> </span><span class="btn-text">Dark Mode</span></button>
                    </div>
                    <hr>
                    <h6 class="mb-5">Top Nav</h6>
                    <p class="font-14">Choose your liked color mode</p>
                    <div class="button-list hk-navbar-select mb-10">
                        <button type="button" id="navtop_light_select" class="btn btn-outline-light btn-sm btn-wth-icon icon-wthot-bg"><span class="icon-label"><i class="fa fa-sun-o"></i> </span><span class="btn-text">Light Mode</span></button>
                        <button type="button" id="navtop_dark_select" class="btn btn-outline-primary btn-sm btn-wth-icon icon-wthot-bg"><span class="icon-label"><i class="fa fa-moon-o"></i> </span><span class="btn-text">Dark Mode</span></button>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Scrollable Header</h6>
                        <div class="toggle toggle-sm toggle-simple toggle-light toggle-bg-primary scroll-nav-switch"></div>
                    </div>
                    <button id="reset_settings" class="btn btn-primary btn-block btn-reset mt-30">Reset</button>
                </div>
            </div>
            <img class="d-none" src="dist/img/logo-light.png" alt="brand" />
            <img class="d-none" src="dist/img/logo-dark.png" alt="brand" />
        </div>
        <!-- /Setting Panel -->

        <!-- Main Content -->
        <div class="hk-pg-wrapper">

            <!-- Breadcrumb -->
            <nav class="hk-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light bg-transparent">
                    <li class="breadcrumb-item"><a href="#">Semester</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $_SESSION['role']; ?></li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->

            <div class="container">

                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <div class="table-responsive">

                                <!-- <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th href="foo.html">Module Code</th>
                                            <th>Module Title</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($module_list as $q) {
                                            echo "<tr>";
                                            echo "<th scope='row'>#</th>";
                                            echo "<td>" . $q['moduleCode'] . "</td>";
                                            echo  "<td class='peity-gradient'><span class='peity-line' data-width='90'  data-height='40'>" . $q['moduleTitle'] . "</span> </td>";
                                            echo "</tr>";
                                            // echo "<option value=' " . $k['moduleCode'] . " '> " . $k['moduleCode'] . " - " . $k['moduleTitle'] . " </option>";
                                        }
                                        ?>
                                    </tbody>
                                </table> -->

                                <ul>
                                    <li>Click on Techniqul Module</li>
                                    <?php
                                    foreach ($module_list as $q) {
                                        echo "<li class='nav-item'>";
                                        echo "<a class='nav-link' href='tech_module.php?myModule=" . $q['moduleCode'] . "&myId=" . $lecturerId . "'>";
                                        echo  $q['moduleCode'] . " - " . $q['moduleTitle'];
                                        echo "</a>";
                                        echo "</li>";
                                    }
                                    ?>
                                    <li>
                                        <form action="tech_module.php" method="post">
                                            <input type="submit" name="clearSes" value="Reset" />
                                        </form>
                                    </li>
                                </ul>


                            </div>
                        </div>
                    </div>
                </div>

                <hr class="nav-separator">


            </div>

            <br><br>

            <div>
                <div class="container">

                    <h1>My Lectures</h1>
                    <div class="row">
                        <div id="calendar">
                        </div>
                    </div>

                    <hr class="nav-separator">

                    <h1><?php echo $_SESSION['role']; ?> - Semester Students' Lectures</h1>
                    <h4><?php echo $_SESSION['final_module_not_in']; ?></h4>
                    <div class="row">
                        <div id="calendar_s"></div>
                    </div>

                </div>
            </div>




            <!-- Modal Add New -->
            <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Extra Lectures</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <input type="hidden" id="startTime" />
                            <input type="hidden" id="endTime" />

                            <table class="table table-bordered table-dark">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><select class="form-control custom-select  mt-15" id="module" name="module">
                                                <?php
                                                foreach ($module_list as $k) {
                                                    echo "<option value=' " . $k['moduleCode'] . " '> " . $k['moduleCode'] . " - " . $k['moduleTitle'] . " </option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2"><select class="custom-select" id="addnewlocation"> </select></td>
                                    </tr>

                                    <tr>
                                        <td>When :</td>
                                        <td id="when"></td>
                                    </tr>

                                </tbody>
                            </table>







                            <!-- <div class="control-group">
                                <label class="control-label" for="when">When:</label>
                                <div class="controls controls-row" id="when" style="margin-top:5px;">
                                </div>
                            </div> -->

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="submitButton">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Delete -->
            <div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="calendarModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <div id="modalBody" class="modal-body">
                                <!-- <h4 id="modalTitle" class="modal-title"></h4> -->
                                <!-- <div id="modalWhen" style="margin-top:5px;"></div> -->
                                <!-- <p>Location</p> -->
                                <!-- <p id="modalLocation" style="margin-top:5px;"></p> -->

                                <input type="hidden" id="start_time" />
                                <input type="hidden" id="end_time" />

                                <table class="table table-bordered table-dark">
                                    <tbody>
                                        <tr>
                                            <td id="modalTitle" colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td id="modalWhen" colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Location -</th>
                                            <td id="modalLocation"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="custom-select" id="location_list" name="location_list">
                                                    <!-- <option selected="selected" >Select New Location</option> -->
                                                    <!-- <option value="3" selected="selected"> 3 </option> -->
                                                </select>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-warning btn-block" id="changeLocationBtn">Change Location</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><textarea id="note" rows="2"></textarea></td>
                                            <td>
                                                <button type="submit" class="btn btn-success btn-block" id="setnote">Add Notes</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>




                                <!-- <select class="custom-select" id="location_list" name="location_list">
                                    <option disabled>Select New Location</option>
                                </select> -->
                                <!-- <button type="submit" class="btn btn-warning" id="changeLocationBtn">Change Location</button> -->
                                <!-- <input type="button" value="Change Location"> -->
                            </div>

                            <input type="hidden" id="eventID" />


                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger" id="deleteButton">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal forms-->
            <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Change Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" id="username" value="<?php echo $lecturerId; ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" class="form-control" id="password_old" placeholder="Password">
                                </div>

                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" id="password_new" placeholder="Password">
                                </div>

                                <button type="submit" class="btn btn-primary" id="changeButton">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal small -->
            <div class="modal fade" id="exampleModalSmall01" tabindex="-1" role="dialog" aria-labelledby="exampleModalSmall01" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Status</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="modelTitleStatus"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalSmall01">
                Small modal
            </button> -->

            <!-- Modal forms-->
            <div class="modal fade" id="addlectureModal" tabindex="-1" role="dialog" aria-labelledby="addlectureModal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Lectures</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="">

                                <table class="table table-dark">
                                    <tbody>
                                        <tr>
                                            <td>Semester Start Date</td>
                                            <td><?php echo $sem_info_val['sem_start_date']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Semester End Date</td>
                                            <td><?php echo $sem_info_val['sem_end_date']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Select Day</td>
                                            <td><select class="form-control" id="day_name" name="day_name">
                                                    <option value="1">Monday</option>
                                                    <option value="2">Tuesday</option>
                                                    <option value="3">Wednesday</option>
                                                    <option value="4">Thursday</option>
                                                    <option value="5">Friday</option>
                                                    <option value="6">Saturday</option>
                                                    <option value="7">Sunday</option>
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td>Start Time</td>
                                            <td><input id="s_time" name="s_time" type="time"></td>
                                        </tr>
                                        <tr>
                                            <td>End Time</td>
                                            <td><input id="e_time" name="e_time" type="time"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="form-control" name="lec_location" id="lec_location">
                                                    <option selected="" disabled="">Select Default Location</option>
                                                    <?php
                                                    $location_lists = mysqli_query($connection, " SELECT * FROM locations ");
                                                    while ($row_location_list = mysqli_fetch_assoc($location_lists)) {
                                                        echo "<option value='" . $row_location_list['locationId'] . "'>" . $row_location_list['code'] . " - " . $row_location_list['name'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="selectSemNo" id="selectSemNo">
                                                    <option selected="" disabled="">Select Semester</option>

                                                    <?php

                                                    require 'data_post.php';
                                                    $row_sem_lists = loadSem();
                                                    foreach ($row_sem_lists as $row_sem_list) {
                                                        echo "<option id='" . $row_sem_list['semester'] . "' value='" . $row_sem_list['semester'] . "'>" . $row_sem_list['semester'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2">
                                                <select class="form-control" class="form-control" id="mod_list" name="mod_list"></select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                                <input class="btn btn-primary" name="Submit" type="submit">
                            </form>


                        </div>
                    </div>
                </div>
            </div>




        </div>
        <!-- /HK Wrapper -->






        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->


        <!-- jQuery -->
        <script src="vendors/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
        <!-- <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script> -->

        <!-- Slimscroll JavaScript -->
        <script src="dist/js/jquery.slimscroll.js"></script>

        <!-- Fancy Dropdown JS -->
        <script src="dist/js/dropdown-bootstrap-extended.js"></script>

        <!-- FeatherIcons JavaScript -->
        <script src="dist/js/feather.min.js"></script>

        <!-- Toggles JavaScript -->
        <script src="vendors/jquery-toggles/toggles.min.js"></script>
        <script src="dist/js/toggle-data.js"></script>

        <!-- Counter Animation JavaScript -->
        <script src="vendors/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="vendors/jquery.counterup/jquery.counterup.min.js"></script>

        <!-- Sparkline JavaScript -->
        <script src="vendors/jquery.sparkline/dist/jquery.sparkline.min.js"></script>

        <!-- Vector Maps JavaScript -->
        <script src="vendors/vectormap/jquery-jvectormap-2.0.3.min.js"></script>
        <script src="vendors/vectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script src="vendors/vectormap/jquery-jvectormap-de-merc.js"></script>
        <script src="dist/js/vectormap-data.js"></script>

        <!-- Owl JavaScript -->
        <script src="vendors/owl.carousel/dist/owl.carousel.min.js"></script>

        <!-- Toastr JS -->
        <script src="vendors/jquery-toast-plugin/dist/jquery.toast.min.js"></script>
        <script src="dist/js/toast-data.js"></script>

        <!-- Apex JavaScript -->
        <script src="vendors/apexcharts/dist/apexcharts.min.js"></script>
        <script src="dist/js/irregular-data-series.js"></script>

        <!-- Init JavaScript -->
        <script src="dist/js/init.js"></script>
        <script src="dist/js/dashboard-data.js"></script>



        <!-- myyyy ========================================================= -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- <script type="text/javascript" src="js/script_lec.js"></script> -->
        <script type="text/javascript" src="js/script_lec.js"></script>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->



        <script src="js/moment.min.js"></script>
        <script src="js/fullcalendar.js"></script>






        <!-- myyyy ========================================================= -->




</body>

</html>