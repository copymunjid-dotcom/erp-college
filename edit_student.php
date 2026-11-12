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

$student_id = $_GET["id"];
$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM students
     WHERE student_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $student_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$student = mysqli_fetch_assoc($result);
$stmt = mysqli_prepare(

    $conn,

    "SELECT *

     FROM student_addresses

     WHERE student_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $student_id

);

mysqli_stmt_execute($stmt);

$address_result = mysqli_stmt_get_result($stmt);

$address = mysqli_fetch_assoc($address_result);
$stmt = mysqli_prepare(

    $conn,

    "SELECT *

     FROM family_details

     WHERE student_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $student_id

);

mysqli_stmt_execute($stmt);

$family_result = mysqli_stmt_get_result($stmt);

$father = [];
$mother = [];
$guardian = [];

while($row = mysqli_fetch_assoc($family_result)){

    if($row["relationship_type_id"] == 1){

        $father = $row;

    }
    elseif($row["relationship_type_id"] == 2){

        $mother = $row;

    }
    elseif($row["relationship_type_id"] == 3){

        $guardian = $row;

    }

}
$stmt = mysqli_prepare(

    $conn,

    "SELECT *

     FROM emergency_contacts

     WHERE student_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $student_id

);

mysqli_stmt_execute($stmt);

$emergency_result = mysqli_stmt_get_result($stmt);

$emergency = mysqli_fetch_assoc($emergency_result);
$stmt = mysqli_prepare(

    $conn,

    "SELECT *

     FROM academic_details

     WHERE student_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $student_id

);

mysqli_stmt_execute($stmt);

$academic_result = mysqli_stmt_get_result($stmt);

$academic = [];

while($row = mysqli_fetch_assoc($academic_result)){

    $academic[$row["qualification_id"]] = $row;

}
$stmt = mysqli_prepare(

    $conn,

    "SELECT *

     FROM student_documents

     WHERE student_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $student_id

);

mysqli_stmt_execute($stmt);

$document_result = mysqli_stmt_get_result($stmt);

$documents = [];

while($row = mysqli_fetch_assoc($document_result)){

    $documents[$row["document_type_id"]] = $row;

}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Update Student</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="container">

    <h1>Update Student</h1>

    <form action="update_student_admin.php" method="POST" enctype="multipart/form-data">

<input
type="hidden"
name="student_id"
value="<?php echo $student["student_id"]; ?>">

    <!-- ===========================
            PERSONAL INFORMATION
    ============================ -->

    <h2 class="form-section">
        Personal Information
    </h2>

    <label>Student Name</label>

    <input
type="text"
name="student_name"
value="<?php echo htmlspecialchars($student["student_name"]); ?>"
required>

    <label>Register Number</label>

    <input
type="text"
name="register_number"
value="<?php echo htmlspecialchars($student["register_number"]); ?>"
required>

    <label>Email</label>

    <input
type="email"
name="email"
value="<?php echo htmlspecialchars($student["email"]); ?>"
required>

    <label>Phone Number</label>

    <input
type="tel"
name="phone"
pattern="[0-9]{10}"
value="<?php echo htmlspecialchars($student["phone"]); ?>"
required>

    <label>Gender</label>

    <div class="gender">

        <label>

            <input
type="radio"
name="gender_id"
value="1"

<?php

if($student["gender_id"]==2){
    echo "checked";
}

?>
>

            Male

        </label>

        <label>

            <input
type="radio"
name="gender_id"
value="2"

<?php

if($student["gender_id"]==1){

    echo "checked";

}

?>
>

            Female

        </label>

        <label>

            <input
type="radio"
name="gender_id"
value="3"

<?php

if($student["gender_id"]==3){

    echo "checked";

}

?>
>

            Others

        </label>

    </div>

    <label>Date of Birth</label>

    <input
type="date"
name="date_of_birth"
value="<?php echo $student["date_of_birth"]; ?>"
required>
    <label>Program Level <span style="color:red;">*</span></label>

<select name="program_level" required>

    <option value="">Select Program Level</option>

    <?php

    $result = mysqli_query($conn, "SELECT * FROM program_levels");

    while($row = mysqli_fetch_assoc($result)){

    ?>

        <option
value="<?php echo $row["program_level_id"]; ?>"

<?php

if($student["program_level_id"] == $row["program_level_id"]){

    echo "selected";

}

?>>

            <?php echo htmlspecialchars($row["program_level_name"]); ?>

        </option>

    <?php

    }

    ?>

</select>

    <label>Department</label>

    <select class="options"
name="department_id"
required>

    <option value="">Select Department</option>

    <?php

    $result = mysqli_query($conn, "SELECT * FROM departments ORDER BY department_name");

    while($row = mysqli_fetch_assoc($result)){

    ?>

        <option
value="<?php echo $row["department_id"]; ?>"

<?php

if($student["department_id"] == $row["department_id"]){

    echo "selected";

}

?>>

            <?php echo $row["department_name"]; ?>

        </option>

    <?php

    }

    ?>

