<?php

session_start();

if(
    !isset($_SESSION["role_id"]) ||
    $_SESSION["role_id"] != 3
){
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h1>Admin Dashboard</h1>

    <h2>

        Welcome,
        <?php echo htmlspecialchars($_SESSION["full_name"]); ?>

    </h2>

    <br>

    <button onclick="location.href='manage_students.php'">

        Manage Students

    </button>

    <br><br>

    <button onclick="location.href='manage_staff.php'">

        Manage Staff

    </button>

    <br><br>

    

    <button onclick="location.href='manage_courses.php'">

    Manage Courses

</button>

<br><br>

<button onclick="location.href='manage_classes.php'">

    Manage Classes

</button>

    <br><br>
<button onclick="location.href='manage_subjects.php'">

    Manage Subjects

</button>

<br><br>

    <button onclick="location.href='logout.php'">

        Logout

    </button>

</div>

</body>

</html>