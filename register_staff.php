
<?php

require "database.php";

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

    <h1>Staff Registration Form</h1>

    <form action="save_staff.php" method="POST" enctype="multipart/form-data">

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
    required>

    <label>Employee Number</label>

    <input
    type="text"
    name="register_number"
    required>

    <label>Email</label>

    <input
    type="email"
    name="email"
    required>

    <label>Phone Number</label>

    <input
    type="tel"
    name="phone"
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

    <option value="<?php echo $row["gender_id"]; ?>">

        <?php echo htmlspecialchars($row["gender_name"]); ?>

    </option>

    <?php } ?>

</select>
    <label>Date of Birth</label>

    <input
    type="date"
    name="date_of_birth"
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
    required></textarea>

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
    rows="4"></textarea>
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

        <option value="<?php echo $row["department_id"]; ?>">

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

        <option value="<?php echo $row["designation_id"]; ?>">

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
required>

<label>Relationship</label>

<select
name="relationship_type_id"
required>

    <option value="">Select Relationship</option>

    <option value="1"   >Father</option>

    <option value="2">Mother</option>

    <option value="3">Brother</option>

    <option value="4">Sister</option>

    <option value="5">Guardian</option>

    <option value="6">Relative</option>

</select>

<label>Emergency Contact Number</label>

<input
type="tel"
name="emergency_phone"
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
accept=".jpg,.jpeg,.png"
required>

<label>Resume</label>

<input
type="file"
name="resume"
accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
required>

<label>Highest Qualification Certificate</label>

<input
type="file"
name="degree_certificate"
accept=".pdf,.jpg,.jpeg,.png"
required>

<label>ID Proof</label>

<input
type="file"
name="id_proof"
accept=".pdf,.jpg,.jpeg,.png"
required>

<label>Address Proof</label>

<input
type="file"
name="address_proof"
accept=".pdf,.jpg,.jpeg,.png"
required>

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
        PART 3 STARTS HERE
=========================== -->




<!-- ===========================
        ACCOUNT DETAILS
=========================== -->

<h2 class="form-section">

    Account Details

</h2>

<label for="password">

    Password

</label>

<div class="password-box">

    <input
    type="password"
    name="password"
    id="password"
    required>

    <i
    class="fa-solid fa-eye"
    id="togglePassword"></i>

</div>

<label for="confirm_password">

    Confirm Password

</label>

<div class="password-box">

    <input
    type="password"
    name="confirm_password"
    id="confirm_password"
    required>

    <i
    class="fa-solid fa-eye"
    id="toggleConfirmPassword"></i>

</div>

<div class="button-group">

    <button
    type="submit">

        Register Student

    </button>

    <button
    type="reset">

        Reset Form

    </button>

</div>

</form>

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