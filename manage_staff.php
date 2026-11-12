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

    s.staff_id,
    s.staff_name,
    s.register_number,
    s.photo,
    s.email,
    d.department_name,
    des.designation_name

FROM staff s

LEFT JOIN departments d
ON s.department_id = d.department_id

LEFT JOIN designations des
ON s.designation_id = des.designation_id

";

if($search != ""){

    $sql .= "

    WHERE

    s.register_number LIKE ?

    OR s.staff_name LIKE ?

    OR s.email LIKE ?

    ";

}

$sql .= "

ORDER BY s.staff_name

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

Manage Staffs

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

Manage Staffs

</h1>

<form method="GET" class="search-form">

<div class="search-row">

<div class="search-input">

<input
type="text"
name="search"
placeholder="Search Staff..."
value="<?php echo htmlspecialchars($search); ?>">

</div>

<?php if($search==""){ ?>

<a
href="register_staff.php"
class="add-btn">

<i class="fa-solid fa-plus"></i>

Add Staff

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
<th>Staff Code</th>
<th>Name</th>
<th>Department</th>
<th>Designation</th>
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
src="<?php echo htmlspecialchars($row["photo"]); ?>"
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

<?php echo htmlspecialchars($row["staff_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["department_name"]); ?>

</td>



<td>

<?php echo htmlspecialchars($row["designation_name"]); ?>

</td>



<td>

<a
href="staff_profile.php?id=<?php echo $row["staff_id"]; ?>"
title="View">

<i class="fa-solid fa-eye"></i>

</a>

<a
href="assign_subjects.php?staff_id=<?php echo $row["staff_id"]; ?>"
title="Assign Subjects">

<i class="fa-solid fa-book-open"></i>

</a>

<a
href="update_staff_admin.php?id=<?php echo $row["staff_id"]; ?>"
title="Edit">

<i class="fa-solid fa-pen"></i>

</a>

<a
href="delete_staff.php?id=<?php echo $row["staff_id"]; ?>"
onclick="return confirm('Are you sure you want to delete this staff member?');"
title="Delete">

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

    <h3>No Staff Found</h3>

    <p>Try another Staff  number or Name.</p>

</div>

<?php

    }

}

?>