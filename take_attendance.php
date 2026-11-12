<?php

session_start();

if(
    !isset($_SESSION["staff_id"])
){
    header("Location: login.php");
    exit();
}

require "database.php";

$staff_id = $_SESSION["staff_id"];

if(
    !isset($_GET["class_id"]) ||
    !isset($_GET["subject_id"])
){
    header("Location: manage_attendance.php");
    exit();
}

$class_id = intval($_GET["class_id"]);
$subject_id = intval($_GET["subject_id"]);

/* ===========================
   Check Staff Assignment
=========================== */

$stmt = mysqli_prepare(

$conn,

"SELECT

c.class_name,
d.department_name,
y.year_name,
s.subject_name

FROM staff_subjects ss

INNER JOIN classes c
ON ss.class_id=c.class_id

INNER JOIN departments d
ON c.department_id=d.department_id

INNER JOIN years y
ON c.year_id=y.year_id

INNER JOIN subjects s
ON ss.subject_id=s.subject_id

WHERE
ss.staff_id=?
AND ss.class_id=?
AND ss.subject_id=?"

);

mysqli_stmt_bind_param(

$stmt,

"iii",

$staff_id,
$class_id,
$subject_id

);

mysqli_stmt_execute($stmt);

$class_result=mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($class_result)==0){

    die(
        "staff_id = ".$staff_id.
        "<br>class_id = ".$class_id.
        "<br>subject_id = ".$subject_id
    );

}

$class=mysqli_fetch_assoc($class_result);

/* ===========================
   Load Students
=========================== */

$stmt=mysqli_prepare(

$conn,

"SELECT

student_id,
register_number,
student_name

FROM students

WHERE class_id=?

ORDER BY register_number"

);

mysqli_stmt_bind_param(

$stmt,

"i",

$class_id

);

mysqli_stmt_execute($stmt);

$students=mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

Take Attendance

</title>

<link
rel="stylesheet"
href="style.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="page-container">

<div class="form-container">

<h1>

Take Attendance

</h1>

<hr>

<div class="profile-card">

<h2>

<?php echo htmlspecialchars($class["class_name"]); ?>

</h2>

<p>

<strong>Course :</strong>

<?php echo htmlspecialchars($class["department_name"]); ?>

</p>

<p>

<strong>Year :</strong>

<?php echo htmlspecialchars($class["year_name"]); ?>

</p>

<p>

<strong>Subject :</strong>

<?php echo htmlspecialchars($class["subject_name"]); ?>

</p>

</div>

<form
method="POST"
action="save_attendance.php">

<input
type="hidden"
name="class_id"
value="<?php echo $class_id; ?>">

<input
type="hidden"
name="subject_id"
value="<?php echo $subject_id; ?>">

<label>

Attendance Date *

</label>

<input

type="date"

name="attendance_date"

required>

<br><br>

<table class="attendance-table">

<thead>

<tr>

<th>

Register No

</th>

<th>

Student Name

</th>

<th>

Present

</th>

<th>

Absent

</th>

</tr>

</thead>

<tbody>

<?php

while($student=mysqli_fetch_assoc($students)){

?>

<tr>

<td>

<?php echo htmlspecialchars($student["register_number"]); ?>

</td>

<td>

<?php echo htmlspecialchars($student["student_name"]); ?>

</td>

<td>

<input

type="radio"

name="attendance[<?php echo $student["student_id"]; ?>]"

value="Present"

required>

</td>

<td>

<input

type="radio"

name="attendance[<?php echo $student["student_id"]; ?>]"

value="Absent"

required>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<br>

<button
type="submit"
class="save-btn">

<i class="fa-solid fa-floppy-disk"></i>

Save Attendance

</button>

</form>

</div>

</div>

</body>

</html>