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
////add student
if (isset($_POST['SubmitS'])) {
    $studentId = mysqli_real_escape_string($connection, $_POST['studentId']);
    $studentName = mysqli_real_escape_string($connection, $_POST['studentName']);
    $departmentId = mysqli_real_escape_string($connection, $_POST['departmentId']);
    $semester = mysqli_real_escape_string($connection, $_POST['semester']);
    $email1 = mysqli_real_escape_string($connection, $_POST['email1']);


    // checking empty fields
    if (empty($studentId) || empty($studentName) || empty($departmentId) || empty($semester) || empty($email1)) {

        if (empty($studentId)) {
            echo "<font color='red'>Registation field is empty.</font><br/>";
        }

        if (empty($studentName)) {
            echo "<font color='red'>Name field is empty.</font><br/>";
        }

        if (empty($departmentId)) {
            echo "<font color='red'>Email field is empty.</font><br/>";
        }

        if (empty($semester)) {
            echo "<font color='red'>Semester field is empty.</font><br/>";
        }
        if (empty($email1)) {
            echo "<font color='red'>Email field is empty.</font><br/>";
        }
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
    } else {
        $result = mysqli_query($connection, "INSERT INTO student(studentid,studentName,departmentId,semester,email) VALUES('$studentId','$studentName','$departmentId','$semester','$email1')");
        echo "<font color='green'>Data added successfully.";
        // if all the fields are filled (not empty) 

        //insert data to database	


        //display success message
        ////echo "<font color='green'>Data added error.password is not matched";
        //echo "<br/><a href='index.php'>View Result</a>";
    }
}


///add lecture
if (isset($_POST['submitL'])) {
    $lecturerId = mysqli_real_escape_string($connection, $_POST['lecturerId']);
    $lecturerName = mysqli_real_escape_string($connection, $_POST['lecturerName']);
    $departmentId = mysqli_real_escape_string($connection, $_POST['departmentId']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);


    // checking empty fields
    if (empty($lecturerId) || empty($lecturerName) || empty($departmentId) || empty($email)) {

        if (empty($lecturerId)) {
            echo "<font color='red'>Registation field is empty.</font><br/>";
        }

        if (empty($lecturerName)) {
            echo "<font color='red'>Name field is empty.</font><br/>";
        }

        if (empty($departmentId)) {
            echo "<font color='red'>Department field is empty.</font><br/>";
        }

        if (empty($email)) {
            echo "<font color='red'>Email field is empty.</font><br/>";
        }
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
    } else {
        $result = mysqli_query($connection, "INSERT INTO lecturers (lecturerId,lecturerName,departmentId,email) VALUES('$lecturerId','$lecturerName','$departmentId','$email')");
        echo "<font color='green'>Data added successfully.";
        // if all the fields are filled (not empty) 

        //insert data to database	


        //display success message
        ////echo "<font color='green'>Data added error.password is not matched";
        //echo "<br/><a href='index.php'>View Result</a>";
    }
}

