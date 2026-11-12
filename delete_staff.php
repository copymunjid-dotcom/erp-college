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

    header("Location: manage_students.php");
    exit();

}

$staff_id = intval($_GET["id"]);

mysqli_begin_transaction($conn);

try{

    mysqli_query($conn,"DELETE FROM staff_documents WHERE staff_id=$staff_id");

    mysqli_query($conn,"DELETE FROM staff_qualifications WHERE staff_id=$staff_id");

    mysqli_query($conn,"DELETE FROM staff_addresses WHERE staff_id=$staff_id");

    mysqli_query($conn,"DELETE FROM staff_emergency_contacts WHERE staff_id=$staff_id");

    mysqli_query($conn,"DELETE FROM users WHERE staff_id=$staff_id");

    mysqli_query($conn,"DELETE FROM staff WHERE staff_id=$staff_id");

    mysqli_commit($conn);

    header("Location: manage_staff.php");
    exit();

}catch(Exception $e){

    mysqli_rollback($conn);

    die($e->getMessage());

}