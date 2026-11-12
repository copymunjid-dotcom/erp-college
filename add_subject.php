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

// Load Courses
$courses = mysqli_query(
    $conn,
    "SELECT department_id, department_name
     FROM departments
     ORDER BY department_name"
);

// Load Years
$years = mysqli_query(
    $conn,
    "SELECT year_id, year_name
     FROM years
     ORDER BY year_id"
);

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $subject_code = trim($_POST["subject_code"]);
    $subject_name = trim($_POST["subject_name"]);
    $department_id = intval($_POST["department_id"]);
    $year_id = intval($_POST["year_id"]);
    $semester = intval($_POST["semester"]);
    $credits = intval($_POST["credits"]);

    // Check Duplicate Subject Code
    $stmt = mysqli_prepare(
        $conn,
        "SELECT subject_id
         FROM subjects
         WHERE subject_code=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $subject_code
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result)>0){

        echo "<script>
        alert('Subject Code already exists.');
        </script>";

    }else{

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO subjects
            (
                subject_code,
                subject_name,
                department_id,
                year_id,
                semester,
                credits
            )
            VALUES
            (
                ?,?,?,?,?,?
            )"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssiiii",
            $subject_code,
            $subject_name,
            $department_id,
            $year_id,
            $semester,
            $credits
        );

        mysqli_stmt_execute($stmt);

        echo "<script>
        alert('Subject added successfully.');
        window.location='manage_subjects.php';
        </script>";

        exit();

    }

}

?>

<!DOCTYPE html>

<html>

<head>

<title>Add Subject</title>

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
href="manage_subjects.php"
class="home-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>Add Subject</h1>

<form method="POST">

<label>

Subject Code <span class="required">*</span>

</label>

<input
type="text"
name="subject_code"
required>

<label>

Subject Name <span class="required">*</span>

</label>

<input
type="text"
name="subject_name"
required>

<label>

Course <span class="required">*</span>

</label>

<select
name="department_id"
required>

<option value="">
Select Course
</option>

<?php
while($course=mysqli_fetch_assoc($courses)){
?>

<option
value="<?php echo $course["department_id"]; ?>">

<?php echo htmlspecialchars($course["department_name"]); ?>

</option>

<?php
}
?>

</select>

<label>

Year <span class="required">*</span>

</label>

<select
name="year_id"
required>

<option value="">
Select Year
</option>

<?php
while($year=mysqli_fetch_assoc($years)){
?>

<option
value="<?php echo $year["year_id"]; ?>">

<?php echo htmlspecialchars($year["year_name"]); ?>

</option>

<?php
}
?>

</select>

<label>

Semester <span class="required">*</span>

</label>

<select
name="semester"
required>

<option value="1">Semester 1</option>
<option value="2">Semester 2</option>
<option value="3">Semester 3</option>
<option value="4">Semester 4</option>
<option value="5">Semester 5</option>
<option value="6">Semester 6</option>
<option value="7">Semester 7</option>
<option value="8">Semester 8</option>

</select>

<label>

Credits <span class="required">*</span>

</label>

<input
type="number"
name="credits"
value="4"
min="1"
max="10"
required>

<button
type="submit">

<i class="fa-solid fa-floppy-disk"></i>

Save Subject

</button>

</form>

</div>

</body>

</html>