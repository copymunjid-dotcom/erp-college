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

    header("Location: manage_courses.php");
    exit();

}

$department_id = intval($_GET["id"]);
$stmt = mysqli_prepare(

    $conn,

    "SELECT
        department_name,
        program_level
     FROM departments
     WHERE department_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $department_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$course = mysqli_fetch_assoc($result);

if(!$course){

    die("Course not found.");

}
$message = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $department_name = trim($_POST["department_name"]);

    $program_level = $_POST["program_level"];

    $stmt = mysqli_prepare(

        $conn,

        "UPDATE departments

        SET

        department_name=?,
        program_level=?

        WHERE department_id=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "ssi",

        $department_name,

        $program_level,

        $department_id

    );

    mysqli_stmt_execute($stmt);

    echo "<script>

    alert('Course updated successfully.');

    window.location='manage_courses.php';

    </script>";

    exit();

}
?>

<!DOCTYPE html>
<html>

<head>
    

<title>Edit Course</title>

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
href="manage_courses.php"
class="home-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>
<h1>

Edit Course

</h1>
<form method="POST">
    <label>

Course Name

</label>

<input

type="text"

name="department_name"

value="<?php echo htmlspecialchars($course["department_name"]); ?>"

required>
    <br><br>

<label>

Program Level

</label>

<select name="program_level" required>

<option value="UG"

<?php if($course["program_level"]=="UG") echo "selected"; ?>>

UG

</option>

<option value="PG"

<?php if($course["program_level"]=="PG") echo "selected"; ?>>

PG

</option>

<option value="Diploma"

<?php if($course["program_level"]=="Diploma") echo "selected"; ?>>

Diploma

</option>

<option value="PhD"

<?php if($course["program_level"]=="PhD") echo "selected"; ?>>

PhD

</option>

</select>





<br><br>

<button type="submit">

Update Course

</button>

</form>
</div>

</body>

</html>