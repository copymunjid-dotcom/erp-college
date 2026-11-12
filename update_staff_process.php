<?php

session_start();

require "database.php";
function updateStaffDocument($conn, $staff_id, $document_type_id, $input_name){

    if(
        !isset($_FILES[$input_name]) ||
        $_FILES[$input_name]["error"] == UPLOAD_ERR_NO_FILE
    ){
        return;
    }

    $upload_dir = "uploads/staff/";

    if(!is_dir($upload_dir)){
        mkdir($upload_dir,0777,true);
    }

    $filename =
        time() . "_" .
        basename($_FILES[$input_name]["name"]);

    $target = $upload_dir . $filename;

    if(!move_uploaded_file(
        $_FILES[$input_name]["tmp_name"],
        $target
    )){
        throw new Exception("Failed to upload " . $input_name);
    }

    $stmt = mysqli_prepare(

        $conn,

        "UPDATE staff_documents

         SET file_path=?

         WHERE staff_id=?
         AND document_type_id=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "sii",

        $target,

        $staff_id,

        $document_type_id

    );

    mysqli_stmt_execute($stmt);
    if($document_type_id == 1){

    $stmt = mysqli_prepare(

        $conn,

        "UPDATE staff
         SET photo=?
         WHERE staff_id=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "si",

        $target,

        $staff_id

    );

    mysqli_stmt_execute($stmt);

    if(mysqli_stmt_errno($stmt)){

        throw new Exception(mysqli_stmt_error($stmt));

    }

}

    if(mysqli_stmt_errno($stmt)){

        throw new Exception(mysqli_stmt_error($stmt));

    }

}

if($_SERVER["REQUEST_METHOD"] != "POST"){

    header("Location: update_staff.php");

    exit();

}

mysqli_begin_transaction($conn);

try{
$staff_id = $_POST["staff_id"];

$staff_name = trim($_POST["staff_name"]);

$register_number = trim($_POST["register_number"]);

$email = trim($_POST["email"]);

$phone = trim($_POST["phone"]);

$gender_id = $_POST["gender_id"];

$date_of_birth = $_POST["date_of_birth"];

$department_id = $_POST["department_id"];

$designation_id = $_POST["designation_id"];

$permanent_address = trim($_POST["address"]);

$temporary_address = trim($_POST["temporary_address"]);

$emergency_name = trim($_POST["emergency_name"]);

$relationship_type_id = $_POST["relationship_type_id"];

$emergency_phone = trim($_POST["emergency_phone"]);
$stmt = mysqli_prepare(

    $conn,

    "UPDATE staff

     SET

        staff_name=?,

        register_number=?,

        email=?,

        phone=?,

        gender_id=?,

        date_of_birth=?,

        department_id=?,

        designation_id=?

     WHERE staff_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "ssssissii",

    $staff_name,

    $register_number,

    $email,

    $phone,

    $gender_id,

    $date_of_birth,

    $department_id,

    $designation_id,

    $staff_id

);

mysqli_stmt_execute($stmt);

if(mysqli_stmt_errno($stmt)){

    throw new Exception(mysqli_stmt_error($stmt));

}
// =============================
// Update Staff Address
// =============================

$stmt = mysqli_prepare(

    $conn,

    "UPDATE staff_addresses

     SET

        permanent_address=?,

        temporary_address=?

     WHERE staff_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "ssi",

    $permanent_address,

    $temporary_address,

    $staff_id

);

mysqli_stmt_execute($stmt);

if(mysqli_stmt_errno($stmt)){

    throw new Exception(mysqli_stmt_error($stmt));

}
// =============================
// Update Emergency Contact
// =============================

$stmt = mysqli_prepare(

    $conn,

    "UPDATE staff_emergency_contacts

     SET

        emergency_name=?,

        relationship_type_id=?,

        emergency_phone=?

     WHERE staff_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "sisi",

    $emergency_name,

    $relationship_type_id,

    $emergency_phone,

    $staff_id

);

mysqli_stmt_execute($stmt);

if(mysqli_stmt_errno($stmt)){

    throw new Exception(mysqli_stmt_error($stmt));

}
// =============================
// Delete Old Qualifications
// =============================

$stmt = mysqli_prepare(

    $conn,

    "DELETE FROM staff_qualifications
     WHERE staff_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $staff_id

);

mysqli_stmt_execute($stmt);

if(mysqli_stmt_errno($stmt)){

    throw new Exception(mysqli_stmt_error($stmt));

}
// =============================
// Insert Updated Qualifications
// =============================

if(isset($_POST["qualification_id"])){

    $count = count($_POST["qualification_id"]);

    for($i = 0; $i < $count; $i++){

        if(empty($_POST["qualification_id"][$i])){
            continue;
        }

        $qualification_id = $_POST["qualification_id"][$i];

        $specialization = trim($_POST["specialization"][$i]);

        $institution = trim($_POST["institution"][$i]);

        $university_name = trim($_POST["university_name"][$i]);

        $percentage = $_POST["percentage"][$i];

        $year_of_passing = $_POST["year_of_passing"][$i];

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO staff_qualifications
            (
                staff_id,
                qualification_id,
                specialization,
                institution,
                university_name,
                percentage,
                year_of_passing
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?)"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "iisssdi",

            $staff_id,

            $qualification_id,

            $specialization,

            $institution,

            $university_name,

            $percentage,

            $year_of_passing

        );

        mysqli_stmt_execute($stmt);

        if(mysqli_stmt_errno($stmt)){

            throw new Exception(mysqli_stmt_error($stmt));

        }

    }

}   
// =============================
// Update User Login
// =============================

