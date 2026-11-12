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

if(!isset($_GET["id"])){

    header("Location: manage_subjects.php");
    exit();

}

$subject_id = intval($_GET["id"]);

// Check if subject exists

$stmt = mysqli_prepare(

    $conn,

    "SELECT subject_name

    FROM subjects

    WHERE subject_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $subject_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$subject = mysqli_fetch_assoc($result);

if(!$subject){

    echo "<script>

    alert('Subject not found.');

    window.location='manage_subjects.php';

    </script>";

    exit();

}

// Delete Subject

$stmt = mysqli_prepare(

    $conn,

    "DELETE FROM subjects

    WHERE subject_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $subject_id

);

if(mysqli_stmt_execute($stmt)){

    echo "<script>

    alert('Subject deleted successfully.');

    window.location='manage_subjects.php';

    </script>";

}else{

    echo "<script>

    alert('Unable to delete subject.');

    window.location='manage_subjects.php';

    </script>";

}

?>