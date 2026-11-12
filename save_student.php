<?php

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// CHECK REQUEST
// =========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // =========================================
    // START DATABASE TRANSACTION
    // =========================================

    mysqli_begin_transaction($conn);

    try {

        // =========================================
        // STUDENT DETAILS
        // =========================================

        $student_name = trim($_POST["student_name"]);
        $register_number = trim($_POST["register_number"]);
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phone"]);

        $gender = $_POST["gender"];

        switch ($gender) {

            case "Male":
                $gender_id = 1;
                break;

            case "Female":
                $gender_id = 2;
                break;

            default:
                $gender_id = 3;
                break;

        }

        $date_of_birth = $_POST["date_of_birth"];

        $department_id = intval($_POST["department_id"]);

        $year_id = intval($_POST["year_id"]);
        $program_level_id = intval($_POST["program_level"]);

        // =========================================
        // ADDRESS
        // =========================================

        $address = trim($_POST["address"]);

        $temporary_address = trim($_POST["temporary_address"]);

        // =========================================
        // FAMILY
        // =========================================

        $father_name = trim($_POST["father_name"]);
        $father_occupation = trim($_POST["father_occupation"]);
        $father_phone = trim($_POST["father_phone"]);

        $mother_name = trim($_POST["mother_name"]);
        $mother_occupation = trim($_POST["mother_occupation"]);
        $mother_phone = trim($_POST["mother_phone"]);

        $guardian_name = trim($_POST["guardian_name"]);
        $guardian_phone = trim($_POST["guardian_phone"]);

        // =========================================
        // EMERGENCY CONTACT
        // =========================================

        $emergency_name = trim($_POST["emergency_name"]);

        $relationship_type_id = intval($_POST["relationship_type_id"]);

        $emergency_phone = trim($_POST["emergency_phone"]);

        // =========================================
        // ACADEMICS
        // =========================================

        $tenth_marks = floatval($_POST["tenth_marks"]);
        $tenth_school = trim($_POST["tenth_school"]);

        $twelfth_marks = floatval($_POST["twelfth_marks"]);
        $twelfth_school = trim($_POST["twelfth_school"]);

        $board_id = intval($_POST["board_id"]);

        // =========================================
        // PASSWORD
        // =========================================

        $password = password_hash(

            $_POST["password"],

            PASSWORD_DEFAULT

        );

        // =========================================
        // SIBLINGS
        // =========================================

        $has_siblings = $_POST["has_siblings"];

        $sibling_names = $_POST["sibling_name"] ?? [];

        $relationship_type_ids =
            $_POST["sibling_relationship_type_id"] ?? [];

        // =========================================
        // FILES
        // =========================================

        $photo = $_FILES["photo"]["name"];
        $photo_tmp = $_FILES["photo"]["tmp_name"];

        $tenth_marksheet = $_FILES["tenth_marksheet"]["name"];
        $tenth_tmp = $_FILES["tenth_marksheet"]["tmp_name"];

        $twelfth_marksheet = $_FILES["twelfth_marksheet"]["name"];
        $twelfth_tmp = $_FILES["twelfth_marksheet"]["tmp_name"];

        $aadhar_card = $_FILES["aadhar_card"]["name"];
        $aadhar_tmp = $_FILES["aadhar_card"]["tmp_name"];

        $transfer_certificate =
            $_FILES["transfer_certificate"]["name"];

        $transfer_tmp =
            $_FILES["transfer_certificate"]["tmp_name"];

        $community_certificate = "";

        if (!empty($_FILES["community_certificate"]["name"])) {

            $community_certificate =
                $_FILES["community_certificate"]["name"];

            $community_tmp =
                $_FILES["community_certificate"]["tmp_name"];

        }
                // =========================================
        // CREATE UPLOAD FOLDERS
        // =========================================

        $folders = [

            "uploads/photos",
            "uploads/tenth_marksheets",
            "uploads/twelfth_marksheets",
            "uploads/aadhar_cards",
            "uploads/transfer_certificates",
            "uploads/community_certificates"

        ];

        foreach ($folders as $folder) {

            if (!is_dir($folder)) {

                mkdir($folder, 0777, true);

            }

        }

        // =========================================
        // MOVE FILES
        // =========================================

        move_uploaded_file(
            $photo_tmp,
            "uploads/photos/" . $photo
        );

        move_uploaded_file(
            $tenth_tmp,
            "uploads/tenth_marksheets/" . $tenth_marksheet
        );

        move_uploaded_file(
            $twelfth_tmp,
            "uploads/twelfth_marksheets/" . $twelfth_marksheet
        );

        move_uploaded_file(
            $aadhar_tmp,
            "uploads/aadhar_cards/" . $aadhar_card
        );

        move_uploaded_file(
            $transfer_tmp,
            "uploads/transfer_certificates/" . $transfer_certificate
        );

        if ($community_certificate != "") {

            move_uploaded_file(
                $community_tmp,
                "uploads/community_certificates/" . $community_certificate
            );

        }

        // =========================================
        // CHECK DUPLICATE REGISTER NUMBER / EMAIL
        // =========================================

        $check = mysqli_prepare(

            $conn,

            "SELECT student_id
             FROM students
             WHERE register_number = ?
             OR email = ?"

        );

        mysqli_stmt_bind_param(

            $check,

            "ss",

            $register_number,

            $email

        );

        mysqli_stmt_execute($check);

        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {

            throw new Exception("Register Number or Email already exists.");

        }

        mysqli_stmt_close($check);

        // =========================================
        // INSERT STUDENT
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO students
            (
                student_name,
                register_number,
                email,
                phone,
                gender_id,
                date_of_birth,
                department_id,
                program_level_id,
                year_id
            )
            VALUES
            (?,?,?,?,?,?,?,?,?)"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "ssssisiii",

            $student_name,
            $register_number,
            $email,
            $phone,
            $gender_id,
            $date_of_birth,
            $department_id,
            $program_level_id,
            $year_id

        );

        mysqli_stmt_execute($stmt);

        $student_id = mysqli_insert_id($conn);

        mysqli_stmt_close($stmt);

        // =========================================
        // INSERT USER LOGIN
        // =========================================
        // Default role = Student
