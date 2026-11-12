<?php

session_start();

require "database.php";

$staff_id=$_SESSION["staff_id"];

$stmt=mysqli_prepare(

$conn,

"SELECT

ss.class_id,

ss.subject_id,

c.class_name,

s.subject_name,

d.department_name

FROM staff_subjects ss

JOIN classes c
ON ss.class_id=c.class_id

JOIN subjects s
ON ss.subject_id=s.subject_id

JOIN departments d
ON c.department_id=d.department_id

WHERE ss.staff_id=?"

);

mysqli_stmt_bind_param(

$stmt,

"i",

$staff_id

);

mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>

<html>

<head>

<title>

Manage Attendance

</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

<h1>

Manage Attendance

</h1>

<table class="attendance-table">

<thead>

<tr>

<th>Class</th>

<th>Subject</th>

<th>Course</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo htmlspecialchars($row["class_name"]); ?></td>

<td><?php echo htmlspecialchars($row["subject_name"]); ?></td>

<td><?php echo htmlspecialchars($row["department_name"]); ?></td>

<td>

<a
class="action-btn"
href="take_attendance.php?class_id=<?php echo $row["class_id"]; ?>&subject_id=<?php echo $row["subject_id"]; ?>">

Take Attendance

</a>

<br><br>

<a
class="action-btn"
href="view_attendance.php?class_id=<?php echo $row["class_id"]; ?>&subject_id=<?php echo $row["subject_id"]; ?>">

View Attendance

</a>

</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>

</html>