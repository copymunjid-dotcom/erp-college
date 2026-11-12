<?php

session_start();

if(
    !isset($_SESSION["staff_id"]) ||
    $_SESSION["role_id"] != 2
){
    header("Location: login.php");
    exit();
}

require "database.php";
$staff_id = $_SESSION["staff_id"];

$stmt = mysqli_prepare(

    $conn,

    "SELECT
        s.*,
        g.gender_name,
        d.department_name,
        des.designation_name
     FROM staff s

     LEFT JOIN genders g
     ON s.gender_id = g.gender_id

     LEFT JOIN departments d
     ON s.department_id = d.department_id

     LEFT JOIN designations des
     ON s.designation_id = des.designation_id

     WHERE s.staff_id = ?"

);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $staff_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$staff = mysqli_fetch_assoc($result);

// =============================
// Fetch Address
// =============================

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM staff_addresses
     WHERE staff_id = ?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $staff_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$address = mysqli_fetch_assoc($result);
// =============================
// Fetch Emergency Contact
// =============================

$stmt = mysqli_prepare(

    $conn,

    "SELECT
        sec.*,
        rt.relationship_name
     FROM staff_emergency_contacts sec

     LEFT JOIN relationship_types rt
     ON sec.relationship_type_id = rt.relationship_type_id

     WHERE sec.staff_id = ?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $staff_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$emergency = mysqli_fetch_assoc($result);
// =============================
// Fetch Qualifications
// =============================

$stmt = mysqli_prepare(

    $conn,

    "SELECT
        sq.*,
        sqm.qualification_name
     FROM staff_qualifications sq

     LEFT JOIN staff_qualifications_master sqm
     ON sq.qualification_id = sqm.qualification_id

     WHERE sq.staff_id = ?

     ORDER BY sq.year_of_passing"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $staff_id

);

mysqli_stmt_execute($stmt);

$qualification_result = mysqli_stmt_get_result($stmt);

$stmt = mysqli_prepare(

    $conn,

    "SELECT
        sd.*,
        dt.document_name
     FROM staff_documents sd

     LEFT JOIN document_types dt
     ON sd.document_type_id = dt.document_type_id

     WHERE sd.staff_id = ?

     ORDER BY dt.document_name"

);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $staff_id
);

mysqli_stmt_execute($stmt);

$documents_result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Staff Registration</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="container">

    <h1>Staff updation Form</h1>

    <form action="update_staff_process.php" method="POST" enctype="multipart/form-data">
        <input
type="hidden"
name="staff_id"
value="<?php echo $staff["staff_id"]; ?>">

    <!-- ===========================
            PERSONAL INFORMATION
    ============================ -->

    <h2 class="form-section">
        Personal Information
    </h2>

    <label>Staff Name</label>

    <input
type="text"
name="staff_name"
value="<?php echo htmlspecialchars($staff["staff_name"]); ?>"
required>

    <label>Employee Number</label>

    <input
    type="text"
    name="register_number"
    value="<?php echo htmlspecialchars($staff["register_number"]); ?>"
    required>

    <label>Email</label>

    <input
    type="email"
    name="email"
    value="<?php echo htmlspecialchars($staff["email"]); ?>"
    required>

    <label>Phone Number</label>

    <input
    type="tel"
    name="phone"
    value="<?php echo htmlspecialchars($staff["phone"]); ?>"
    pattern="[0-9]{10}"
    required>

    <label>Gender</label>

<select
name="gender_id"
required>

    <option value="">Select Gender</option>

    <?php

    $result = mysqli_query($conn,
    "SELECT * FROM genders ORDER BY gender_name");

    while($row = mysqli_fetch_assoc($result)){

    ?>

 <option
value="<?php echo $row["gender_id"]; ?>"
<?php
if($staff["gender_id"] == $row["gender_id"]){
    echo "selected";
}
?>>

    <?php echo htmlspecialchars($row["gender_name"]); ?>

</option>
<?php } ?>

</select>
    <label>Date of Birth</label>

    <input
    type="date"
    name="date_of_birth"
    value="<?php echo htmlspecialchars($staff["date_of_birth"]); ?>"
    required>
    
    


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
            <?php if (!empty($staff["same_address"])): ?>checked<?php endif; ?>
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
<h2 class="form-section">

    Employment Information

</h2>

<label>Department</label>

<select
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
if($staff["department_id"] == $row["department_id"]){
    echo "selected";
}
?>>

            <?php echo htmlspecialchars($row["department_name"]); ?>

        </option>

    <?php } ?>

</select>

<label>Designation</label>

<select
name="designation_id"
required>

    <option value="">Select Designation</option>

    <?php

    $result = mysqli_query($conn, "SELECT * FROM designations ORDER BY designation_name");

    while($row = mysqli_fetch_assoc($result)){

    ?>

       <option
value="<?php echo $row["designation_id"]; ?>"
<?php
if($staff["designation_id"] == $row["designation_id"]){
    echo "selected";
}
?>>

            <?php echo htmlspecialchars($row["designation_name"]); ?>

        </option>

    <?php } ?>

