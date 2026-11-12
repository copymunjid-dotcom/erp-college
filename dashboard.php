<?php

session_start();


if(
    !isset($_SESSION["role_id"]) ||
    $_SESSION["role_id"] != 1
)

// Check if the student is logged in
if (!isset($_SESSION["full_name"])) {

    header("Location: login.php");
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h1>Student Dashboard</h1>

    

    <h2 style="text-align:center; font-size:20px; ">

        Welcome,

        <?php echo $_SESSION["full_name"];?>

    </h2>

    <br>

    <p style="text-align:center; font-size:18px; ">

        You have successfully logged in.

    </p>

    <br>

    <a class="logout-link" href="logout.php">

        <button>

            Logout

        </button>

    </a>
    <a class="update-link" href="update_student.php">
    <button>Edit Profile</button>
</a>

</div>

</body>

</html>