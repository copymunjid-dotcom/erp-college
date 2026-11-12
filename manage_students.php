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

    s.student_id,
    s.register_number,
    s.student_name,
    s.email,
    d.department_name,
    y.year_name,
    sd.file_path AS photo

FROM students s

LEFT JOIN departments d
ON s.department_id = d.department_id

LEFT JOIN years y
ON s.year_id = y.year_id
LEFT JOIN student_documents sd
ON s.student_id = sd.student_id
AND sd.document_type_id = 1
";

if($search != ""){

    $sql .= "

    WHERE

    s.student_name LIKE ?

    OR s.register_number LIKE ?

    OR s.email LIKE ?

    ";

}

$sql .= "

ORDER BY s.student_name

";

$stmt = mysqli_prepare($conn,$sql);

if($search != ""){

    $like = "%".$search."%";

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

Manage Students

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

Manage Students

</h1>

<form method="GET" class="search-form">

<div class="search-row">

<div class="search-input">

<input
type="text"
name="search"
placeholder="Search Student..."
value="<?php echo htmlspecialchars($search); ?>">

</div>

<?php if($search==""){ ?>

<a
href="register.php"
class="add-btn">

<i class="fa-solid fa-plus"></i>

Add Student

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

<th>Photo</th>

<th>Register No</th>

<th>Name</th>

<th>Department</th>

<th>Year</th>

<th>Actions</th>

</tr>

</thead>

<tbody>
<?php

while($row = mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<?php if(!empty($row["photo"])){ ?>

<img
src="uploads/photos/<?php echo htmlspecialchars($row["photo"]); ?>"
class="table-photo">

<?php }else{ ?>

<img
src="images/default-user.png"
class="table-photo">

<?php } ?>

</td>

<td>

<?php echo htmlspecialchars($row["register_number"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["student_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["department_name"]); ?>

</td>



<td>

<?php echo htmlspecialchars($row["year_name"]); ?>

</td>



<td>

<a
href="view_student.php?id=<?php echo $row["student_id"]; ?>">

<i class="fa-solid fa-eye"></i>

</a>

<a
href="edit_student.php?id=<?php echo $row["student_id"]; ?>">

<i class="fa-solid fa-pen"></i>

</a>

<a
href="delete_student.php?id=<?php echo $row["student_id"]; ?>">

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

    <h3>No Students Found</h3>

    <p>Try another Register Number or Name.</p>

</div>

<?php

    }

}

?>
</div>
</body>
</html>