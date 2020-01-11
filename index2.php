<?php

session_start();

// $_SESSION['id_student']='EG2865';

if (empty($_SESSION['id_student']) && empty($_SESSION['id_lecturer'])) {
    
    echo "<h1>" . $_SESSION['id_student'] . "</h1>";
    ?>

    <li><a href="login_student.php">Student-log</a></li>

    <li><a href="login_lecturer.php">Lecturer-log</a></li>

    <li><a href="#">Sign-Up</a>
        <div><a href="reg_student.php">Student-reg</a>
            <a href="reg_lecturer.php">Lecturer-reg</a>
        </div>
    </li>




    <?php } else {

        if (isset($_SESSION['id_student'])) {
            ?>
        <li><a href="dashboard_student.php">Dashboard</a></li>

    <?php
        } else if (isset($_SESSION['id_lecturer'])) {
            ?>

        <li><a href="dashboard_lecturer.php">Dashboard</a></li>

    <?php } ?>

    <li><a href="logout.php">Logout</a></li>

<?php } ?>