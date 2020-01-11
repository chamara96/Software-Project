<?php
session_start();
include("database.php");
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = session_id();
}

$sem = '6';
$dept = '1';

$sem_info = mysqli_query($connection, " SELECT `year`, `sem_start_date`, `sem_end_date` FROM `sem_info` ");
$sem_info_val = mysqli_fetch_assoc($sem_info);

$start_date = $sem_info_val['sem_start_date'];
$end_date = $sem_info_val['sem_end_date'];



$module_list = mysqli_query($connection, " SELECT `moduleCode`, `moduleTitle`, `moduleId` FROM `modules` ");



if (isset($_POST['Submit'])) {
    // $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    // $age = mysqli_real_escape_string($mysqli, $_POST['age']);
    // $email = mysqli_real_escape_string($mysqli, $_POST['email']);

    $day_name = $_POST['day_name'];
    $s_time = $_POST['s_time'];
    $e_time = $_POST['e_time'];
    $module_select = $_POST['modules'];
    $lec_id = $_POST['lecturer_list'];

    // checking empty fields
    if (empty($day_name) || empty($s_time) || empty($e_time) || empty($module_select) || empty($lec_id)) {

        // if (empty($name)) {
        //     echo "<font color='red'>Name field is empty.</font><br/>";
        // }

        // if (empty($age)) {
        //     echo "<font color='red'>Age field is empty.</font><br/>";
        // }

        // if (empty($email)) {
        //     echo "<font color='red'>Email field is empty.</font><br/>";
        // }

        echo "<h1>empty</h1>";

        //link to the previous page
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
    } else {
        // if all the fields are filled (not empty) 

        //insert data to database	
        // $result = mysqli_query($mysqli, "INSERT INTO users(name,age,email) VALUES('$name','$age','$email')");
        getDateForSpecificDayBetweenDates($lec_id, $start_date, $end_date, $day_name, $module_select, $s_time, $e_time);
        //display success message
        echo "<font color='green'>Data added successfully.";
        echo "<br/><a href='index.php'>View Result</a>";
    }
}


function getDateForSpecificDayBetweenDates($lecId, $startDate, $endDate, $day_number, $module_select, $timeStart, $timeEnd)
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

        $sql = "INSERT INTO `timetable`(`lecturerId`,`module`,`color`,`start`, `end`) VALUES ('$lecturerId','$module_select','$color','$s','$e') ";

        if (mysqli_query($connection, $sql)) {
            echo "Records inserted successfully.";
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($connection);
        }
    }
}

// function get_modules($sem, $dept)
// {
//     global $connection;
//     $get_modules_list = "SELECT moduleCode,moduleTitle FROM modules WHERE semester=' " . $sem . " ' AND departmentId=' " . $dept . " ' ORDER BY moduleCode";
//     $run_module_list = mysqli_query($connection, $get_modules_list);
//     while ($row_module_list = mysqli_fetch_array($run_module_list)) {
//         // $teacher_name = $row_module_list['moduleCode'];
//         // echo "<option>$teacher_name</option>";
//         echo "<option value=' " . $row_module_list['moduleCode'] . " '> " . $row_module_list['moduleCode'] . " - " . $row_module_list['moduleTitle'] . " </option>";
//     }
// }

?>


<!-- =========================================== -->


<!DOCTYPE html>
<!-- 
Template Name: Pinkman - Responsive Bootstrap 4 Admin Dashboard Template
Author: Hencework

