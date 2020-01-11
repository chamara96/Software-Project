<?php
session_start();
include("database.php");


if ((isset($_SESSION['logged_regNo']) && isset($_SESSION['logged_roleType'])) || isset($_COOKIE['logging'])) {
	if ($_SESSION['logged_roleType'] == '1') {
		header('location:dashboard_admin.php');
	} elseif ($_SESSION['logged_roleType'] == '2') {
		header('location:dashboard_student.php');
	} elseif ($_SESSION['logged_roleType'] == '3') {
		header('location:dashboard_lecturer.php');
	}
} else {
	$_SESSION['logged'] = 'error';
	$error_log = "Please Login";
}


if (isset($_POST['submit'])) {
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	$keepLog = isset($_POST['keeplogin']);

	$login_details = mysqli_query($connection, " SELECT regNo, roleType FROM login WHERE username='" . $username . "' AND password='" . $password . "' ");
	$login_details_vals = mysqli_fetch_assoc($login_details);

	$regNo = $login_details_vals['regNo'];
	$roleType = $login_details_vals['roleType'];



	if (!$regNo == "" && $roleType == "2") {
		$_SESSION['logged_roleType'] = $roleType;
		$_SESSION['logged_regNo'] = $regNo;
		setcookie('logging', $keepLog, time() + 60 * 10);
		header('location:dashboard_student.php');
	} elseif (!$regNo == "" && $roleType == "3") {
		$_SESSION['logged_roleType'] = $roleType;
		$_SESSION['logged_regNo'] = $regNo;
		setcookie('logging', $keepLog, time() + 60 * 10);
		header('location:dashboard_lecturer.php');
	} elseif (!$regNo == "" && $roleType == "1") {
		$_SESSION['logged_roleType'] = $roleType;
		$_SESSION['logged_regNo'] = $regNo;
		setcookie('logging', $keepLog, time() + 60 * 10);
		header('location:dashboard_admin.php');
	} else {
		$_SESSION['logged'] = 'error';
		$error_log = "Error Login";
	}
}
?>



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
	<title>LTSS || Login</title>
	<meta name="description" content="A responsive bootstrap 4 admin dashboard template by hencework" />

	<!-- Favicon -->
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="icon" href="favicon.ico" type="image/x-icon">

	<!-- Toggles CSS -->
	<link href="vendors/jquery-toggles/css/toggles.css" rel="stylesheet" type="text/css">
	<link href="vendors/jquery-toggles/css/themes/toggles-light.css" rel="stylesheet" type="text/css">

	<!-- Custom CSS -->
	<link href="dist/css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<!-- Preloader -->
	<div class="preloader-it">
		<div class="loader-pendulums"></div>
	</div>
	<!-- /Preloader -->

	<!-- HK Wrapper -->
	<div class="hk-wrapper">

		<!-- Main Content -->
		<div class="hk-pg-wrapper hk-auth-wrapper">
			<!-- <header class="d-flex justify-content-end align-items-center">
					<div class="btn-group btn-group-sm">
						<a href="#" class="btn btn-outline-secondary">Help</a>
						<a href="#" class="btn btn-outline-secondary">About Us</a>
					</div>
				</header> -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-xl-12 pa-0">
						<div class="auth-form-wrap pt-xl-0 pt-70">
							<div class="auth-form w-xl-30 w-lg-55 w-sm-75 w-100">
								<a class="auth-brand text-center d-block mb-20" href="#">
									<img class="brand-img" src="img/welcome.png" alt="brand" />
								</a>


								<form action="" method="POST">
									<h1 class="display-4 text-center mb-10">LTSS :)</h1>
									<p class="text-center mb-30">Sign in to your account.</p>
									<div class="form-group">
										<input class="form-control" name="username" placeholder="Username" type="text">
									</div>
									<div class="form-group">
										<div class="input-group">
											<input class="form-control" name="password" placeholder="Password" type="password">
											<div class="input-group-append">
												<span class="input-group-text"><span class="feather-icon"><i data-feather="eye-off"></i></span></span>
											</div>
										</div>
									</div>
									<div class="custom-control custom-checkbox mb-25">
										<input class="custom-control-input" name="keeplogin" id="same-address" type="checkbox" checked>
										<label class="custom-control-label font-14" for="same-address">Keep me logged in</label>
									</div>
									<button class="btn btn-pink btn-block" name="submit" type="submit">Login</button>
									<!-- <p class="font-14 text-center mt-15">Having trouble logging in?</p> -->
									<!-- <div class="option-sep"></div> -->
									<!-- <div class="form-row">
											<div class="col-sm-6 mb-20"><button class="btn btn-indigo btn-block btn-wth-icon"> <span class="icon-label"><i class="fa fa-facebook"></i> </span><span class="btn-text">Login with facebook</span></button></div>
											<div class="col-sm-6 mb-20"><button class="btn btn-primary btn-block btn-wth-icon"> <span class="icon-label"><i class="fa fa-twitter"></i> </span><span class="btn-text">Login with Twitter</span></button></div>
										</div> -->
									<p class="text-center"><?php if (isset($error_log)) {
																echo $error_log;
															} ?></p>
								</form>



							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Main Content -->

	</div>
	<!-- /HK Wrapper -->

	<!-- JavaScript -->

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

	<!-- Init JavaScript -->
	<script src="dist/js/init.js"></script>
</body>

</html>