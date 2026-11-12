<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== NULL) {
        echo "<pre>";
        print_r($error);
        echo "</pre>";
    }
});

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// =========================================
// CHECK LOGIN
// =========================================

if(
    !isset($_SESSION["role_id"]) ||
    $_SESSION["role_id"] != 3
){
    header("Location: login.php");
    exit();
}

if(!isset($_POST["student_id"])){

    header("Location: manage_students.php");
    exit();
}

$student_id = intval($_POST["student_id"]);

// =========================================
// LOAD STUDENT
// =========================================

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM students
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt,"i",$student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$student = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// =========================================
// LOAD USER
// =========================================

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM users
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt,"i",$student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// =========================================
// LOAD ADDRESS
// =========================================

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM student_addresses
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt,"i",$student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$address = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// =========================================
// LOAD FAMILY
// =========================================

$family = [];

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM family_details
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt,"i",$student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while($row = mysqli_fetch_assoc($result)){

    $family[$row["relationship_type_id"]] = $row;

}

mysqli_stmt_close($stmt);

// =========================================
// LOAD EMERGENCY CONTACT
// =========================================

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM emergency_contacts
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt,"i",$student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$emergency = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// =========================================
// LOAD ACADEMICS
// =========================================

$academic = [];

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM academic_details
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt,"i",$student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while($row = mysqli_fetch_assoc($result)){

    $academic[$row["qualification_id"]] = $row;

}

mysqli_stmt_close($stmt);

// =========================================
// LOAD SIBLINGS
// =========================================

$siblings = [];

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM siblings
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt,"i",$student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while($row = mysqli_fetch_assoc($result)){

    $siblings[] = $row;

}
mysqli_stmt_close($stmt);


// =========================================
// UPDATE PROFILE
// =========================================

// =========================================
// UPDATE PROFILE
// =========================================