License: You must have a valid license purchased only from themeforest to legally use the template for your project.
-->
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#modules").change(function() {
                var aid = $("#modules").val();
                $.ajax({
                    url: 'data_post.php',
                    method: 'post',
                    data: 'aid=' + aid
                }).done(function(modules) {
                    console.log(modules);
                    modules = JSON.parse(modules);
                    $('#lecturer_list').empty();
                    modules.forEach(function(module) {
                        $('#lecturer_list').append('<option value=' + module.lecturerId + '>' + module.lecturerName + '</option>')
                    })
                })
            })
        })
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
            <a class="navbar-brand" href="dashboard1.html">
                <img class="brand-img d-inline-block" src="dist/img/logo-dark.png" alt="brand" />
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
                                <span>Madelyn Shane<i class="zmdi zmdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
                        <a class="dropdown-item" href="#"><i class="dropdown-icon zmdi zmdi-settings"></i><span>Settings</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"><i class="dropdown-icon zmdi zmdi-power"></i><span>Log out</span></a>
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
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#dash_drp">
                                <i class="ion ion-ios-keypad"></i>
                                <span class="nav-link-text">Manage Lectures</span>
                            </a>
                            <ul id="dash_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item active">
                                            <a class="nav-link" href="set_timetable.php">Add Lectures</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="delete_timetable.php">Delete Lectures</a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link" href="dashboard3.html">Add Lecturers</a>
                                        </li> -->
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link link-with-badge" href="javascript:void(0);" data-toggle="collapse" data-target="#app_drp">
                                <i class="ion ion-ios-apps"></i>
                                <span class="nav-link-text">Manage Students</span>
                                <!-- <span class="badge badge-primary badge-pill">4</span> -->
                            </a>
                            <ul id="app_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="chats.html">Add Students</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="calendar.html">Update Student</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link link-with-badge" href="javascript:void(0);" data-toggle="collapse" data-target="#app_drp">
                                <i class="ion ion-ios-apps"></i>
                                <span class="nav-link-text">Manage Lecturers</span>
                                <!-- <span class="badge badge-primary badge-pill">4</span> -->
                            </a>
                            <ul id="app_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="chats.html">Add Lecturers</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="calendar.html">Update Lecturers</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    </ul>
                    <hr class="nav-separator">

                </div>
            </div>
        </nav>
        <!-- /Vertical Nav -->

        <!-- Setting Panel -->
        <!-- <div class="hk-settings-panel">
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
        </div> -->
        <!-- /Setting Panel -->

        <!-- Main Content -->
        <div class="hk-pg-wrapper">
            <!-- Container -->
            <div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
                <!-- Row -->
                <div class="row">
                    <a href="logout.php">logout Admin</a>
                    <br><br>
                    <form role="form" method="post" action="">
                        Semester Start Date <input type="date" name="start_date" value="<?php echo $sem_info_val['sem_start_date']; ?>"><br>
                        Semester End Date <input type="date" name="end_date" value="<?php echo $sem_info_val['sem_end_date']; ?>"><br>
                        <br>
                        <br>

                        <select name="day_name">
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                            <option value="7">Sunday</option>
                        </select>


                        <input name="s_time" type="time">
                        <input name="e_time" type="time">


                        <select id="modules" name="modules">
                            <option selected="" disabled="">Select Module</option>
                            <?php
                            require 'data_post.php';
                            $row_module_lists = loadModule($sem, $dept);
                            foreach ($row_module_lists as $row_module_list) {
                                echo "<option id='" . $row_module_list['moduleCode'] . "' value='" . $row_module_list['moduleCode'] . "'>" . $row_module_list['moduleCode'] . " - " . $row_module_list['moduleTitle'] . "</option>";
                            }
                            ?>
                        </select>


                        <select id="lecturer_list" name="lecturer_list"></select>

                        <!-- <form role="form" method="post" action="">
                    
                </form> -->

                        <input name="Submit" type="submit">
                    </form>
                </div>
                <!-- /Row -->
            </div>
            <!-- /Container -->

            <!-- Footer -->
            <div class="hk-footer-wrap container">
                <footer class="footer">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <p>Pampered by<a href="https://hencework.com/" class="text-dark" target="_blank">Hencework</a> © 2019</p>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <p class="d-inline-block">Follow us</p>
                            <a href="#" class="d-inline-block btn btn-icon btn-icon-only btn-indigo btn-icon-style-4"><span class="btn-icon-wrap"><i class="fa fa-facebook"></i></span></a>
                            <a href="#" class="d-inline-block btn btn-icon btn-icon-only btn-indigo btn-icon-style-4"><span class="btn-icon-wrap"><i class="fa fa-twitter"></i></span></a>
                            <a href="#" class="d-inline-block btn btn-icon btn-icon-only btn-indigo btn-icon-style-4"><span class="btn-icon-wrap"><i class="fa fa-google-plus"></i></span></a>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- /Footer -->
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

    <!-- Apex JavaScript -->
    <script src="vendors/apexcharts/dist/apexcharts.min.js"></script>
    <script src="dist/js/irregular-data-series.js"></script>

    <!-- Init JavaScript -->
    <script src="dist/js/init.js"></script>
    <script src="dist/js/dashboard-data.js"></script>

</body>

</html>