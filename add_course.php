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

$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $course_name = trim($_POST["course_name"]);
    $program_level = $_POST["program_level"];

    if($course_name==""){

        $error = "Course name is required.";

    }else{

        $stmt = mysqli_prepare(

    $conn,

    "INSERT INTO departments
    (
        department_name,
        program_level
    )
    VALUES
    (
        ?,
        ?
    )"

);

        

        mysqli_stmt_bind_param(

            $stmt,

            "ss",

            $course_name,
            $program_level

        );

        try{

    mysqli_stmt_execute($stmt);

    echo "<script>

    alert('Course saved successfully.');

    window.location='manage_courses.php';

    </script>";

    exit();

}catch(mysqli_sql_exception $e){

    if(str_contains($e->getMessage(), "Duplicate entry")){

        echo "<script>

        alert('Course already exists.');

        </script>";

    }else{

        throw $e;

    }

}

    }

}

?>
<!DOCTYPE html>

<html>

<head>

<title>Add Course</title>

<link
rel="stylesheet"
href="style.css">

</head>

<body>

<div class="container">

<a
href="manage_courses.php"
class="home-btn">

🏠

</a>
<?php if($success != ""){ ?>

<div class="success-message">

    <?php echo $success; ?>

</div>

<?php } ?>
<h1>

Add Course

</h1>

<?php if($error!=""){ ?>

<div class="error-message">

<?php echo $error; ?>

</div>

<?php } ?>

<form method="POST">

<label>

Course Name

</label>

<input
type="text"
name="course_name"
required>

<br><br>


<label>

Program Level

</label>

<select name="program_level" required>

    <option value="">Select Program Level</option>

    <option value="UG">UG</option>

    <option value="PG">PG</option>

    <option value="Diploma">Diploma</option>

    <option value="PhD">PhD</option>

</select>


<button
type="submit">

Save Course

</button>

</form>

</div>

</body>

</html>