?>





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
                <img class="brand-img d-inline-block" src="img/dashboard6.png" alt="brand" /> <!-- chanege logo -->
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

                        <!-- <li class="nav-item active">
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
                                        <li class="nav-item">
                                            <a class="nav-link" href="dashboard3.html">Add Lecturers</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li> -->

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
                                            <a class="nav-link" data-toggle="modal" data-target="#ModalFormsAddStudent">Add Students</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="modal" data-target="#ModalFormsUpdateStudent">Update Student</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link link-with-badge" href="javascript:void(0);" data-toggle="collapse" data-target="#auth_drp">
                                <i class="ion ion-ios-apps"></i>
                                <span class="nav-link-text">Manage Lecturers</span>
                                <!-- <span class="badge badge-primary badge-pill">4</span> -->
                            </a>
                            <ul id="auth_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="modal" data-target="#ModalFormsAddLecturer">Add Lecturers</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="modal" data-target="#ModalFormsUpdateLecturer">Update Lecturers</a>
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

        <!-- Main Content -->
        <div class="hk-pg-wrapper">

            <div class="container">

                <div class="row">
                    <div class="col-sm">

                        <input type="text" id="search_text" placeholder="Search for..." aria-label="Search">


                        <div id="searchbox">
                            <div>
                                Search Results
                            </div>
                            <div>
                                <div id="result"></div>
                            </div>
                        </div>



                    </div>
                </div>

                <hr class="nav-separator">


            </div>

            <br><br>


        </div>


    </div>


    <!-- Modal forms Add Student-->
    <div class="modal fade" id="ModalFormsAddStudent" tabindex="-1" role="dialog" aria-labelledby="ModalFormsAddStudent" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="admin_function.php" method="POST" id="addnewstudent">
                        <div class="form-group">
                            <label for="exampleDropdownFormEmail1">Student Reg no.</label>
                            <input type="text" class="form-control" name="studentId">
                        </div>
                        <div class="form-group">
                            <label for="exampleDropdownFormPassword1">Student Name</label>
                            <input type="text" class="form-control" name="studentName">
                        </div>
                        <div class="form-group">
                            <label for="exampleDropdownFormPassword1">Department</label>
                            <select class="form-control" name="departmentId">
                                <?php $result = mysqli_query($connection, " SELECT departmentName,departmentId FROM department ");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value=" . $row['departmentId'] . ">" . $row['departmentName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleDropdownFormPassword1">Current Semester</label>
                            <select class="form-control" name="semester">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleDropdownFormEmail1">Email</label>
                            <input type="email" class="form-control" name="email_std">
                        </div>

                        <input type="submit" name="submitStd" value="Add Student" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal forms Update Student-->
    <div class="modal fade" id="ModalFormsUpdateStudent" tabindex="-1" role="dialog" aria-labelledby="ModalFormsUpdateStudent" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleDropdownFormEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
                        </div>
                        <div class="form-group">
                            <label for="exampleDropdownFormPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
                        </div>
                        <div class="custom-control custom-checkbox mb-10">
                            <input type="checkbox" class="custom-control-input" id="customChk">
                            <label class="custom-control-label" for="customChk">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal forms Add lecturer-->
    <div class="modal fade" id="ModalFormsAddLecturer" tabindex="-1" role="dialog" aria-labelledby="ModalFormsAddLecturer" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Lecturer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="admin_function.php" method="POST">
                        <div class="form-group">
                            <label for="exampleDropdownFormEmail1">Lecturer Reg no.</label>
                            <input type="text" class="form-control" name="lecturerId">
                        </div>
                        <div class="form-group">
                            <label for="exampleDropdownFormPassword1">Lecturer Name</label>
                            <input type="text" class="form-control" name="lecturerName">
                        </div>
                        <div class="form-group">
                            <label for="exampleDropdownFormPassword1">Department</label>
                            <select class="form-control" name="departmentId">
                                <?php $result = mysqli_query($connection, " SELECT departmentName,departmentId FROM department ");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value=" . $row['departmentId'] . ">" . $row['departmentName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleDropdownFormPassword1">Email</label>
                            <input type="text" class="form-control" name="email_lec">
                        </div>

                        <input type="submit" name="submitLec" value="Add Lecturer" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal forms Update lecturer-->
    <div class="modal fade" id="ModalFormsUpdateLecturer" tabindex="-1" role="dialog" aria-labelledby="ModalFormsUpdateLecturer" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleDropdownFormEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
                        </div>
                        <div class="form-group">
                            <label for="exampleDropdownFormPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
                        </div>
                        <div class="custom-control custom-checkbox mb-10">
                            <input type="checkbox" class="custom-control-input" id="customChk">
                            <label class="custom-control-label" for="customChk">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Sign in</button>
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

    <!--  -->

    <script>
        $(document).ready(function() {
            $("#searchbox").hide();
            load_data();

            function load_data(query) {
                $.ajax({
                    url: "search_admin.php",
                    method: "post",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('#result').html(data);
                    }
                });
            }

            $('#search_text').keyup(function() {
                var search = $(this).val();
                if (search != '') {
                    $("#searchbox").show();
                    load_data(search);
                } else {
                    // $("#result").attr("disabled", true);
                    $("#searchbox").hide();
                    // load_data();
                }
            });
        });
    </script>


    <script src="js/admin_function.js"></script>


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