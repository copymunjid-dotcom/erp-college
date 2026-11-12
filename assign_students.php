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

if(!isset($_GET["class_id"])){

    header("Location:manage_classes.php");
    exit();

}

$class_id = intval($_GET["class_id"]);

// Get Class Details

$stmt = mysqli_prepare(

    $conn,

    "SELECT

    c.class_name,
    c.department_id,
    c.year_id,

    d.department_name,
    d.program_level,

    y.year_name

    FROM classes c

    LEFT JOIN departments d
    ON c.department_id=d.department_id

    LEFT JOIN years y
    ON c.year_id=y.year_id

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

// Assign Students

if($_SERVER["REQUEST_METHOD"]=="POST"){

    if(isset($_POST["students"])){

        foreach($_POST["students"] as $student_id){

            $stmt = mysqli_prepare(

                $conn,

                "UPDATE students
                 SET class_id=?
                 WHERE student_id=?"

            );

            mysqli_stmt_bind_param(

                $stmt,

                "ii",

                $class_id,

                $student_id

            );

            mysqli_stmt_execute($stmt);

        }

    }

    echo "<script>

    alert('Students assigned successfully.');

    window.location='view_class.php?id=".$class_id."';

    </script>";

    exit();

}

// Students eligible for this class

$stmt = mysqli_prepare(

    $conn,

    "SELECT

    student_id,
    register_number,
    student_name,
    email

    FROM students

    WHERE

    department_id=?

    AND year_id=?

    AND class_id IS NULL

    ORDER BY student_name"

);

mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $class["department_id"],

    $class["year_id"]

);

mysqli_stmt_execute($stmt);

$students = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html>

<head>

<title>

Assign Students

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
href="view_class.php?id=<?php echo $class_id; ?>"
class="home-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Assign Students

</h1>

<div class="class-details-card">

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

<strong>Program :</strong>

<?php echo htmlspecialchars($class["program_level"]); ?>

</p>

</div>

<form method="POST">

<table class="data-table">

<thead>

<tr>

<th>Select</th>

<th>Register No</th>

<th>Name</th>

<th>Email</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($students)>0){

while($row=mysqli_fetch_assoc($students)){

?>

<tr>

<td>

<input
type="checkbox"
name="students[]"
value="<?php echo $row["student_id"]; ?>">

</td>

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

<td colspan="4">

No students available for this class.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<br>

<button
type="submit">

<i class="fa-solid fa-user-plus"></i>

Assign Selected Students

</button>

</form>

</div>

</body>

</html>