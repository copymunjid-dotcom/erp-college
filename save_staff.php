<?php



error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require "database.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: register_staff.php");
    exit();

}

mysqli_begin_transaction($conn);

try{
    $staff_name = trim($_POST["staff_name"]);

$register_number = trim($_POST["register_number"]);

$email = trim($_POST["email"]);

$phone = trim($_POST["phone"]);

$gender_id = $_POST["gender_id"];

$date_of_birth = $_POST["date_of_birth"];

$department_id = $_POST["department_id"];

$designation_id = $_POST["designation_id"];
function saveStaffDocument($conn,$staff_id,$document_type_id,$field_name){

    if(
        isset($_FILES[$field_name]) &&
        $_FILES[$field_name]["error"] == 0
    ){

        $folder = "uploads/staff_documents/";

        if(!is_dir($folder)){
            mkdir($folder,0777,true);
        }

        $filename =
        time()."_".basename($_FILES[$field_name]["name"]);

        $filepath = $folder.$filename;

        move_uploaded_file(
            $_FILES[$field_name]["tmp_name"],
            $filepath
        );

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO staff_documents
            (
                staff_id,
                document_type_id,
                file_path
            )
            VALUES
            (?, ?, ?)"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "iis",

            $staff_id,
            $document_type_id,
            $filepath

        );

        mysqli_stmt_execute($stmt);

    }

}

// =============================
// Upload Staff Photo
// =============================

$photo_path = NULL;

if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){

    $upload_directory = "uploads/staffs/staff_photos/";

    if(!is_dir($upload_directory)){

        mkdir($upload_directory, 0777, true);

    }

    $photo_name = time() . "_" . basename($_FILES["photo"]["name"]);

    $photo_path = $upload_directory . $photo_name;

    if(!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_path)){

        throw new Exception("Failed to upload staff photo.");

    }


}
// =============================
// Insert Staff
// =============================

$stmt = mysqli_prepare(

    $conn,

    "INSERT INTO staff
    (
        staff_name,
        register_number,
        email,
        phone,
        gender_id,
        date_of_birth,
        photo,
        department_id,
        designation_id
    )
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?)"

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
    $photo_path,
    $department_id,
    $designation_id

);

mysqli_stmt_execute($stmt);


$staff_id = mysqli_insert_id($conn);
// =============================
// Insert Staff Address
// =============================

$permanent_address = trim($_POST["address"]);

$temporary_address = trim($_POST["temporary_address"]);

$stmt = mysqli_prepare(

    $conn,

    "INSERT INTO staff_addresses
    (
        staff_id,
        permanent_address,
        temporary_address
    )
    VALUES
    (?, ?, ?)"

);

mysqli_stmt_bind_param(

    $stmt,

    "iss",

    $staff_id,
    $permanent_address,
    $temporary_address

);


mysqli_stmt_execute($stmt);

if(mysqli_stmt_affected_rows($stmt) <= 0){

    throw new Exception("Failed to save staff address.");

}


// =============================
// Insert Emergency Contact
// =============================

$emergency_name = trim($_POST["emergency_name"]);

$relationship_type_id = $_POST["relationship_type_id"];

$emergency_phone = trim($_POST["emergency_phone"]);

$stmt = mysqli_prepare(

    $conn,

    "INSERT INTO staff_emergency_contacts
    (
        staff_id,
        emergency_name,
        relationship_type_id,
        emergency_phone
    )
    VALUES
    (?, ?, ?, ?)"

);

mysqli_stmt_bind_param(

    $stmt,

    "isis",

    $staff_id,
    $emergency_name,
    $relationship_type_id,
    $emergency_phone

);

mysqli_stmt_execute($stmt);

if(mysqli_stmt_affected_rows($stmt) <= 0){

    throw new Exception("Failed to save emergency contact.");

}
// =============================
// Insert Staff Qualifications
// =============================

// =============================
// Insert Staff Qualifications
// =============================

if(isset($_POST["qualification_id"])){

    $count = count($_POST["qualification_id"]);

    for($i = 0; $i < $count; $i++){

        if(empty($_POST["qualification_id"][$i])){
            continue;
        }
        echo "Loop: " . $i . "<br>";
echo "Qualification ID: " . $_POST["qualification_id"][$i] . "<br>";

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
    die(mysqli_stmt_error($stmt));
}

        if(mysqli_stmt_affected_rows($stmt) <= 0){

            throw new Exception("Failed to save qualification.");

        }

    }

}
saveStaffDocument($conn,$staff_id,1,"photo");

saveStaffDocument($conn,$staff_id,7,"resume");

saveStaffDocument($conn,$staff_id,8,"degree_certificate");

saveStaffDocument($conn,$staff_id,9,"id_proof");

saveStaffDocument($conn,$staff_id,10,"address_proof");

saveStaffDocument($conn,$staff_id,11,"experience_certificate");

saveStaffDocument($conn,$staff_id,12,"joining_letter");
// =============================
// Upload Documents
// =============================


// =============================
// Create Login
// =============================

$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

$role_id = 2; // Staff

$stmt = mysqli_prepare(

    $conn,

    "INSERT INTO users
    (
        staff_id,
        register_number,
        email,
        password,
        role_id
    )
    VALUES
    (?, ?, ?, ?, ?)"

);

mysqli_stmt_bind_param(

    $stmt,

    "isssi",

    $staff_id,
    $register_number,
    $email,
    $password,
    $role_id

);

mysqli_stmt_execute($stmt);

if(mysqli_stmt_affected_rows($stmt) <= 0){

    throw new Exception("Failed to create login.");

}

mysqli_commit($conn);

echo "<script>
alert('Staff Registered Successfully');
window.location='login.php';
</script>";
}
catch(Exception $e){

    mysqli_rollback($conn);

    echo "<h2>Error:</h2>";
    echo $e->getMessage();

}

?>