if($_SERVER["REQUEST_METHOD"]=="POST"){

    mysqli_begin_transaction($conn);

    try{

        // =========================================
        // STUDENT DETAILS
        // =========================================

        $student_name = trim($_POST["student_name"]);
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phone"]);

        $gender_id = intval($_POST["gender_id"]);

        $date_of_birth = $_POST["date_of_birth"];

        $department_id = intval($_POST["department_id"]);

$program_level_id = intval($_POST["program_level"]);

$year_id = intval($_POST["year_id"]);

        // =========================================
        // ADDRESS
        // =========================================

        $permanent_address = trim($_POST["address"]);

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
        // EMERGENCY
        // =========================================

        $emergency_name = trim($_POST["emergency_name"]);

        $relationship_type_id = intval($_POST["relationship_type_id"]);

        $emergency_phone =
        trim($_POST["emergency_phone"]);

        // =========================================
        // ACADEMICS
        // =========================================

        $tenth_marks =
        floatval($_POST["tenth_marks"]);

        $tenth_school =
        trim($_POST["tenth_school"]);

        $twelfth_marks =
        floatval($_POST["twelfth_marks"]);

        $twelfth_school =
        trim($_POST["twelfth_school"]);

        $board_id = intval($_POST["board_id"]);

        // =========================================
        // SIBLINGS
        // =========================================

        $has_siblings =
        $_POST["has_siblings"];

        $sibling_names =
        $_POST["sibling_name"] ?? [];

        $relationship_type_ids =
        $_POST["sibling_type"] ?? [];

        // =========================================
        // UPDATE STUDENT
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE students

SET

student_name=?,
email=?,
phone=?,
gender_id=?,
date_of_birth=?,
department_id=?,
program_level_id=?,
year_id=?

WHERE student_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sssisiiii",

            $student_name,
            $email,
            $phone,
            $gender_id,
            $date_of_birth,
            $department_id,
            $program_level_id,
            $year_id,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // UPDATE USERS
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE users

            SET

            email=?

            WHERE student_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "si",

            $email,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
                // =========================================
        // CHANGE PASSWORD (OPTIONAL)
        // =========================================

        if(
            !empty($_POST["current_password"]) &&
            !empty($_POST["new_password"]) &&
            !empty($_POST["confirm_new_password"])
        ){

            if($_POST["new_password"] != $_POST["confirm_new_password"]){

                throw new Exception(
                    "New passwords do not match."
                );

            }

            $stmt = mysqli_prepare(

                $conn,

                "SELECT password
                 FROM users
                 WHERE student_id=?"

            );

            mysqli_stmt_bind_param(

                $stmt,

                "i",

                $student_id

            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $user = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            if(
                !password_verify(
                    $_POST["current_password"],
                    $user["password"]
                )
            ){

                throw new Exception(
                    "Current password is incorrect."
                );

            }

            $newPassword = password_hash(

                $_POST["new_password"],

                PASSWORD_DEFAULT

            );

            $stmt = mysqli_prepare(

                $conn,

                "UPDATE users
                 SET password=?
                 WHERE student_id=?"

            );

            mysqli_stmt_bind_param(

                $stmt,

                "si",

                $newPassword,
                $student_id

            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);

        }

        // =========================================
        // UPDATE ADDRESS
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE student_addresses

             SET

                permanent_address=?,
                temporary_address=?

             WHERE student_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "ssi",

            $permanent_address,
            $temporary_address,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
                // =========================================
        // UPDATE FATHER
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE family_details

             SET

                name=?,
                occupation=?,
                phone=?

             WHERE student_id=?
             AND relationship_type_id=1"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sssi",

            $father_name,
            $father_occupation,
            $father_phone,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // UPDATE MOTHER
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE family_details

             SET

                name=?,
                occupation=?,
                phone=?

             WHERE student_id=?
             AND relationship_type_id=2"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sssi",

            $mother_name,
            $mother_occupation,
            $mother_phone,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // UPDATE GUARDIAN
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE family_details

             SET

                name=?,
                occupation=NULL,
                phone=?

             WHERE student_id=?
             AND relationship_type_id=3"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "ssi",

            $guardian_name,
            $guardian_phone,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // UPDATE EMERGENCY CONTACT
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE emergency_contacts

             SET

                emergency_name=?,
                relationship_type_id=?,
                emergency_phone=?

             WHERE student_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sisi",

            $emergency_name,
            $relationship_type_id,
            $emergency_phone,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // UPDATE 10TH DETAILS
        // =========================================

        $qualification_id = 1;

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE academic_details

             SET

                school_name=?,
                percentage=?,
                board_id=?

             WHERE student_id=?
             AND qualification_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sdiii",

            $tenth_school,
            $tenth_marks,
            $board_id,
            $student_id,
            $qualification_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // UPDATE 12TH DETAILS
        // =========================================

        $qualification_id = 2;

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE academic_details

             SET

                school_name=?,
                percentage=?,
                board_id=?

             WHERE student_id=?
             AND qualification_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sdiii",

            $twelfth_school,
            $twelfth_marks,
            $board_id,
            $student_id,
            $qualification_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
                // =========================================
        // DELETE OLD SIBLINGS
        // =========================================

        $stmt = mysqli_prepare(

            $conn,

            "DELETE FROM siblings
             WHERE student_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "i",

            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =========================================
        // INSERT UPDATED SIBLINGS
        // =========================================

        if($has_siblings=="Yes"){

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

            for($i = 0; $i < count($sibling_names); $i++){

    $name = trim($sibling_names[$i]);

    $relationship_type_id = isset($relationship_type_ids[$i])
        ? intval($relationship_type_ids[$i])
        : 0;

    if($name == ""){
        continue;
    }

    if($relationship_type_id == 0){
        continue;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "isi",
        $student_id,
        $name,
        $relationship_type_id
    );

    mysqli_stmt_execute($stmt);
}

            mysqli_stmt_close($stmt);

        }

        // =========================================
        // COMMIT
        // =========================================

        mysqli_commit($conn);

        echo "<script>

            alert('Profile Updated Successfully.');

            window.location='manage_students.php';

        </script>";

        exit();

    }

    catch (Exception $e) {

    mysqli_rollback($conn);

    echo $e->getMessage();

    exit();

}

    }



?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Update Student Profile</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="container">

<h1>Update Student Profile</h1>

<form method="POST" enctype="multipart/form-data">

<!-- ========================================= -->
<!-- PERSONAL INFORMATION -->
<!-- ========================================= -->

<h2 class="form-section">

Personal Information

</h2>

<label>Student Name</label>

<input
type="text"
name="student_name"
value="<?php echo htmlspecialchars($student["student_name"]); ?>"
required>

<label>Email</label>

<input
type="email"
name="email"
value="<?php echo htmlspecialchars($student["email"]); ?>"
required>

<label>Phone Number</label>

<input
type="text"
name="phone"
value="<?php echo htmlspecialchars($student["phone"]); ?>"
required>

<label>Gender</label>

<select
name="gender"
required>

<option value="">Select Gender</option>

<?php

$result = mysqli_query(

$conn,

"SELECT * FROM genders"

);

while($row=mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["gender_id"]; ?>"

<?php

if($student["gender_id"]==$row["gender_id"])
echo "selected";

?>

>

<?php echo $row["gender_name"]; ?>

</option>

<?php

}

?>

</select>

<label>Date of Birth</label>

<input
type="date"
name="date_of_birth"
value="<?php echo $student["date_of_birth"]; ?>"
required>

<label>Department</label>

<select
name="department"
required>

<option value="">Select Department</option>

<?php

$result = mysqli_query(

$conn,

"SELECT * FROM departments"

);

while($row=mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["department_id"]; ?>"

<?php

if($student["department_id"]==$row["department_id"])
echo "selected";

?>

>

<?php echo $row["department_name"]; ?>

</option>

<?php

}

?>

</select>
<label>Program Level</label>

<select
name="program_level"
required>

<option value="">Select Program Level</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM program_levels"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["program_level_id"]; ?>"

<?php

if($student["program_level_id"] == $row["program_level_id"])
echo "selected";

?>

>

<?php echo htmlspecialchars($row["program_level_name"]); ?>

</option>

<?php

}

?>

</select>

<label>Year of Study</label>

<select
name="year_of_study"
required>

<option value="">Select Year</option>

<?php

$result = mysqli_query(

$conn,

"SELECT * FROM years"

);

while($row=mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["year_id"]; ?>"

<?php

if($student["year_id"]==$row["year_id"])
echo "selected";

?>

>

<?php echo $row["year_name"]; ?>

</option>

<?php

}

?>

</select>
<!-- ========================================= -->
<!-- ADDRESS DETAILS -->
<!-- ========================================= -->

<h2 class="form-section">

Address Details

</h2>

<label>Permanent Address</label>

<textarea
name="address"
rows="4"
required><?php echo htmlspecialchars($address["permanent_address"]); ?></textarea>

<label>Temporary Address</label>

<textarea
name="temporary_address"
rows="4"><?php echo htmlspecialchars($address["temporary_address"]); ?></textarea>

<!-- ========================================= -->
<!-- PARENT / GUARDIAN DETAILS -->
<!-- ========================================= -->

<h2 class="form-section">

Parent / Guardian Details

</h2>

<label>Father's Name</label>

<input
type="text"
name="father_name"
value="<?php echo htmlspecialchars($family[1]["name"] ?? ""); ?>"
required>

<label>Father's Occupation</label>

<input
type="text"
name="father_occupation"
value="<?php echo htmlspecialchars($family[1]["occupation"] ?? ""); ?>"
required>

<label>Father's Phone Number</label>

<input
type="text"
name="father_phone"
value="<?php echo htmlspecialchars($family[1]["phone"] ?? ""); ?>"
required>

<label>Mother's Name</label>

<input
type="text"
name="mother_name"
value="<?php echo htmlspecialchars($family[2]["name"] ?? ""); ?>"
required>

<label>Mother's Occupation</label>

<input
type="text"
name="mother_occupation"
value="<?php echo htmlspecialchars($family[2]["occupation"] ?? ""); ?>"
required>

<label>Mother's Phone Number</label>

<input
type="text"
name="mother_phone"
value="<?php echo htmlspecialchars($family[2]["phone"] ?? ""); ?>"
required>

<label>Guardian Name</label>

<input
type="text"
name="guardian_name"
value="<?php echo htmlspecialchars($family[3]["name"] ?? ""); ?>">

<label>Guardian Phone Number</label>

<input
type="text"
name="guardian_phone"
value="<?php echo htmlspecialchars($family[3]["phone"] ?? ""); ?>">
<!-- ========================================= -->
<!-- SIBLING DETAILS -->
<!-- ========================================= -->

<h2 class="form-section">

Sibling Details

</h2>

<label>Do you have siblings?</label>

<div class="gender">

<label>

<input
type="radio"
name="has_siblings"
id="siblings_yes"
value="Yes"

<?php if(count($siblings)>0) echo "checked"; ?>

onclick="toggleSiblingSection()">

Yes

</label>

<label>

<input
type="radio"
name="has_siblings"
id="siblings_no"
value="No"

<?php if(count($siblings)==0) echo "checked"; ?>

onclick="toggleSiblingSection()">

No

</label>

</div>

<div
id="siblings_section"

style="<?php if(count($siblings)==0) echo 'display:none;'; ?>">

<div id="siblings_container">

<?php

if(count($siblings)>0){

foreach($siblings as $sibling){

?>

<div class="sibling-card">

<label>Sibling Name</label>

<input
type="text"
name="sibling_name[]"
value="<?php echo htmlspecialchars($sibling["sibling_name"]); ?>">

<label>Relationship</label>

<select name="sibling_type[]">

<option value="">Select</option>

<?php

$result = mysqli_query(

$conn,

"SELECT *
FROM relationship_types
WHERE relationship_name IN ('Brother','Sister')"

);

while($row=mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["relationship_type_id"]; ?>"

<?php

if($row["relationship_type_id"]==$sibling["relationship_type_id"])
echo "selected";

?>

>

<?php echo $row["relationship_name"]; ?>

</option>

<?php

}

?>

</select>

<button
type="button"
onclick="this.parentElement.remove();">

Remove

</button>

</div>

<?php

}

}

?>

</div>

<br>

<button
type="button"
onclick="addSibling()">

+ Add Another Sibling

</button>

</div>

<!-- ========================================= -->
<!-- EMERGENCY CONTACT -->
<!-- ========================================= -->

<h2 class="form-section">

Emergency Contact

</h2>


<label>Emergency Contact Name</label>

<input
type="text"
name="emergency_name"
value="<?php echo htmlspecialchars($emergency["emergency_name"] ?? ""); ?>"
required>

<label>Relationship</label>

<select name="relationship" required>

<option value="">Select Relationship</option>

<?php

$result = mysqli_query($conn,"SELECT * FROM relationship_types");

if(!$result){
    die("SQL Error: " . mysqli_error($conn));
}

while($row = mysqli_fetch_assoc($result)){

?>

<option
value="<?php echo $row["relationship_type_id"]; ?>"

<?php
if(($emergency["relationship_type_id"] ?? "") == $row["relationship_type_id"])
echo "selected";
?>

>

<?php echo htmlspecialchars($row["relationship_name"]); ?>

</option>

<?php

}

?>

</select>



<label>Emergency Contact Number</label>

<input
type="text"
name="emergency_phone"
value="<?php echo htmlspecialchars($emergency["emergency_phone"] ?? ""); ?>"
required>

<!-- ========================================= -->
<!-- ACADEMIC DETAILS -->
<!-- ========================================= -->

<h2 class="form-section">

Academic Details

</h2>

<label>10th School Name</label>

<input
type="text"
name="tenth_school"
value="<?php echo htmlspecialchars($academic[1]["school_name"] ?? ""); ?>"
required>

<label>10th Percentage</label>

<input
type="number"
step="0.01"
name="tenth_marks"
value="<?php echo htmlspecialchars($academic[1]["percentage"] ?? ""); ?>"
required>

<label>12th School Name</label>

<input
type="text"
name="twelfth_school"
value="<?php echo htmlspecialchars($academic[2]["school_name"] ?? ""); ?>"
required>

<label>12th Percentage</label>

<input
type="number"
step="0.01"
name="twelfth_marks"
value="<?php echo htmlspecialchars($academic[2]["percentage"] ?? ""); ?>"
required>

<label>Board</label>

<select
name="board"
required>

<option value="">Select Board</option>

<?php

$result = mysqli_query($conn,"SELECT * FROM boards");

while($row=mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["board_id"]; ?>"

<?php

if(($academic[1]["board_id"] ?? "")==$row["board_id"])
echo "selected";

?>

>

<?php echo $row["board_name"]; ?>

</option>

<?php

}

?>

</select>
<!-- ========================================= -->
<!-- ACCOUNT -->
<!-- ========================================= -->

<h2 class="form-section">

Account Settings

</h2>

<label>Current Password</label>

<div class="password-box">

<input
type="password"
id="currentPassword"
name="current_password"
placeholder="Enter Current Password">

<i class="fa-solid fa-eye"
id="toggleCurrentPassword"></i>

</div>

<label>New Password</label>

<div class="password-box">

<input
type="password"
id="newPassword"
name="new_password"
placeholder="Leave blank if not changing">

<i class="fa-solid fa-eye"
id="toggleNewPassword"></i>

</div>

<label>Confirm New Password</label>

<div class="password-box">

<input
type="password"
id="confirmPassword"
name="confirm_new_password"
placeholder="Confirm New Password">

<i class="fa-solid fa-eye"
id="toggleConfirmPassword"></i>

</div>

<!-- ========================================= -->
<!-- DOCUMENTS -->
<!-- ========================================= -->

<h2 class="form-section">

Documents

</h2>

<p style="color:green;">

Current documents are kept.
You can add document replacement later.

</p>

<!-- ========================================= -->
<!-- BUTTONS -->
<!-- ========================================= -->

<div class="button-group">

<button type="submit">

Update Profile

</button>

<a href="dashboard.php">

<button
type="button">

Cancel

</button>

</a>

</div>

</form>

</div>


<script src="script.js"></script>

<script>

function toggleSiblingSection(){

    const yes = document.getElementById("siblings_yes");

    const section = document.getElementById("siblings_section");

    const container = document.getElementById("siblings_container");

    if(yes.checked){

        section.style.display = "block";

        if(container.children.length === 0){

            addSibling();

        }

    }else{

        section.style.display = "none";

        container.innerHTML = "";

    }

}

function addSibling(){

    let container=document.getElementById("siblings_container");

    let div=document.createElement("div");

    div.className="sibling-card";

    div.innerHTML=`
        <label>Sibling Name</label>

        <input
        type="text"
        name="sibling_name[]">

        <label>Relationship</label>

        <select name="sibling_type[]">

    <option value="">Select</option>

    <option value="4">Brother</option>

    <option value="5">Sister</option>

</select>

        <button
        type="button"
        onclick="this.parentElement.remove();">

        Remove

        </button>
    `;

    container.appendChild(div);

}

</script>

</body>

</html>
<!-- END OF FILE TEST -->