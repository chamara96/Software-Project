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
            <!-- Container -->
            <div class="container-fluid mt-xl-50 mt-sm-30 mt-15">

                <div class="row">
                    <form>
                        <div class="position-relative">
                            <!-- <a href="javascript:void(0);" class="navbar-search-icon"><i class="ion ion-ios-search"></i></a> -->
                            <input type="text" id="search_text" placeholder="Search for..." class="form-control">
                            <!-- <a id="navbar_search_close" class="navbar-search-close" href="#"><i class="ion ion-ios-close"></i></a> -->
                        </div>
                    </form>
                </div>

                <div class="row">
                    <!-- <input type="text" id="search_text" placeholder="Search for..." aria-label="Search"> -->

                    <div id="searchbox" class="card shadow mb-4">
                        <div class="card-header py-3">
                            Search Results
                        </div>
                        <div class="card-body">
                            <div id="result"></div>
                        </div>
                    </div>

                </div>
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





    <!-- <script src="js/admin_function.js"></script> -->


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