</select>


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
value="<?php echo htmlspecialchars($emergency["emergency_name"]); ?>"
required>

<label>Relationship</label>

<select
name="relationship_type_id"
required>

    <option value="">Select Relationship</option>

    <option
value="1"
<?php
if($emergency["relationship_type_id"]==1){
    echo "selected";
}
?>>

Father

</option>

    <option
value="2"
<?php
if($emergency["relationship_type_id"]==2){
    echo "selected";
}
?>>

Mother

</option>

    <option
value="3"
<?php
if($emergency["relationship_type_id"]==3){
    echo "selected";
}
?>>

Brother

</option>

    <option
value="4"
<?php
if($emergency["relationship_type_id"]==4){
    echo "selected";
}
?>>

Sister

</option>

    <option
value="5"
<?php
if($emergency["relationship_type_id"]==5){
    echo "selected";
}
?>>

Guardian

</option>

    <option
value="6"
<?php
if($emergency["relationship_type_id"]==6){
    echo "selected";
}
?>>

Relative

</option>

</select>

<label>Emergency Contact Number</label>

<input
type="tel"
name="emergency_phone"
value="<?php echo htmlspecialchars($emergency["emergency_phone"]); ?>"
pattern="[0-9]{10}"
required>
<!-- ===========================
        EDUCATIONAL QUALIFICATIONS
=========================== -->

<h2 class="form-section">

    Educational Qualifications

</h2>
<div class="table-wrapper">

<table id="qualificationTable">

<thead>

<tr>

<th>Qualification</th>

<th>Specialization</th>

<th>Institution</th>

<th>University</th>

<th>Percentage / CGPA</th>

<th>Year</th>

<th>Action</th>

</tr>

</thead>

<tbody id="qualificationBody">



<tr id="qualificationTemplate" style="display:none;">

    <td>

        <select name="qualification_id[]">

            <option value="">Select</option>

            <?php

            $result = mysqli_query(
                $conn,
                "SELECT * FROM staff_qualifications_master ORDER BY qualification_name"
            );

            while($row = mysqli_fetch_assoc($result)){

            ?>

            <option value="<?php echo $row["qualification_id"]; ?>">

                <?php echo htmlspecialchars($row["qualification_name"]); ?>

            </option>

            <?php } ?>

        </select>

    </td>

    <td>

        <input
        type="text"
        name="specialization[]">

    </td>

    <td>

        <input
        type="text"
        name="institution[]">

    </td>

    <td>

        <input
        type="text"
        name="university_name[]">

    </td>

    <td>

        <input
        type="number"
        step="0.01"
        name="percentage[]">

    </td>

    <td>

        <input
        type="number"
        min="1950"
        max="<?php echo date('Y'); ?>"
        name="year_of_passing[]">

    </td>

    <td>

        <button
type="button"
class="delete-btn"
onclick="removeQualificationRow(this)">

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
onclick="addQualificationRow()">

+ Add Another Qualification

</button>
<!-- ===========================
        DOCUMENTS
=========================== -->

<h2 class="form-section">

    Documents

</h2>

<label>Profile Photo</label>

<input
type="file"
name="photo"
accept=".jpg,.jpeg,.png">

<label>Resume</label>

<input
type="file"
name="resume"
accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

<label>Highest Qualification Certificate</label>

<input
type="file"
name="degree_certificate"
accept=".pdf,.jpg,.jpeg,.png">

<label>ID Proof</label>

<input
type="file"
name="id_proof"
accept=".pdf,.jpg,.jpeg,.png">

<label>Address Proof</label>

<input
type="file"
name="address_proof"
accept=".pdf,.jpg,.jpeg,.png">

<label class="optional">
Experience Certificate (Optional)
</label>

<input
type="file"
name="experience_certificate"
accept=".pdf,.jpg,.jpeg,.png">

<label class="optional">
Joining Letter (Optional)
</label>

<input
type="file"
name="joining_letter"
accept=".pdf,.jpg,.jpeg,.png">
<!-- ===========================
        CHANGE PASSWORD
=========================== -->

<h2 class="form-section">

    Change Password

</h2>

<label>

Current Password

</label>

<input
type="password"
name="current_password">

<label>

New Password

</label>

<input
type="password"
name="new_password">

<label>

Confirm New Password

</label>

<input
type="password"
name="confirm_password">


<!-- ===========================
        PART 3 STARTS HERE
=========================== -->
<div class="button-group">

    <button type="submit">

        Update Profile

    </button>

    <a href="staff_dashboard.php">

        Cancel

    </a>

</div>




<script src="script.js"></script>
<script>

function addQualificationRow(){

    let table = document.getElementById("qualificationBody");

    let template = document.getElementById("qualificationTemplate");

    let newRow = template.cloneNode(true);

    newRow.removeAttribute("id");

    newRow.style.display = "";

    table.appendChild(newRow);

}

function removeQualificationRow(button){

    button.closest("tr").remove();

}

window.onload = function(){

    addQualificationRow();

};

</script>

</body>

</html>