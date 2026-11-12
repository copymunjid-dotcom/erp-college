<?php

session_start();

if(
    !isset($_SESSION["role_id"]) ||
    $_SESSION["role_id"] != 3
){
    header("Location: login.php");
    exit();
}

require "database.php";

if(!isset($_GET["id"])){

    header("Location: manage_classes.php");
    exit();

}

$class_id = intval($_GET["id"]);
$stmt = mysqli_prepare(

    $conn,

    "SELECT

    c.class_name,
    c.section,

    d.department_name,
    d.program_level,

    y.year_name,

    s.staff_name

    FROM classes c

    LEFT JOIN departments d
    ON c.department_id=d.department_id

    LEFT JOIN years y
    ON c.year_id=y.year_id

    LEFT JOIN staff s
    ON c.class_advisor=s.staff_id

    WHERE c.class_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $class_id

);

mysqli_stmt_execute($stmt);

$class = mysqli_stmt_get_result($stmt);

$class = mysqli_fetch_assoc($class);

if(!$class){

    die("Class not found.");

}
$stmt = mysqli_prepare(

    $conn,

    "SELECT COUNT(*) AS total

    FROM students

    WHERE class_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $class_id

);

mysqli_stmt_execute($stmt);

$count = mysqli_stmt_get_result($stmt);

$count = mysqli_fetch_assoc($count);
$stmt = mysqli_prepare(

    $conn,

    "SELECT

    student_id,

    register_number,

    student_name,

    email

    FROM students

    WHERE class_id=?

    ORDER BY student_name"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $class_id

);

mysqli_stmt_execute($stmt);

$students = mysqli_stmt_get_result($stmt);
?>


<!DOCTYPE html>

<html>

<head>

<title>

View Class

</title>

<link
rel="stylesheet"
href="style.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="container">

<a
href="manage_classes.php"
class="home-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

View Class

</h1>
<div class="class-details-card">

<h2>

Class Information

</h2>

<div class="details-grid">

<div>

<strong>📚 Course</strong>

<p><?php echo htmlspecialchars($class["department_name"]); ?></p>

</div>

<div>

<strong>🏷 Class</strong>

<p><?php echo htmlspecialchars($class["class_name"]); ?></p>

</div>

<div>

<strong>🎓 Program Level</strong>

<p><?php echo htmlspecialchars($class["program_level"]); ?></p>

</div>

<div>

<strong>📅 Year</strong>

<p><?php echo htmlspecialchars($class["year_name"]); ?></p>

</div>

<div>

<strong>🅱 Section</strong>

<p><?php echo htmlspecialchars($class["section"]); ?></p>

</div>

<div>

<strong>👨‍🏫 Class Advisor</strong>

<p>

<?php echo htmlspecialchars($class["staff_name"] ?? "Not Assigned"); ?>

</p>

</div>

</div>

</div>
<div class="summary-row">
<div class="summary-card">

<i class="fa-solid fa-user-graduate"></i>



<h2>

<?php echo $count["total"]; ?>

</h2>

</div>
<a

href="assign_students.php?class_id=<?php echo $class_id; ?>"


class="add-btn">

<i class="fa-solid fa-user-plus"></i>


</a>
</div>
<table class="data-table">

<thead>

<tr>

<th>

Register No

</th>

<th>

Student Name

</th>

<th>

Email

</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($students)>0){

while($row=mysqli_fetch_assoc($students)){

?>

<tr>

<td>

<?php echo htmlspecialchars($row["register_number"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["student_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["email"]); ?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="3">

No students assigned.

</td>

</tr>

<?php

}

?>

</tbody>

</table>
</div>

</body>

</html>