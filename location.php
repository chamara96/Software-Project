<?php
include("database.php");

if (isset($_POST['start_time'])) {
    $s_time = $_POST['start_time'];
    $e_time = $_POST['end_time'];
    // $event_id = $_POST['eventid'];

    // $location_list = mysqli_query($connection, " SELECT * FROM timetable INNER JOIN locations USING(locationId) WHERE (start BETWEEN '" . $s_time . "'  AND '" . $e_time . "') OR (end BETWEEN '" . $s_time . "'  AND '" . $e_time . "') ");
    $location_list = mysqli_query($connection, " SELECT * FROM locations WHERE code NOT IN ( SELECT code FROM timetable INNER JOIN locations USING(locationId) WHERE  ( (start > '" . $s_time . "' AND start < '" . $e_time . "') OR (end > '" . $s_time . "' AND end < '" . $e_time . "') OR (start <= '" . $s_time . "' AND end >= '" . $e_time . "') ) )  ");
    // $row_location_list = mysqli_fetch_array($location_list);

    while ($row = mysqli_fetch_assoc($location_list)) {
        $events[] = $row;
    }
    echo json_encode($events);

    exit;
}

if ($_POST['action'] == "changelocation") {
    // header('Content-Type: application/json');
    mysqli_query($connection, " UPDATE `timetable` SET `locationId`='" . $_POST['newloc'] . "' WHERE id='" . $_POST["id"] . "' ");
    // $updated_row = mysqli_query($connection, " SELECT * FROM `timetable` INNER JOIN `locations` USING (locationId) WHERE id='" . $_POST["id"] . "' ");
    // $array_updated_row = mysqli_fetch_assoc($updated_row);
    // echo json_encode($array_updated_row);
    // header('location:dashboard_lecturer.php?view=1');
    exit;
} elseif ($_POST['action'] == "addnote") {
    mysqli_query($connection, " UPDATE `timetable` SET `note`='" . $_POST['note'] . "' WHERE id='" . $_POST["id"] . "' ");
    exit;
} elseif ($_POST['action'] == "refresh") {
    $updated_row = mysqli_query($connection, " SELECT * FROM `timetable` INNER JOIN `locations` USING (locationId) WHERE id='" . $_POST["id"] . "' ");
    $array_updated_row = mysqli_fetch_assoc($updated_row);
    echo json_encode($array_updated_row);
    exit;
}
