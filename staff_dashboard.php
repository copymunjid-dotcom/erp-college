<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// CHECK LOGIN
// =========================================

if(
    !isset($_SESSION["staff_id"])
){
    header("Location: login.php");
    exit();
}

// =========================================
// CHECK ROLE
// =========================================

if($_SESSION["role_id"] != 2){

    header("Location: login.php");
    exit();

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Staff Dashboard</title>

<link
rel="stylesheet"
href="style.css">

</head>

<body>

<div class="container">

<h1>

Staff Dashboard

</h1>

<div class="welcome-card">

<h2>

Welcome,

<?php

echo htmlspecialchars($_SESSION["full_name"]);

?>

</h2>

<p>

Staff Portal

</p>

</div>

<div class="dashboard-buttons">

<a
href="staff_profile.php"
class="dashboard-btn blue">

My Profile

</a>

<a
href="update_staff.php"
class="dashboard-btn blue">

Update Profile

</a>
<a href="manage_attendance.php" class="dashboard-btn blue">



<h3>Manage Attendance</h3>

</a>


<a
href="logout.php"
class="dashboard-btn blue">

Logout

</a>

</div>

</div>

</body>

</html>