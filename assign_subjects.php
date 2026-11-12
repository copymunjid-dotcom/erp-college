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

if(!isset($_GET["staff_id"])){

    header("Location: manage_staff.php");
    exit();

}

$staff_id = intval($_GET["staff_id"]);
$stmt = mysqli_prepare(

    $conn,

    "SELECT

    s.staff_id,
    s.staff_name,
    d.department_name,
    s.department_id,
    dg.designation_name

    FROM staff s

    LEFT JOIN departments d
    ON s.department_id=d.department_id

    LEFT JOIN designations dg
    ON s.designation_id=dg.designation_id

    WHERE s.staff_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $staff_id

);

mysqli_stmt_execute($stmt);

$staff=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$classes=mysqli_query(

    $conn,

    "SELECT

    class_id,
    class_name

    FROM classes

    WHERE department_id=".$staff["department_id"]."

    ORDER BY class_name"

);
$class_id=0;

if(isset($_POST["class_id"])){

    $class_id=intval($_POST["class_id"]);

}
$class_year=0;

if($class_id!=0){

$stmt=mysqli_prepare(

$conn,

"SELECT year_id

FROM classes

WHERE class_id=?"

);

mysqli_stmt_bind_param(

$stmt,

"i",

$class_id

);

mysqli_stmt_execute($stmt);

$temp=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$class_year=$temp["year_id"];

}
$subjects=[];

if($class_id!=0){

$stmt=mysqli_prepare(

$conn,

"SELECT

subject_id,

subject_name

FROM subjects

WHERE

department_id=?

AND

year_id=?"

);

mysqli_stmt_bind_param(

$stmt,

"ii",

$staff["department_id"],

$class_year

);

mysqli_stmt_execute($stmt);

$subjects=mysqli_stmt_get_result($stmt);

}
if(isset($_POST["assign"])){

$class_id=intval($_POST["class_id"]);

$subject_id=intval($_POST["subject_id"]);
$stmt=mysqli_prepare(

$conn,

"SELECT *

FROM staff_subjects

WHERE

staff_id=?

AND subject_id=?

AND class_id=?"

);

mysqli_stmt_bind_param(

$stmt,

"iii",

$staff_id,

$subject_id,

$class_id

);

mysqli_stmt_execute($stmt);

$exists=mysqli_stmt_get_result($stmt);
if(mysqli_num_rows($exists)==0){

$stmt=mysqli_prepare(

$conn,

"INSERT INTO staff_subjects

(

staff_id,

subject_id,

class_id

)

VALUES

(

?,

?,

?

)"

);

mysqli_stmt_bind_param(

$stmt,

"iii",

$staff_id,

$subject_id,

$class_id

);

mysqli_stmt_execute($stmt);

echo "<script>

alert('Subject assigned successfully.');

window.location='assign_subjects.php?staff_id=".$staff_id."';

</script>";

exit();

}
else{

echo "<script>

alert('This subject is already assigned.');

</script>";

}
}
?>
<!DOCTYPE html>

<html>

<head>

<title>

Assign Subject

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
href="manage_staff.php"
class="home-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Assign Subject

</h1>

<div class="details-card">

<h2>

<?php echo htmlspecialchars($staff["staff_name"]); ?>

</h2>

<hr>

<p>

<strong>Department :</strong>

<?php echo htmlspecialchars($staff["department_name"]); ?>

</p>

<p>

<strong>Designation :</strong>

<?php echo htmlspecialchars($staff["designation_name"]); ?>

</p>

</div>

<form method="POST">

<label>

Class <span class="required">*</span>

</label>

<select

name="class_id"

onchange="this.form.submit()"

required>

<option value="">

Select Class

</option>

<?php

while($row=mysqli_fetch_assoc($classes)){

?>

<option

value="<?php echo $row["class_id"]; ?>"

<?php

if($class_id==$row["class_id"]) echo "selected";

?>

>

<?php echo htmlspecialchars($row["class_name"]); ?>

</option>

<?php

}

?>

</select>

<br><br>

<?php

if($class_id!=0){

?>

<label>

Subject <span class="required">*</span>

</label>

<select

name="subject_id"

required>

<option value="">

Select Subject

</option>

<?php

while($subject=mysqli_fetch_assoc($subjects)){

?>

<option

value="<?php echo $subject["subject_id"]; ?>">

<?php echo htmlspecialchars($subject["subject_name"]); ?>

</option>

<?php

}

?>

</select>

<br><br>

<button

type="submit"

name="assign">

<i class="fa-solid fa-book"></i>

Assign Subject

</button>

<?php

}

?>

</form>

</div>

</body>

</html>