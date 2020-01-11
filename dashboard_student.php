<?php
session_start();
include("database.php");
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = session_id();
}

if (!isset($_SESSION['logged_regNo']) or !isset($_SESSION['logged_roleType'])) {
    header('location:index.php');
} else {
    $studentId = $_SESSION['logged_regNo'];
}


$datetime_string = date('c', time());

// $module_list = mysqli_query($connection, " SELECT m.moduleTitle, m.moduleCode FROM modules m INNER JOIN module_conduct c ON m.moduleId=c.moduleId WHERE c.lecturerId='" . $lecturerId . "' ");


// $module_info_info = mysqli_fetch_assoc($module_list);
$student_info = mysqli_query($connection, " SELECT `studentId`, `studentName`, `departmentId`, `semester`, `email` FROM `students` WHERE `studentId`='" . $studentId . "' ");
$rowStudent = mysqli_fetch_array($student_info);
// $res_info = mysqli_fetch_assoc($lecturer_info);

$sem_no = $rowStudent['semester'];

if (isset($_GET['view'])) {
    header('Content-Type: application/json');

    $start = mysqli_real_escape_string($connection, $_GET["start"]);
    $end = mysqli_real_escape_string($connection, $_GET["end"]);

    // $result = mysqli_query($connection, "SELECT `id`, `start` ,`end` ,`module`, `color` FROM  `timetable` where (date(start) >= '$start' AND date(start) <= '$end') and lecturerId='" . $lecturerId . "'");
    // $result = mysqli_query($connection, "SELECT `id`, `start` ,`end` ,`module`, `color` FROM  `timetable` where (date(start) >= '$start' AND date(start) <= '$end') ");
    $result = mysqli_query($connection, " SELECT DISTINCT t.id,t.start,t.end,t.module,t.color FROM timetable t INNER JOIN (SELECT m.moduleCode,m.semester FROM modules m) AS R WHERE R.semester='" . $sem_no . "' ");
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
    echo json_encode($events);
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Pinkman I CRM Dashboard</title>
    <meta name="description" content="A responsive bootstrap 4 admin dashboard template by hencework" />



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

    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>


    <link rel="stylesheet" href="https://bootswatch.com/4/lux/bootstrap.min.css">

    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
    <link href='../packages/core/main.css' rel='stylesheet' />
    <link href='../packages/bootstrap/main.css' rel='stylesheet' />
    <link href='../packages/timegrid/main.css' rel='stylesheet' />
    <link href='../packages/daygrid/main.css' rel='stylesheet' />
    <link href='../packages/list/main.css' rel='stylesheet' />


    <link rel="stylesheet" href="css/my.css">

    <!-- my================================================= -->





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
            <a class="navbar-brand" href="dashboard1.html">
                <img class="brand-img d-inline-block" src="img/dashboard6.png" alt="brand" />
            </a>
            <ul class="navbar-nav hk-navbar-content">
                <!-- <li class="nav-item">
                    <a id="navbar_search_btn" class="nav-link nav-link-hover" href="javascript:void(0);"><i class="ion ion-ios-search"></i></a>
                </li>
                <li class="nav-item">
                    <a id="settings_toggle_btn" class="nav-link nav-link-hover" href="javascript:void(0);"><i class="ion ion-ios-settings"></i></a>
                </li> -->

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
                                <span><?php echo $rowStudent['studentName'] ?><i class="zmdi zmdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
                        <a class="dropdown-item" data-toggle="modal" data-target="#changePasswordModal"><i class="dropdown-icon zmdi zmdi-settings"></i><span>Settings</span></a>
                        <!-- <div class="dropdown-divider"></div> -->

                        <a class="dropdown-item" href="logout.php"><i class="dropdown-icon zmdi zmdi-power"></i><span>Log out</span></a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /Top Navbar -->

        <!-- Vertical Nav -->
        <!-- <nav class="hk-nav hk-nav-light">
            <a href="javascript:void(0);" id="hk_nav_close" class="hk-nav-close"><span class="feather-icon"><i data-feather="x"></i></span></a>
            <div class="nicescroll-bar">
                <div class="navbar-nav-wrap">
                    <ul class="navbar-nav flex-column">

                        <li class="nav-item active">
                            <a class="nav-link" href="javascript:void(0);">
                                <i class="ion ion-ios-keypad"></i>
                                <span class="nav-link-text">17</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link link-with-badge" href="javascript:void(0);">
                                <i class="ion ion-ios-apps"></i>
                                <span class="nav-link-text">18</span>
                                <span class="badge badge-primary badge-pill">4</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);">
                                <i class="ion ion-ios-person-add"></i>
                                <span class="nav-link-text">19</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);">
                                <i class="ion ion-ios-copy"></i>
                                <span class="nav-link-text">20</span>
                            </a>
                        </li>

                    </ul>

                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span>User Interface</span>
                        <span>UI</span>
                    </div>



                </div>
            </div>
        </nav> -->

        <!-- <div id="hk_nav_backdrop" class="hk-nav-backdrop"></div> -->
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
                                    <input type="text" class="form-control" id="username" value="<?php echo $studentId; ?>" disabled>
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



        </div>
        <!-- /Main Content -->


    </div>
    <!-- /HK Wrapper -->





    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Slimscroll JavaScript -->
    <script src="dist/js/jquery.slimscroll.js"></script>

    <!-- Fancy Dropdown JS -->
    <script src="dist/js/dropdown-bootstrap-extended.js"></script>

    <!-- FeatherIcons JavaScript -->
    <script src="dist/js/feather.min.js"></script>

    <!-- Toggles JavaScript -->
    <!-- <script src="vendors/jquery-toggles/toggles.min.js"></script>
    <script src="dist/js/toggle-data.js"></script> -->

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

    <!-- Apex JavaScript -->
    <script src="vendors/apexcharts/dist/apexcharts.min.js"></script>
    <script src="dist/js/irregular-data-series.js"></script>

    <!-- Init JavaScript -->
    <script src="dist/js/init.js"></script>
    <script src="dist/js/dashboard-data.js"></script>



    <!-- myyyy ========================================================= -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/script_student.js"></script>

    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->



    <script src="js/moment.min.js"></script>
    <script src="js/fullcalendar.js"></script>




    <!-- myyyy ========================================================= -->




</body>

</html>