<?php
include("database.php");
// $conn = mysqli_conn("localhost", "root", "", "jobportal");
$output = '';
if (isset($_POST["query"])) {
    $search = mysqli_real_escape_string($connection, $_POST["query"]);
    $query = "
	SELECT * FROM students 
	WHERE studentId LIKE '%" . $search . "%'
	OR studentName LIKE '%" . $search . "%'
	";
} else {
    $query = "SELECT * FROM students ORDER BY studentId";
    // // $query = '';
    // $query = 0;
    // $result = "";
    // // $query = NULL;
    // // $query = FALSE;
}
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    $output .= '<div class="table-responsive">
					<table class="table table bordered">
						<tr>
							<th>Student Id</th>
							<th>Student Name</th>
							<th>Department ID</th>
							<th>semestere</th>
							<th>email</th>
							
						</tr>';
    while ($row = mysqli_fetch_array($result)) {
        $output .= '
			<tr>
				<td>' . $row["studentId"] . '</td>
				<td>' . $row["studentName"] . '</td>
				<td>' . $row["departmentId"] . '</td>
				<td>' . $row["semester"] . '</td>
				<td>' . $row["email"] . '</td>
				<td>
					<button onclick="GetUserDetails('.$row['id'].')" class="btn btn-warning">Update</button>
				</td>
				<td>
					<button onclick="DeleteUser('.$row['id'].')" class="btn btn-danger">Delete</button>
				</td>
			</tr>
		';
    }
    echo $output;
} else {
    echo 'Data Not Found';
}
