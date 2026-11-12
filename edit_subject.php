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

    header("Location: manage_subjects.php");
    exit();

}

$subject_id = intval($_GET["id"]);

// Load Subject

$stmt = mysqli_prepare(

    $conn,

    "SELECT *

    FROM subjects

    WHERE subject_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $subject_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$subject = mysqli_fetch_assoc($result);

if(!$subject){

    die("Subject not found.");

}

// Load Courses

$courses = mysqli_query(

    $conn,

    "SELECT

    department_id,
    department_name

    FROM departments

    ORDER BY department_name"

);

// Load Years

$years = mysqli_query(

    $conn,

    "SELECT

    year_id,
    year_name

    FROM years

    ORDER BY year_id"

);

// Update Subject

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $subject_code = trim($_POST["subject_code"]);

    $subject_name = trim($_POST["subject_name"]);

    $department_id = intval($_POST["department_id"]);

    $year_id = intval($_POST["year_id"]);

    $semester = intval($_POST["semester"]);

    $credits = intval($_POST["credits"]);

    // Duplicate Check

    $stmt = mysqli_prepare(

        $conn,

        "SELECT subject_id

        FROM subjects

        WHERE subject_code=?

        AND subject_id<>?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "si",

        $subject_code,

        $subject_id

    );

    mysqli_stmt_execute($stmt);

    $duplicate = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($duplicate)>0){

        echo "<script>

        alert('Subject Code already exists.');

        </script>";

    }else{

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE subjects

            SET

            subject_code=?,
            subject_name=?,
            department_id=?,
            year_id=?,
            semester=?,
            credits=?

            WHERE subject_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "ssiiiii",

            $subject_code,

            $subject_name,

            $department_id,

            $year_id,

            $semester,

            $credits,

            $subject_id

        );

        mysqli_stmt_execute($stmt);

        echo "<script>

        alert('Subject updated successfully.');

        window.location='manage_subjects.php';

        </script>";

        exit();

    }

}

?>

<!DOCTYPE html>

<html>

<head>

<title>

Edit Subject

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
href="manage_subjects.php"
class="home-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Edit Subject

</h1>

<form method="POST">

<label>

Subject Code

</label>

<input
type="text"
name="subject_code"
value="<?php echo htmlspecialchars($subject["subject_code"]); ?>"
required>

<label>

Subject Name

</label>

<input
type="text"
name="subject_name"
value="<?php echo htmlspecialchars($subject["subject_name"]); ?>"
required>

<label>

Course

</label>

<select
name="department_id"
required>

<?php

while($course=mysqli_fetch_assoc($courses)){

?>

<option

value="<?php echo $course["department_id"]; ?>"

<?php

if($subject["department_id"]==$course["department_id"]){

echo "selected";

}

?>

>

<?php echo htmlspecialchars($course["department_name"]); ?>

</option>

<?php

}

?>

</select>

<label>

Year

</label>

<select
name="year_id"
required>

<?php

while($year=mysqli_fetch_assoc($years)){

?>

<option

value="<?php echo $year["year_id"]; ?>"

<?php

if($subject["year_id"]==$year["year_id"]){

echo "selected";

}

?>

>

<?php echo htmlspecialchars($year["year_name"]); ?>

</option>

<?php

}

?>

</select>

<label>

Semester

</label>

<select
name="semester"
required>

<?php

for($i=1;$i<=8;$i++){

?>

<option

value="<?php echo $i; ?>"

<?php

if($subject["semester"]==$i){

echo "selected";

}

?>

>

Semester <?php echo $i; ?>

</option>

<?php

}

?>

</select>

<label>

Credits

</label>

<input
type="number"
name="credits"
min="1"
max="10"
value="<?php echo htmlspecialchars($subject["credits"]); ?>"
required>

<br><br>

<button
type="submit">

<i class="fa-solid fa-floppy-disk"></i>

Update Subject

</button>

</form>

</div>

</body>

</html>