$stmt = mysqli_prepare(

    $conn,

    "UPDATE users

     SET

        register_number=?,

        email=?

     WHERE staff_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "ssi",

    $register_number,

    $email,

    $staff_id

);

mysqli_stmt_execute($stmt);

if(mysqli_stmt_errno($stmt)){

    throw new Exception(mysqli_stmt_error($stmt));

}
updateStaffDocument($conn,$staff_id,1,"photo");

updateStaffDocument($conn,$staff_id,7,"resume");

updateStaffDocument($conn,$staff_id,8,"degree_certificate");

updateStaffDocument($conn,$staff_id,9,"id_proof");

updateStaffDocument($conn,$staff_id,10,"address_proof");

updateStaffDocument($conn,$staff_id,11,"experience_certificate");

updateStaffDocument($conn,$staff_id,12,"joining_letter");
// =============================
// Change Password
// =============================

if(

    !empty($_POST["new_password"]) ||

    !empty($_POST["confirm_password"])

){

    $current_password = $_POST["current_password"];

    $new_password = $_POST["new_password"];

    $confirm_password = $_POST["confirm_password"];

    if($new_password != $confirm_password){

        throw new Exception("Passwords do not match.");

    }

    $stmt = mysqli_prepare(

        $conn,

        "SELECT password
         FROM users
         WHERE staff_id=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "i",

        $staff_id

    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    if(

        !password_verify(

            $current_password,

            $user["password"]

        )

    ){

        throw new Exception("Current password is incorrect.");

    }

    $new_hash = password_hash(

        $new_password,

        PASSWORD_DEFAULT

    );

    $stmt = mysqli_prepare(

        $conn,

        "UPDATE users
         SET password=?
         WHERE staff_id=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "si",

        $new_hash,

        $staff_id

    );

    mysqli_stmt_execute($stmt);

}
mysqli_commit($conn);

echo "<script>

alert('Staff Updated Successfully');

window.location='staff_profile.php';

</script>";

}

catch(Exception $e){

    mysqli_rollback($conn);

    echo "<h2>Error:</h2>";

    echo $e->getMessage();

}

?>