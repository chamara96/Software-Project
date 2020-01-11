<?php
include("database.php");

if (isset($_POST['submitStd'])) {
    $studentId = $_POST['studentId'];
    $studentName = $_POST['studentName'];
    $departmentId = $_POST['departmentId'];
    $semester = $_POST['semester'];
    $email_std = $_POST['email_std'];

    mysqli_query($connection, "INSERT INTO `students` (
        `studentId` ,
        `studentName` ,
        `departmentId` ,
        `semester`,
        `email`
        )
        VALUES (
        '" . $studentId . "',
        '" . $studentName . "',
        '" . $departmentId . "',
        '" . $semester . "',
        '" . $email_std . "'
        )");
    header('Content-Type: application/json');
    echo '{"id":"' . mysqli_insert_id($connection) . '"}';
    header('location:index.php');
    exit;
}

if (isset($_POST['submitLec'])) {
    $lecturerId = $_POST['lecturerId'];
    $lecturerName = $_POST['lecturerName'];
    $departmentId = $_POST['departmentId'];
    $email_lec = $_POST['email_lec'];

    mysqli_query($connection, "INSERT INTO `lecturers` (
                `lecturerId` ,
                `lecturerName` ,
                `departmentId` ,
                `email`
                )
                VALUES (
                '" . $lecturerId . "',
                '" . $lecturerName . "',
                '" . $departmentId . "',
                '" . $email_lec . "'
                )");
    header('Content-Type: application/json');
    echo '{"id":"' . mysqli_insert_id($connection) . '"}';
    header('location:index.php');

    exit;
}

// if (isset($_POST['submit'])) {
//     if ($_POST['submit'] == "Add Student") {
//         $studentId = $_POST['studentId'];
//         $studentName = $_POST['studentName'];
//         $departmentId = $_POST['departmentId'];
//         $semester = $_POST['semester'];
//         $email_std = $_POST['email_std'];

//         mysqli_query($connection, "INSERT INTO `students` (
//             `studentId` ,
//             `studentName` ,
//             `departmentId` ,
//             `semester`,
//             `email`
//             )
//             VALUES (
//             '" . $studentId . "',
//             '" . $studentName . "',
//             '" . $departmentId . "',
//             '" . $semester . "',
//             '" . $email_std . "'
//             )");
//         header('Content-Type: application/json');
//         echo '{"id":"' . mysqli_insert_id($connection) . '"}';
//         exit;
//     } elseif (isset($_POST['submitL'])) {
//         $lecturerId = $_POST['lecturerId'];
//         $lecturerName = $_POST['lecturerName'];
//         $departmentId = $_POST['departmentId'];
//         $email_lec = $_POST['email_lec'];

//         mysqli_query($connection, "INSERT INTO `lectureres` (
//             `lecturerId` ,
//             `lecturerName` ,
//             `departmentId` ,
//             `email`
//             )
//             VALUES (
//             '" . $lecturerId . "',
//             '" . $lecturerName . "',
//             '" . $departmentId . "',
//             '" . $email_lec . "'
//             )");
//         header('Content-Type: application/json');
//         echo '{"id":"' . mysqli_insert_id($connection) . '"}';

//         exit;
//     }
// }
