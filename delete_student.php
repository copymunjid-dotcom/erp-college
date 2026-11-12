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

$student_id = intval($_GET["id"]);

mysqli_begin_transaction($conn);

try{

    // Delete child records first

    mysqli_query($conn,"DELETE FROM student_documents WHERE student_id=$student_id");

    mysqli_query($conn,"DELETE FROM academic_details WHERE student_id=$student_id");

    mysqli_query($conn,"DELETE FROM emergency_contacts WHERE student_id=$student_id");

    mysqli_query($conn,"DELETE FROM siblings WHERE student_id=$student_id");

    mysqli_query($conn,"DELETE FROM family_details WHERE student_id=$student_id");

    mysqli_query($conn,"DELETE FROM student_addresses WHERE student_id=$student_id");

    mysqli_query($conn,"DELETE FROM users WHERE student_id=$student_id");

    // Finally delete student

    mysqli_query($conn,"DELETE FROM students WHERE student_id=$student_id");

    mysqli_commit($conn);

    header("Location: manage_students.php");

    exit();

}catch(Exception $e){

    mysqli_rollback($conn);

    die($e->getMessage());

}