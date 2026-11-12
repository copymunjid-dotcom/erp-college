<?php

session_start();

if(!isset($_SESSION["role_id"])){

    header("Location: login.php");
    exit();

}

require "database.php";

/*
Role 3 = Admin
Role 2 = Staff
*/

if($_SESSION["role_id"] == 3){

    // Admin can view any staff profile
    if(!isset($_GET["id"])){

        header("Location: manage_staff.php");
        exit();

    }

    $staff_id = intval($_GET["id"]);

}
else if($_SESSION["role_id"] == 2){

    // Staff can only view their own profile
    $staff_id = $_SESSION["staff_id"];

}
else{

    header("Location: login.php");
    exit();

}


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

<title>My Profile</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="profile-container">

    <!-- Header Card -->
    <div class="profile-header">

    <div class="profile-photo">

        <?php if(!empty($staff["photo"])) { ?>

            <img
            src="<?php echo htmlspecialchars($staff["photo"]); ?>"
            alt="Staff Photo">

        <?php } else { ?>

            <img
            src="images/default-user.png"
            alt="Default Photo">

        <?php } ?>

    </div>

    <div class="profile-info">

        <h2>

            <?php echo htmlspecialchars($staff["staff_name"]); ?>

        </h2>

        <p class="designation">

            <?php echo htmlspecialchars($staff["designation_name"]); ?>

        </p>

        <p class="department">

            <?php echo htmlspecialchars($staff["department_name"]); ?>

        </p>

        <span class="register-number">

            Register No :
            <?php echo htmlspecialchars($staff["register_number"]); ?>

        </span>

    </div>

</div>
<div class="profile-tabs">

    <button class="tab-btn active"
        onclick="openTab(event,'personal')">
        Personal
    </button>

    <button class="tab-btn"
        onclick="openTab(event,'address')">
        Address
    </button>

    <button class="tab-btn"
        onclick="openTab(event,'emergency')">
        Emergency
    </button>

    <button class="tab-btn"
        onclick="openTab(event,'qualification')">
        Qualifications
    </button>

    <button class="tab-btn"
        onclick="openTab(event,'documents')">
        Documents
    </button>
    <button
class="tab-btn"
onclick="openTab(event,'subjects')">

Subjects

</button>

</div>


    <!-- Personal Information -->
     <div id="personal" class="tab-content">
    <div class="profile-card">

    <h3 class="card-title">

        Personal Information

    </h3>

    <div class="info-grid">

        <div class="info-item">

            <label>Register Number</label>

            <span>

                <?php echo htmlspecialchars($staff["register_number"]); ?>

            </span>

        </div>

        <div class="info-item">

            <label>Email</label>

            <span>

                <?php echo htmlspecialchars($staff["email"]); ?>

            </span>

        </div>

        <div class="info-item">

            <label>Phone Number</label>

            <span>

                <?php echo htmlspecialchars($staff["phone"]); ?>

            </span>

        </div>

        <div class="info-item">

            <label>Gender</label>

            <span>

                <?php echo htmlspecialchars($staff["gender_name"]); ?>

            </span>

        </div>

        <div class="info-item">

            <label>Date of Birth</label>

            <span>

                <?php echo htmlspecialchars($staff["date_of_birth"]); ?>

            </span>

        </div>

    </div>

</div>
</div>
<div id="address" class="tab-content">

    <div class="profile-card">

        <h3 class="card-title">

            Address Information

        </h3>

        <div class="info-grid">

            <div class="info-item">

                <label>Permanent Address</label>

                <span>

                    <?php echo htmlspecialchars($address["permanent_address"] ?? "Not Available"); ?>

                </span>

            </div>

            <div class="info-item">

                <label>Temporary Address</label>

                <span>

                    <?php echo nl2br(htmlspecialchars($address["temporary_address"])); ?>

                </span>

            </div>

        </div>

    </div>

</div>
   
<div id="emergency" class="tab-content">

    <div class="profile-card">

        <h3 class="card-title">

            Emergency Contact

        </h3>

        <div class="info-grid">

            <div class="info-item">

                <label>Contact Name</label>

                <span>

                    <?php echo htmlspecialchars($emergency["emergency_name"]); ?>

                </span>

            </div>

            <div class="info-item">

                <label>Relationship</label>

                <span>

                    <?php echo htmlspecialchars($emergency["relationship_name"]); ?>

                </span>

            </div>

            <div class="info-item">

                <label>Phone Number</label>

                <span>

                    <?php echo htmlspecialchars($emergency["emergency_phone"]); ?>

                </span>

            </div>

        </div>

    </div>

