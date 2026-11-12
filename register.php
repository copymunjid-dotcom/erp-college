
<?php

require "database.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="container">

    <h1>Student Registration Form</h1>

    <form action="save_student.php" method="POST" enctype="multipart/form-data">

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
    required>

    <label>Register Number</label>

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

    <div class="gender">

        <label>

            <input
            type="radio"
            name="gender"
            value="Male"
            required>

            Male

        </label>

        <label>

            <input
            type="radio"
            name="gender"
            value="Female">

            Female

        </label>

        <label>

            <input
            type="radio"
            name="gender"
            value="Others">

            Others

        </label>

    </div>

    <label>Date of Birth</label>

    <input
    type="date"
    name="date_of_birth"
    required>
    <label>Program Level <span style="color:red;">*</span></label>

<select name="program_level" required>

    <option value="">Select Program Level</option>

    <?php

    $result = mysqli_query($conn, "SELECT * FROM program_levels");

    while($row = mysqli_fetch_assoc($result)){

    ?>

        <option value="<?php echo $row["program_level_id"]; ?>">

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

        <option value="<?php echo $row["department_id"]; ?>">

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
<option value="1">1st Year</option>
<option value="2">2nd Year</option>
<option value="3">3rd Year</option>
<option value="4">4th Year</option>

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
required>

<label>Father's Occupation</label>

<input
type="text"
name="father_occupation"
required>

<label>Father's Phone Number</label>

<input
type="tel"
name="father_phone"
pattern="[0-9]{10}"
required>

<label>Mother's Name</label>

<input
type="text"
name="mother_name"
required>

<label>Mother's Occupation</label>

<input
type="text"
name="mother_occupation"
required>

<label>Mother's Phone Number</label>

<input
type="tel"
name="mother_phone"
pattern="[0-9]{10}"
required>

<label class="optional">Guardian Name (Optional)</label>

<input
type="text"
name="guardian_name">

<label class="optional">Guardian Phone Number (Optional)</label>

<input
type="tel"
name="guardian_phone"
pattern="[0-9]{10}">

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

                    <th>Relationship</th>

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

                        <select name="sibling_gender_id[]">

                            <option value="">Select</option>

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

                    </td>

                    <td>

                        <select name="relationship_type_id[]">

                            <option value="">Select</option>

                            <?php

                            $result = mysqli_query($conn,
                            "SELECT * FROM relationship_types ORDER BY relationship_name");

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
required>

<label>10th School Name</label>

<input
type="text"
name="tenth_school"
required>

<label>12th Percentage</label>

<input
type="number"
name="twelfth_marks"
step="0.01"
min="0"
max="100"
required>

<label>12th School Name</label>

<input
type="text"
name="twelfth_school"
required>

<label>Board of Education</label>

<select
name="board_id"
required>

    <option value="">Select Board</option>

    <option value="1">State Board</option>

    <option value="2">CBSE</option>

    <option value="3">ICSE</option>

    <option value="4">IB</option>

    <option value="5">IGCSE</option>

    <option value="6">NIOS</option>

</select>

<!-- ===========================
        DOCUMENTS
=========================== -->

<h2 class="form-section">

    Documents

</h2>

<label>Upload 10th Marksheet</label>

<input
type="file"
name="tenth_marksheet"
accept=".pdf,.jpg,.jpeg,.png"
required>

<label>Upload 12th Marksheet</label>

<input
type="file"
name="twelfth_marksheet"
accept=".pdf,.jpg,.jpeg,.png"
required>

<label>Passport Size Photo</label>

<input
type="file"
name="photo"
accept=".jpg,.jpeg,.png"
required>

<label>Upload Aadhaar Card</label>

<input
type="file"
name="aadhar_card"
accept=".pdf,.jpg,.jpeg,.png"
required>

<label>Upload Transfer Certificate</label>

<input
type="file"
name="transfer_certificate"
accept=".pdf,.jpg,.jpeg,.png"
required>

<label class="optional">Upload Community Certificate (Optional)</label>

<input
type="file"
name="community_certificate"
accept=".pdf,.jpg,.jpeg,.png">

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

</body>

</html>