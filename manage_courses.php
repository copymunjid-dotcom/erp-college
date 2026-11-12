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
$count_result = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total_courses
     FROM departments"

);

$count = mysqli_fetch_assoc($count_result);
$search = "";

if(isset($_GET["search"])){

    $search = trim($_GET["search"]);

}
$sql = "

SELECT

department_id,

department_name,

program_level

FROM departments

";

if($search != ""){

    $sql .= "

    WHERE

    department_name LIKE ?

    ";

}

$sql .= "

ORDER BY department_name

";

$stmt = mysqli_prepare($conn,$sql);

if($search != ""){

    $like = "%".$search."%";

    mysqli_stmt_bind_param(

        $stmt,

        "s",

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

Manage Courses

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

Manage Courses

</h1>
<div class="summary-card">

    <i class="fa-solid fa-book"></i>

    <h3>Total Courses</h3>

    <h2>

        <?php echo $count["total_courses"]; ?>

    </h2>

</div>

<form method="GET" class="search-form">

<div class="search-row">

<div class="search-input">

<input
type="text"
name="search"
placeholder="Search Courses..."
value="<?php echo htmlspecialchars($search); ?>">

</div>

<?php if($search==""){ ?>

<a
href="add_course.php"
class="add-btn">

<i class="fa-solid fa-plus"></i>

Add Course

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
<?php

if($search != ""){

    if(mysqli_num_rows($result)>0){

?>

<div class="table-wrapper">

<table class="data-table">

<thead>

<tr>

<th>Course ID</th>
<th>Program Level</th>
<th>Course Name</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

while($row = mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<?php echo $row["department_id"]; ?>

</td>
<td>

<?php echo htmlspecialchars($row["program_level"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["department_name"]); ?>

</td>

<td>

<a
href="edit_course.php?id=<?php echo $row["department_id"]; ?>">

<i class="fa-solid fa-pen"></i>

</a>

<a
href="delete_course.php?id=<?php echo $row["department_id"]; ?>"
onclick="return confirm('Delete this course?');">

<i class="fa-solid fa-trash"></i>

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<?php

    }else{

?>

<div class="no-result">

    <i class="fa-solid fa-user-slash"></i>

    <h3>No Courses Found</h3>

<p>Try another course name.</p>

</div>

<?php

    }

}

?>
</div>
</body>

</html>