</div>
<div id="qualification" class="tab-content">

    <div class="profile-card">

        <h3 class="card-title">

            Educational Qualifications

        </h3>

        <table class="qualification-table">

            <thead>

                <tr>

                    <th>Qualification</th>

                    <th>Specialization</th>

                    <th>Institution</th>

                    <th>University</th>

                    <th>Percentage</th>

                    <th>Year</th>

                </tr>

            </thead>

            <tbody>

<?php

if(mysqli_num_rows($qualification_result)>0){

while($row=mysqli_fetch_assoc($qualification_result)){

?>

<tr>

<td><?php echo htmlspecialchars($row["qualification_name"]); ?></td>

<td><?php echo htmlspecialchars($row["specialization"]); ?></td>

<td><?php echo htmlspecialchars($row["institution"]); ?></td>

<td><?php echo htmlspecialchars($row["university_name"]); ?></td>

<td>

<span class="percentage-badge">

<?php echo htmlspecialchars($row["percentage"]); ?>%

</span>

</td>

<td><?php echo htmlspecialchars($row["year_of_passing"]); ?></td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="6" style="padding:30px;text-align:center;">

No qualifications found.

</td>

</tr>

<?php } ?>

</tbody>    

        </table>

    </div>

</div>

<div id="documents" class="tab-content">

    <div class="profile-card">

        <h3 class="card-title">

            Documents

        </h3>

        <div class="document-grid">
            

<?php

$icons = [

    "Photo" => "📷",
    "Resume" => "📄",
    "ID Proof" => "🪪",
    "Highest Qualification Certificate" => "🎓",
    "Address Proof" => "🏠",
    "Experience Certificate" => "📜",
    "Joining Letter" => "📑"

];

while($row = mysqli_fetch_assoc($documents_result)){

?>

<div class="document-card">

    <div class="document-name">

        <?php

        echo $icons[$row["document_name"]] ?? "📄";

        ?>

        <?php echo htmlspecialchars($row["document_name"]); ?>

    </div>

    <a

    href="<?php echo htmlspecialchars($row["file_path"]); ?>"

    target="_blank"

    class="view-btn">

        View

    </a>

</div>


<?php

}

?>

</div>

    </div>
</div>
    
<div id="subjects" class="tab-content">

<div class="profile-card">

<h3 class="card-title">

Assigned Subjects

</h3>

<table class="qualification-table">

<thead>

<tr>

<th>Code</th>

<th>Subject</th>

<th>Class</th>

<th>Course</th>

<th>Year</th>

</tr>

</thead>

<tbody>

<?php

$stmt = mysqli_prepare(

$conn,

"SELECT

sub.subject_code,
sub.subject_name,
c.class_name,
d.department_name,
y.year_name

FROM staff_subjects ss

INNER JOIN subjects sub
ON ss.subject_id = sub.subject_id

INNER JOIN classes c
ON ss.class_id = c.class_id

INNER JOIN departments d
ON c.department_id = d.department_id

INNER JOIN years y
ON c.year_id = y.year_id

WHERE ss.staff_id = ?

ORDER BY
y.year_id,
sub.subject_name"

);

mysqli_stmt_bind_param(

$stmt,

"i",

$staff_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td><?php echo htmlspecialchars($row["subject_code"]); ?></td>

<td><?php echo htmlspecialchars($row["subject_name"]); ?></td>

<td><?php echo htmlspecialchars($row["class_name"]); ?></td>

<td><?php echo htmlspecialchars($row["department_name"]); ?></td>

<td><?php echo htmlspecialchars($row["year_name"]); ?></td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="5" style="text-align:center;padding:25px;">

No subjects assigned.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>
<script>

function openTab(evt, tabName){

    const contents =
    document.getElementsByClassName("tab-content");

    for(let i=0;i<contents.length;i++){

        contents[i].style.display="none";

    }

    const buttons =
    document.getElementsByClassName("tab-btn");

    for(let i=0;i<buttons.length;i++){

        buttons[i].classList.remove("active");

    }

    document.getElementById(tabName).style.display="block";

    evt.currentTarget.classList.add("active");

}

document.getElementById("personal").style.display="block";

</script>
</body>

</html>