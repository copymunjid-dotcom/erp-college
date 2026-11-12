<?php

session_start();

if(!isset($_SESSION["staff_id"])){

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

$date = "";

if(isset($_GET["attendance_date"])){

    $date = $_GET["attendance_date"];

}
$stmt = mysqli_prepare(

$conn,

"SELECT

c.class_name,
d.department_name,
y.year_name,
s.subject_name

FROM staff_subjects ss

INNER JOIN classes c
ON ss.class_id = c.class_id

INNER JOIN departments d
ON c.department_id = d.department_id

INNER JOIN years y
ON c.year_id = y.year_id

INNER JOIN subjects s
ON ss.subject_id = s.subject_id

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

$class = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>
<!DOCTYPE html>

<html>

<head>

<title>

View Attendance

</title>

<link
rel="stylesheet"
href="style.css">

</head>

<body>

<div class="form-container">

<h1>

View Attendance

</h1>

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
<form method="GET">

<input
type="hidden"
name="class_id"
value="<?php echo $class_id; ?>">

<input
type="hidden"
name="subject_id"
value="<?php echo $subject_id; ?>">

<label>

Attendance Date

</label>

<input

type="date"

name="attendance_date"

required

value="<?php echo htmlspecialchars($date); ?>">

<button
type="submit">

Search

</button>

</form>
<?php

if($date!=""){

$stmt=mysqli_prepare(

$conn,

"SELECT

st.register_number,
st.student_name,
a.status

FROM attendance a

INNER JOIN students st
ON a.student_id=st.student_id

WHERE

a.class_id=?
AND a.subject_id=?
AND a.attendance_date=?

ORDER BY st.register_number"

);

mysqli_stmt_bind_param(

$stmt,

"iis",

$class_id,
$subject_id,
$date

);

mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);

?>

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

Status

</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<?php echo htmlspecialchars($row["register_number"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["student_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["status"]); ?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="3">

No attendance found.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<?php

}

?>
</div>

</body>

</html>