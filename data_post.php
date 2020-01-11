<?php

class DbConnect
{
    private $host = 'localhost';
    private $dbName = 'ltss_db';
    private $user = 'root';
    private $pass = '';

    public function connect()
    {
        try {
            $conn = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo 'Database Error: ' . $e->getMessage();
        }
    }
}

if (isset($_POST['semesterno'])) {
    $db = new DbConnect;
    $conn = $db->connect();


    $stmt = $conn->prepare("SELECT module_conduct.lecturerId, modules.moduleCode,modules.moduleTitle, modules.semester FROM module_conduct INNER JOIN modules ON module_conduct.moduleId = modules.moduleId WHERE lecturerId='" . $_POST['lecid'] . "' AND semester='" . $_POST['semesterno'] . "'");

    // $stmt = $conn->prepare(" SELECT lecturers.lecturerName, lecturers.lecturerId FROM lecturers INNER JOIN (SELECT module_conduct.lecturerId, modules.moduleCode FROM module_conduct INNER JOIN modules ON module_conduct.moduleId = modules.moduleId ) AS R2 ON lecturers.lecturerId=R2.lecturerId WHERE moduleCode='" . $_POST['semesterno'] . "'");
    $stmt->execute();
    $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($modules);
}


function loadModule($sem, $dept)
{

    // $sem = '6';
    // $dept = '1';


    $db = new DbConnect;
    $conn = $db->connect();

    $stmt = $conn->prepare(" SELECT moduleCode,moduleTitle FROM modules WHERE semester=' " . $sem . " ' AND departmentId=' " . $dept . " ' ORDER BY moduleCode ");
    $stmt->execute();
    $module_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $module_list;
}

function loadSem()
{

    // $sem = '6';
    // $dept = '1';


    $db = new DbConnect;
    $conn = $db->connect();

    $stmt = $conn->prepare(" SELECT semester FROM sem_info ORDER BY semester ASC ");
    $stmt->execute();
    $module_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $module_list;
}

function loadLocation(){
    $db = new DbConnect;
    $conn = $db->connect();

    $stmt = $conn->prepare(" SELECT * FROM `locations` ");
    $stmt->execute();
    $location_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $location_list;
}
