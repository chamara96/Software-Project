<?php
// $final_module_not_in = 'abcd';
include("database.php");
session_start();

$my_selected_tech = $_GET['myModule'];
$lecturerId = $_GET['myId'];
// $my_selected_tech = "EE6207";
// $lecturerId = "egle04";


if (isset($_GET['myModule'])) {

    // $my_selected_tech = $_POST['tech_moduleCode'];
    // $lecturerId = $_POST['lec_id'];


    $all_tech_modules = mysqli_query($connection, " SELECT moduleCode FROM modules WHERE isTechnical=1 ");
    $my_tech_modules = mysqli_query($connection, " SELECT m.moduleTitle, m.moduleCode FROM modules m INNER JOIN module_conduct c ON m.moduleId=c.moduleId WHERE c.lecturerId='" . $lecturerId . "' AND m.isTechnical=1 ");

    while ($row = mysqli_fetch_array($all_tech_modules)) {
        $sub_array = $row['moduleCode'];

        $cross_tech = mysqli_query($connection, " SELECT t1.studentId FROM (SELECT studentId FROM enrollment WHERE moduleCode='" . $my_selected_tech . "') t1 INNER JOIN (SELECT studentId FROM enrollment WHERE moduleCode='" . $sub_array . "') t2 ON t1.studentId=t2.studentId ");
        $row_num = mysqli_num_rows($cross_tech);


        if ($row_num == 0) {
            $final_module_not_in .= ",'" . $sub_array . "'";
        }
    }

    $final_module_not_in = substr($final_module_not_in, 1);
    // echo $final_module_not_in;

    $_SESSION['final_module_not_in'] = $final_module_not_in;

    header('location:dashboard_lecturer.php');
}

if (isset($_POST['clearSes'])) {
    $_SESSION['final_module_not_in'] = "'non'";
    header('location:dashboard_lecturer.php');
}
