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
$courses = mysqli_query(

    $conn,

    "SELECT
        department_id,
        department_name,
        program_level
     FROM departments
     ORDER BY department_name"

);
$years = mysqli_query(

    $conn,

    "SELECT
        year_id,
        year_name
     FROM years
     ORDER BY year_id"

);
$staff = mysqli_query(

    $conn,

    "SELECT
        staff_id,
        staff_name
     FROM staff
     ORDER BY staff_name"

);
if($_SERVER["REQUEST_METHOD"]=="POST"){

    $department_id=$_POST["department_id"];

    $year_id=$_POST["year_id"];

    $section=$_POST["section"];

    // Get Course Name

$stmt = mysqli_prepare(

    $conn,

    "SELECT department_name
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

$department = mysqli_fetch_assoc($result);

$course_name = $department["department_name"];
// Get Year Name

$stmt = mysqli_prepare(

    $conn,

    "SELECT year_name
     FROM years
     WHERE year_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $year_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$year = mysqli_fetch_assoc($result);

$year_name = $year["year_name"];

// Convert to Roman Numerals

switch($year_name){

    case "1st Year":
        $year_short = "I";
        break;

    case "2nd Year":
        $year_short = "II";
        break;

    case "3rd Year":
        $year_short = "III";
        break;

    case "4th Year":
        $year_short = "IV";
        break;

    default:
        $year_short = $year_name;

}

// Generate Class Name

$class_name =

$course_name

. " "

. $year_short

. "-"

. $section;

    $class_advisor=
$_POST["class_advisor"];

    $stmt=mysqli_prepare(

        $conn,

        "INSERT INTO classes
        (
            class_name,
            department_id,
            year_id,
            section,
            class_advisor
        )

        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?
        )"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "siisi",

        $class_name,

        $department_id,

        $year_id,

        $section,

        $class_advisor

    );

    mysqli_stmt_execute($stmt);

    echo "<script>

    alert('Class created successfully.');

    window.location='manage_classes.php';

    </script>";

    exit();

}
?>

<!DOCTYPE html>

<html>

<head>

<title>

Add Class

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

Add Class

</h1>

<form method="POST">

<label>

Course

</label>

<select
name="department_id"
id="department"
required>

<option value="">Select Course</option>

<?php while($row=mysqli_fetch_assoc($courses)){ ?>

<option
value="<?php echo $row["department_id"]; ?>">

<?php echo htmlspecialchars($row["department_name"]); ?>

</option>

<?php } ?>

</select>

<label>

Year

</label>

<select
name="year_id"
id="year"
required>

<option value="">Select Year</option>

<?php while($row=mysqli_fetch_assoc($years)){ ?>

<option
value="<?php echo $row["year_id"]; ?>">

<?php echo htmlspecialchars($row["year_name"]); ?>

</option>

<?php } ?>

</select>

<label>

Section

</label>

<select
name="section"
id="section"
required>

<option>A</option>
<option>B</option>
<option>C</option>
<option>D</option>

</select>

<label>

Class Advisor

</label>

<select
name="class_advisor">

<option value="">Select Advisor</option>

<?php while($row=mysqli_fetch_assoc($staff)){ ?>

<option
value="<?php echo $row["staff_id"]; ?>">

<?php echo htmlspecialchars($row["staff_name"]); ?>

</option>

<?php } ?>

</select>



<br><br>

<button
type="submit">

Save Class

</button>

</form>

</div>

</body>

</html>