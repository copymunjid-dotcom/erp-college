<?php

session_start();

if(!isset($_SESSION["staff_id"])){

    header("Location: login.php");
    exit();

}

require "database.php";

$staff_id = $_SESSION["staff_id"];

if($_SERVER["REQUEST_METHOD"] != "POST"){

    header("Location: manage_attendance.php");
    exit();

}

$class_id = intval($_POST["class_id"]);
$subject_id = intval($_POST["subject_id"]);
$attendance_date = $_POST["attendance_date"];

if(
    empty($attendance_date) ||
    !isset($_POST["attendance"])
){

    die("Please complete the attendance form.");

}

$attendance = $_POST["attendance"];

/* ===========================================
   Check if attendance already exists
=========================================== */

$check = mysqli_prepare(

    $conn,

    "SELECT attendance_id

     FROM attendance

     WHERE

     class_id = ?
     AND subject_id = ?
     AND attendance_date = ?

     LIMIT 1"

);

mysqli_stmt_bind_param(

    $check,

    "iis",

    $class_id,
    $subject_id,
    $attendance_date

);

mysqli_stmt_execute($check);

$exists = mysqli_stmt_get_result($check);

if(mysqli_num_rows($exists) > 0){

    die("Attendance already exists for this date.");

}

/* ===========================================
   Insert Attendance
=========================================== */

$stmt = mysqli_prepare(

    $conn,

    "INSERT INTO attendance(

        student_id,
        staff_id,
        class_id,
        subject_id,
        attendance_date,
        status

    )

    VALUES(

        ?,
        ?,
        ?,
        ?,
        ?,
        ?

    )"

);

foreach($attendance as $student_id => $status){

    mysqli_stmt_bind_param(

        $stmt,

        "iiiiss",

        $student_id,
        $staff_id,
        $class_id,
        $subject_id,
        $attendance_date,
        $status

    );

    mysqli_stmt_execute($stmt);

}

mysqli_stmt_close($stmt);

header("Location: manage_attendance.php?success=Attendance Saved");

exit();

?>