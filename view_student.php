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

    "SELECT

        s.*,
        g.gender_name,
        d.department_name,
        y.year_name,
        p.program_level_name

    FROM students s

    LEFT JOIN genders g
    ON s.gender_id = g.gender_id

    LEFT JOIN departments d
    ON s.department_id = d.department_id

    LEFT JOIN years y
    ON s.year_id = y.year_id

    LEFT JOIN program_levels p
    ON s.program_level_id = p.program_level_id

    WHERE s.student_id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $student_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$student = mysqli_fetch_assoc($result);     
// =============================
// Fetch Address
// =============================

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

$result = mysqli_stmt_get_result($stmt);

$address = mysqli_fetch_assoc($result);
// =============================
// Fetch Emergency Contact
// =============================

$stmt = mysqli_prepare(

    $conn,

    "SELECT
        ec.*,
        rt.relationship_name

     FROM emergency_contacts ec

     LEFT JOIN relationship_types rt
     ON ec.relationship_type_id = rt.relationship_type_id

     WHERE ec.student_id=?"

);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $student_id
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

        ad.*,
        q.qualification_name,
        b.board_name

     FROM academic_details ad

     LEFT JOIN qualifications q
     ON ad.qualification_id=q.qualification_id

     LEFT JOIN boards b
     ON ad.board_id=b.board_id

     WHERE ad.student_id=?"

);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $student_id
);

mysqli_stmt_execute($stmt);

$academic_result = mysqli_stmt_get_result($stmt);
// =============================
// Fetch Documents
// =============================

$stmt = mysqli_prepare(

    $conn,

    "SELECT
        sd.*,
        dt.document_name

     FROM student_documents sd

     LEFT JOIN document_types dt
     ON sd.document_type_id = dt.document_type_id

     WHERE sd.student_id=?

     ORDER BY dt.document_name"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $student_id

);

mysqli_stmt_execute($stmt);

$documents_result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Student Profile

</title>

<link
rel="stylesheet"
href="style.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="profile-container">
    <div class="profile-header">

    <div class="profile-photo">

        <?php

        $photo = "images/default-user.png";

        while($doc = mysqli_fetch_assoc($documents_result)){

            if($doc["document_type_id"] == 1){

                $photo = "uploads/photos/" . $doc["file_path"];

            }

        }

        ?>

        <img

        src="<?php echo htmlspecialchars($photo); ?>"

        alt="Student Photo">

    </div>

    <div class="profile-details">

        <h2>

            <?php echo htmlspecialchars($student["student_name"]); ?>

        </h2>

        <p>

            <?php echo htmlspecialchars($student["register_number"]); ?>

        </p>

        <p>

            <?php echo htmlspecialchars($student["department_name"]); ?>

        </p>

        <p>

            <?php echo htmlspecialchars($student["year_name"]); ?>

        </p>

    </div>

</div>
<div class="profile-tabs">

<button
class="tab-btn active"
onclick="showTab(event,'personal')">

Personal

</button>

<button
class="tab-btn"
onclick="showTab(event,'address')">

Address

</button>

<button
class="tab-btn"
onclick="showTab(event,'emergency')">

Emergency

</button>

<button
class="tab-btn"
onclick="showTab(event,'qualification')">

Qualifications

</button>

<button
class="tab-btn"
onclick="showTab(event,'documents')">

Documents

</button>

</div>
<div
id="personal"
class="tab-content active">

<div class="profile-card">

<h3 class="card-title">

Personal Information

</h3>

<div class="info-grid">

<div class="info-item">

<label>

Student Name

</label>

<span>

<?php echo htmlspecialchars($student["student_name"]); ?>

</span>

</div>

<div class="info-item">

<label>

Register Number

</label>

<span>

<?php echo htmlspecialchars($student["register_number"]); ?>

</span>

</div>

<div class="info-item">

<label>

Email

</label>

<span>

<?php echo htmlspecialchars($student["email"]); ?>

</span>

</div>

<div class="info-item">

<label>

Phone Number

</label>

<span>

<?php echo htmlspecialchars($student["phone"]); ?>

</span>

</div>

<div class="info-item">

<label>

Gender

</label>

<span>

<?php echo htmlspecialchars($student["gender_name"]); ?>

</span>

</div>

<div class="info-item">

<label>

Date of Birth

</label>