$role_id = 1;

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO users
            (
                student_id,
                register_number,
                email,
                password,
                role_id
            )
            VALUES
            (?,?,?,?,?)"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "isssi",

            $student_id,
            $register_number,
            $email,
            $password,
            $role_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // INSERT ADDRESS
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO student_addresses
            (
                student_id,
                permanent_address,
                temporary_address
            )
            VALUES
            (?,?,?)"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "iss",

            $student_id,
            $address,
            $temporary_address

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
                // =========================================
        // INSERT FATHER
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO family_details
            (
                student_id,
                relationship_type_id,
                name,
                occupation,
                phone
            )
            VALUES
            (?,?,?,?,?)"

        );

        $father_relationship = 1;

        mysqli_stmt_bind_param(

            $stmt,

            "iisss",

            $student_id,
            $father_relationship,
            $father_name,
            $father_occupation,
            $father_phone

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // INSERT MOTHER
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO family_details
            (
                student_id,
                relationship_type_id,
                name,
                occupation,
                phone
            )
            VALUES
            (?,?,?,?,?)"

        );

        $mother_relationship = 2;

        mysqli_stmt_bind_param(

            $stmt,

            "iisss",

            $student_id,
            $mother_relationship,
            $mother_name,
            $mother_occupation,
            $mother_phone

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // INSERT GUARDIAN (OPTIONAL)
        // =========================================

        if ($guardian_name != "" || $guardian_phone != "") {

            $stmt = mysqli_prepare(

                $conn,

                "INSERT INTO family_details
                (
                    student_id,
                    relationship_type_id,
                    name,
                    occupation,
                    phone
                )
                VALUES
                (?,?,?,?,?)"

            );

            $guardian_relationship = 3;
            $occupation = NULL;

            mysqli_stmt_bind_param(

                $stmt,

                "iisss",

                $student_id,
                $guardian_relationship,
                $guardian_name,
                $occupation,
                $guardian_phone

            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);

        }

        // =========================================
        // INSERT EMERGENCY CONTACT
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO emergency_contacts
            (
                student_id,
                emergency_name,
                relationship_type_id,
                emergency_phone
            )
            VALUES
            (?,?,?,?)"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "isis",

            $student_id,
            $emergency_name,
            $relationship_type_id,
            $emergency_phone

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // INSERT 10TH DETAILS
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO academic_details
            (
                student_id,
                qualification_id,
                school_name,
                percentage,
                board_id
            )
            VALUES
            (?,?,?,?,?)"

        );

        $qualification = 1;

        mysqli_stmt_bind_param(

            $stmt,

            "iisdi",

            $student_id,
            $qualification,
            $tenth_school,
            $tenth_marks,
            $board_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // INSERT 12TH DETAILS
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO academic_details
            (
                student_id,
                qualification_id,
                school_name,
                percentage,
                board_id
            )
            VALUES
            (?,?,?,?,?)"

        );

        $qualification = 2;

        mysqli_stmt_bind_param(

            $stmt,

            "iisdi",

            $student_id,
            $qualification,
            $twelfth_school,
            $twelfth_marks,
            $board_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
                // =========================================
        // INSERT DOCUMENTS
        // =========================================

        $documents = [

            [1, $photo],
            [2, $tenth_marksheet],
            [3, $twelfth_marksheet],
            [4, $aadhar_card],
            [5, $transfer_certificate]

        ];

        if ($community_certificate != "") {

            $documents[] = [6, $community_certificate];

        }

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO student_documents
            (
                student_id,
                document_type_id,
                file_path
            )
            VALUES
            (?,?,?)"

        );

        foreach ($documents as $document) {

            $document_type_id = $document[0];

            $file_path = $document[1];

            mysqli_stmt_bind_param(

                $stmt,

                "iis",

                $student_id,
                $document_type_id,
                $file_path

            );

            mysqli_stmt_execute($stmt);

        }

        mysqli_stmt_close($stmt);

        // =========================================
        // INSERT SIBLINGS
        // =========================================

        if ($has_siblings == "Yes") {

            $stmt = mysqli_prepare(

                $conn,

                "INSERT INTO siblings
                (
                    student_id,
                    sibling_name,
                    relationship_type_id
                )
                VALUES
                (?,?,?)"

            );

            for ($i = 0; $i < count($sibling_names); $i++) {

                $name = trim($sibling_names[$i]);

                $relationship_id = intval($relationship_type_ids[$i]);

                if ($name != "") {

                    mysqli_stmt_bind_param(

                        $stmt,

                        "isi",

                        $student_id,
                        $name,
                        $relationship_id

                    );

                    mysqli_stmt_execute($stmt);

                }

            }

            mysqli_stmt_close($stmt);

        }

        // =========================================
        // COMMIT TRANSACTION
        // =========================================

        mysqli_commit($conn);

        echo "<script>

        alert('Student Registered Successfully');

        window.location='login.php';

        </script>";

    }

    catch (Exception $e) {

        mysqli_rollback($conn);

        echo "<script>

        alert('".$e->getMessage()."');

        window.history.back();

        </script>";

    }

}

mysqli_close($conn);

?>