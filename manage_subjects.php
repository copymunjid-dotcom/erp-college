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

$search = "";

if(isset($_GET["search"])){

    $search = trim($_GET["search"]);

}

// Total Subjects

$count_result = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total_subjects
     FROM subjects"

);

$count = mysqli_fetch_assoc($count_result);

// SQL

$sql = "

SELECT

s.subject_id,

s.subject_code,

s.subject_name,

d.department_name,

y.year_name,

s.semester,

s.credits

FROM subjects s

LEFT JOIN departments d
ON s.department_id = d.department_id

LEFT JOIN years y
ON s.year_id = y.year_id

";

if($search != ""){

    $sql .= "

    WHERE

    s.subject_code LIKE ?

    OR s.subject_name LIKE ?

    OR d.department_name LIKE ?

    ";

}

$sql .= "

ORDER BY

d.department_name,

s.semester,

s.subject_name

";

$stmt = mysqli_prepare($conn,$sql);

if($search!=""){

    $like="%".$search."%";

    mysqli_stmt_bind_param(

        $stmt,

        "sss",

        $like,

        $like,

        $like

    );

}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html>

<head>

<title>

Manage Subjects

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
href="admin_dashboard.php"
class="home-btn"
title="Dashboard">

<i class="fa-solid fa-house"></i>

</a>

<h1>

Manage Subjects

</h1>

<div class="summary-card">

<i class="fa-solid fa-book"></i>

<h3>

Total Subjects

</h3>

<h2>

<?php echo $count["total_subjects"]; ?>

</h2>

</div>

<form method="GET" class="search-form">

<div class="search-row">

<div class="search-input">

<input
type="text"
name="search"
placeholder="Search Subject..."
value="<?php echo htmlspecialchars($search); ?>">

</div>

<?php if($search==""){ ?>

<a
href="add_subject.php"
class="add-btn">

<i class="fa-solid fa-user-plus"></i>


</a>

<?php } ?>

</div>

<div class="search-button">

<button
type="submit">

<i class="fa-solid fa-search"></i>

Search

</button>

</div>

</form>

<div class="table-wrapper">

<table class="data-table">

<thead>

<tr>

<th>Code</th>

<th>Subject</th>

<th>Course</th>

<th>Year</th>

<th>Semester</th>

<th>Credits</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<?php echo htmlspecialchars($row["subject_code"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["subject_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["department_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["year_name"]); ?>

</td>

<td>

Semester <?php echo htmlspecialchars($row["semester"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["credits"]); ?>

</td>

<td>

<a
href="edit_subject.php?id=<?php echo $row["subject_id"]; ?>">

<i class="fa-solid fa-pen"></i>

</a>

<a
href="delete_subject.php?id=<?php echo $row["subject_id"]; ?>"
onclick="return confirm('Delete this subject?');">

<i class="fa-solid fa-trash"></i>

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="7">

No Subjects Found.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</body>

</html>