<span>

<?php echo htmlspecialchars($student["date_of_birth"]); ?>

</span>

</div>

<div class="info-item">

<label>

Department

</label>

<span>

<?php echo htmlspecialchars($student["department_name"]); ?>

</span>

</div>

<div class="info-item">

<label>

Program Level

</label>

<span>

<?php echo htmlspecialchars($student["program_level_name"]); ?>

</span>

</div>

<div class="info-item">

<label>

Year

</label>

<span>

<?php echo htmlspecialchars($student["year_name"]); ?>

</span>

</div>

</div>

</div>

</div>
<div
id="address"
class="tab-content">

<div class="profile-card">

<h3 class="card-title">

Address Information

</h3>

<div class="info-grid">

<div class="info-item">

<label>

Permanent Address

</label>

<span>

<?php echo htmlspecialchars($address["permanent_address"]); ?>

</span>

</div>

<div class="info-item">

<label>

Temporary Address

</label>

<span>

<?php echo htmlspecialchars($address["temporary_address"]); ?>

</span>

</div>

</div>

</div>

</div>
<div
id="emergency"
class="tab-content">

<div class="profile-card">

<h3 class="card-title">

Emergency Contact

</h3>

<div class="info-grid">

<div class="info-item">

<label>

Emergency Contact Name

</label>

<span>

<?php echo htmlspecialchars($emergency["emergency_name"] ?? "Not Available"); ?>

</span>

</div>

<div class="info-item">

<label>

Relationship

</label>

<span>

<?php echo htmlspecialchars($emergency["relationship_name"] ?? "Not Available"); ?>

</span>

</div>

<div class="info-item">

<label>

Phone Number

</label>

<span>

<?php echo htmlspecialchars($emergency["emergency_phone"] ?? "Not Available"); ?>

</span>

</div>

</div>

</div>

</div>
<div
id="qualification"
class="tab-content">

<div class="profile-card">

<h3 class="card-title">

Educational Qualifications

</h3>

<table class="qualification-table">

<thead>

<tr>

<th>Qualification</th>

<th>School</th>

<th>Board</th>

<th>Percentage</th>

</tr>

</thead>

<tbody>

<?php

while($row = mysqli_fetch_assoc($academic_result)){

?>

<tr>

<td>

<?php echo htmlspecialchars($row["qualification_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["school_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["board_name"]); ?>

</td>

<td>

<span class="percentage-badge">

<?php echo htmlspecialchars($row["percentage"]); ?>%

</span>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>
<div
id="documents"
class="tab-content">

<div class="profile-card">

<h3 class="card-title">

Documents

</h3>

<div class="document-grid">

<?php

mysqli_data_seek($documents_result,0);

while($doc=mysqli_fetch_assoc($documents_result)){

?>

<div class="document-card">

<div class="document-info">

<h4>

<?php

$icon="📄";

switch($doc["document_type_id"]){

    case 1:
        $icon="📷";
        break;

    case 2:
    case 3:
        $icon="🎓";
        break;

    case 4:
        $icon="🪪";
        break;

    case 5:
        $icon="📜";
        break;

    default:
        $icon="📄";

}

echo $icon." ".htmlspecialchars($doc["document_name"]);

?>

</h4>

</div>

<?php

$folder = "";

switch($doc["document_type_id"]){

    case 1:
        $folder = "uploads/photos/";
        break;

    case 2:
        $folder = "uploads/tenth_marksheets/";
        break;

    case 3:
        $folder = "uploads/twelfth_marksheets/";
        break;

    case 4:
        $folder = "uploads/aadhar_cards/";
        break;

    case 5:
        $folder = "uploads/transfer_certificates/";
        break;

    case 6:
        $folder = "uploads/community_certificate/";
        break;

}

?>

<a
href="<?php echo $folder . rawurlencode($doc["file_path"]); ?>"
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
<script>

function showTab(event, tabId){

    document.querySelectorAll(".tab-content").forEach(function(tab){
        tab.style.display = "none";
    });

    document.querySelectorAll(".tab-btn").forEach(function(button){
        button.classList.remove("active");
    });

    document.getElementById(tabId).style.display = "block";

    event.currentTarget.classList.add("active");

}

document.getElementById("personal").style.display = "block";

document
.getElementById("personal")
.style.display="block";

</script>
</body>
</html>