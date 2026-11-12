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
$sql = "

SELECT

c.class_id,

d.department_name,

y.year_name,

c.section,

s.staff_name

FROM classes c

LEFT JOIN departments d
ON c.department_id = d.department_id

LEFT JOIN years y
ON c.year_id = y.year_id

LEFT JOIN staff s
ON c.class_advisor = s.staff_id

";


if($search != ""){

    $sql .= "

    WHERE

    d.department_name LIKE ?

    OR y.year_name LIKE ?

    OR c.section LIKE ?

    OR s.staff_name LIKE ?

    ";

}

$sql .= "

ORDER BY

d.department_name,

y.year_name,

c.section

";

$stmt = mysqli_prepare($conn,$sql);

if($search!=""){

    $like="%".$search."%";

    mysqli_stmt_bind_param(

        $stmt,

        "ssss",

        $like,

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

Manage Classes

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

Manage Classes

</h1>

<form method="GET" class="search-form">

<div class="search-row">

<div class="search-input">

<input
type="text"
name="search"
placeholder="Search Class / Course / Advisor..."
value="<?php echo htmlspecialchars($search); ?>">

</div>

<?php if($search==""){ ?>

<a
href="add_class.php"
class="add-btn">

<i class="fa-solid fa-plus"></i>

Add Class

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

<th>Course</th>

<th>Year</th>

<th>Section</th>

<th>Class Advisor</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<?php echo htmlspecialchars($row["department_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["year_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["section"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["staff_name"] ?? "Not Assigned"); ?>

</td>

<td>

<a
href="view_class.php?id=<?php echo $row["class_id"]; ?>">

<i class="fa-solid fa-eye"></i>

</a>

<a
href="edit_class.php?id=<?php echo $row["class_id"]; ?>">

<i class="fa-solid fa-pen"></i>

</a>

<a
href="delete_class.php?id=<?php echo $row["class_id"]; ?>"
onclick="return confirm('Delete this class?');">

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