</select>
    <label>Year of Study</label>

    <select class="options"
     name="year_id"
    required>

        <option value="">Select Year</option>
<option
value="1"

<?php if($student["year_id"]==1) echo "selected"; ?>

>

1st Year

</option>
<option
value="2"

<?php if($student["year_id"]==2) echo "selected"; ?>

>

2nd Year

</option>
<option
value="3"

<?php if($student["year_id"]==3) echo "selected"; ?>

>

3rd Year

</option>
<option
value="4"

<?php if($student["year_id"]==4) echo "selected"; ?>

>

4rth Year

</option>

    </select>

    <!-- ===========================
            ADDRESS DETAILS
    ============================ -->

    <h2 class="form-section">

        Address Details

    </h2>

    <label for="address">

        Permanent Address

    </label>

    <textarea
id="address"
name="address"
rows="4"
required><?php echo htmlspecialchars($address["permanent_address"]); ?></textarea>

    <div class="checkbox-container">

    <label for="sameAddress">

        <input
            type="checkbox"
            id="sameAddress"
            name="same_address">

        Same as Permanent Address

    </label>

</div>

    <label for="temporary_address">

        Temporary Address

    </label>

    <textarea
id="temporary_address"
name="temporary_address"
rows="4"><?php echo htmlspecialchars($address["temporary_address"]); ?></textarea>
<!-- ===========================
        PARENT / GUARDIAN DETAILS
=========================== -->

<h2 class="form-section">

    Parent / Guardian Details

</h2>

<label>Father's Name</label>

<input
type="text"
name="father_name"
value="<?php echo htmlspecialchars($father["name"] ?? ""); ?>"
required>

<label>Father's Occupation</label>

<input
type="text"
name="father_occupation"
value="<?php echo htmlspecialchars($father["occupation"] ?? ""); ?>"
required>

<label>Father's Phone Number</label>

<input
type="tel"
name="father_phone"
pattern="[0-9]{10}"
value="<?php echo htmlspecialchars($father["phone"] ?? ""); ?>"
required>

<label>Mother's Name</label>

<input
type="text"
name="mother_name"
value="<?php echo htmlspecialchars($mother["name"] ?? ""); ?>"
required>

<label>Mother's Occupation</label>

<input
type="text"
name="mother_occupation"
value="<?php echo htmlspecialchars($mother["occupation"] ?? ""); ?>"
required>

<label>Mother's Phone Number</label>

<input
type="tel"
name="mother_phone"
pattern="[0-9]{10}"
value="<?php echo htmlspecialchars($mother["phone"] ?? ""); ?>"
required>

<label class="optional">Guardian Name (Optional)</label>

<input
type="text"
name="guardian_name"
value="<?php echo htmlspecialchars($guardian["name"] ?? ""); ?>">

<label class="optional">Guardian Phone Number (Optional)</label>

<input
type="tel"
name="guardian_phone"
pattern="[0-9]{10}"
value="<?php echo htmlspecialchars($guardian["phone"] ?? ""); ?>">

<!-- ===========================
        SIBLING DETAILS
=========================== -->

<h2 class="form-section">

    Sibling Details

</h2>

<label>

Do you have siblings?

</label>

<div class="gender">

    <label>

        <input
type="radio"
id="siblings_yes"
name="has_siblings"
value="Yes">
        Yes

    </label>

    <label>

        <input
type="radio"
id="siblings_no"
name="has_siblings"
value="No"
checked>

        No

    </label>

</div>

<div id="siblings_container" style="display:none;">

    <div class="table-wrapper">

        <table id="siblingsTable" class="data-table">

            <thead>

                <tr>

                    <th>Sibling Name</th>

                    <th>Gender</th>

                    

                    <th>Age</th>

                    <th>Occupation</th>

                    <th>Action</th>

                </tr>

            </thead>

            <tbody id="siblingsBody">

                <tr id="siblingTemplate" style="display:none;">

                    <td>

                        <input
                        type="text"
                        name="sibling_name[]">

                    </td>

                    <td>

                        <select name="sibling_type[]">

    <option value="">Select</option>

    <?php

    $result = mysqli_query(
        $conn,
        "SELECT * FROM relationship_types
         WHERE relationship_name IN ('Brother','Sister')"
    );

    while($row = mysqli_fetch_assoc($result)){
    ?>

        <option value="<?php echo $row["relationship_type_id"]; ?>">

            <?php echo htmlspecialchars($row["relationship_name"]); ?>

        </option>

    <?php } ?>

</select>   

                    </td>

                    

                    <td>

                        <input
                        type="number"
                        name="sibling_age[]">

                    </td>

                    <td>

                        <input
                        type="text"
                        name="occupation[]">

                    </td>

                    <td>

                        <button
                        type="button"
                        class="delete-btn"
                        onclick="removeSiblingRow(this)">

                            <i class="fa-solid fa-trash"></i>

                        </button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <br>

    <button
    type="button"
    onclick="addSiblingRow()">

        + Add Another Sibling

    </button>

</div>

<!-- ===========================
        EMERGENCY CONTACT
=========================== -->

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

<select
name="relationship_type_id"
required>

<option value="">Select Relationship</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM relationship_types
     ORDER BY relationship_name"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["relationship_type_id"]; ?>"

<?php

if(
    ($emergency["relationship_type_id"] ?? 0)
    == $row["relationship_type_id"]
){
    echo "selected";
}

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
type="tel"
name="emergency_phone"
pattern="[0-9]{10}"
value="<?php echo htmlspecialchars($emergency["emergency_phone"] ?? ""); ?>"
required>

<!-- ===========================
        PART 3 STARTS HERE
=========================== -->
<!-- ===========================
        ACADEMIC DETAILS
=========================== -->

<h2 class="form-section">

    Academic Details

</h2>

<label>10th Percentage</label>

<input
type="number"
name="tenth_marks"
step="0.01"
min="0"
max="100"
value="<?php echo htmlspecialchars($academic[1]["percentage"] ?? ""); ?>"
required>

<label>10th School Name</label>

<input
type="text"
name="tenth_school"
value="<?php echo htmlspecialchars($academic[1]["school_name"] ?? ""); ?>"
required>

<label>12th Percentage</label>

<input
type="number"
name="twelfth_marks"
step="0.01"
min="0"
max="100"
value="<?php echo htmlspecialchars($academic[2]["percentage"] ?? ""); ?>"
required>

<label>12th School Name</label>
<input
type="text"
name="twelfth_school"
value="<?php echo htmlspecialchars($academic[2]["school_name"] ?? ""); ?>"
required>

<label>Board of Education</label>

<select
name="board_id"
required>

<option value="">Select Board</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM boards
     ORDER BY board_name"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["board_id"]; ?>"

<?php

if(
    ($academic[1]["board_id"] ?? 0)
    == $row["board_id"]
){
    echo "selected";
}

?>

>

<?php echo htmlspecialchars($row["board_name"]); ?>

</option>

<?php

}

?>

</select>

<!-- ===========================
        DOCUMENTS
=========================== -->

<h2 class="form-section">

    Documents

</h2>

<label>10th Marksheet</label>

<?php if(isset($documents[2])){ ?>

<p>

Current File:
<strong>

<?php echo htmlspecialchars($documents[2]["file_path"]); ?>

</strong>

</p>

<?php } ?>

<input
type="file"
name="tenth_marksheet"
accept=".pdf,.jpg,.jpeg,.png">

<label>12th Marksheet</label>

<?php if(isset($documents[3])){ ?>

<p>

Current File:
<strong>

<?php echo htmlspecialchars($documents[3]["file_path"]); ?>

</strong>

</p>

<?php } ?>

<input
type="file"
name="twelfth_marksheet"
accept=".pdf,.jpg,.jpeg,.png">

<label>Photo</label>

<?php if(isset($documents[1])){ ?>

<img

src="uploads/photos/<?php echo htmlspecialchars($documents[1]["file_path"]); ?>"

width="120"

style="border-radius:10px;display:block;margin-bottom:10px;">

<?php } ?>

<input
type="file"
name="photo"
accept=".jpg,.jpeg,.png">

<label>Aadhaar Card</label>

<?php if(isset($documents[4])){ ?>

<p>

Current File:
<strong>

<?php echo htmlspecialchars($documents[4]["file_path"]); ?>

</strong>

</p>

<?php } ?>

<input
type="file"
name="aadhar_card"
accept=".pdf,.jpg,.jpeg,.png">

<label>Transfer Certificate</label>

<?php if(isset($documents[5])){ ?>

<p>

Current File:
<strong>

<?php echo htmlspecialchars($documents[5]["file_path"]); ?>

</strong>

</p>

<?php } ?>

<input
type="file"
name="transfer_certificate"
accept=".pdf,.jpg,.jpeg,.png">

<label>Community Certificate</label>

<?php if(isset($documents[6])){ ?>

<p>

Current File:
<strong>

<?php echo htmlspecialchars($documents[6]["file_path"]); ?>

</strong>

</p>

<?php } ?>

<input
type="file"
name="community_certificate"
accept=".pdf,.jpg,.jpeg,.png">

<!-- ===========================
        ACCOUNT DETAILS
=========================== -->



<div class="button-group">

    <button
    type="submit">

        Update Student

    </button>

    <button
    type="reset">

        Reset Form

    </button>

</div>

</form>

</div>

<script src="script.js"></script>

</body>

</html>