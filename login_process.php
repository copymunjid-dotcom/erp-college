<?php

// =============================
// Start Session
// =============================

session_start();

// =============================
// Include Database Connection
// =============================

require "database.php";

// =============================
// Check Request Method
// =============================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $register_number = trim($_POST["register_number"]);
    $password = $_POST["password"];

    // =============================
    // Find User
    // =============================

    $stmt = mysqli_prepare(

        $conn,

        "SELECT
    u.user_id,
    u.student_id,
    u.staff_id,
    u.register_number,
    u.email,
    u.password,
    u.role_id,

    CASE
    WHEN u.role_id = 2 THEN st.staff_name
    WHEN u.role_id = 1 THEN s.student_name
    ELSE ''
END AS full_name

FROM users u

LEFT JOIN students s
ON u.student_id = s.student_id

LEFT JOIN staff st
ON u.staff_id = st.staff_id

WHERE u.register_number = ?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "s",

        $register_number

    );

    mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {

    $user = mysqli_fetch_assoc($result);

   

  
        


        if (password_verify($password, $user["password"])) {

            $_SESSION["student_id"] = $user["student_id"];
            $_SESSION["staff_id"] = $user["staff_id"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["register_number"] = $user["register_number"];
            $_SESSION["role_id"] = $user["role_id"];

            if($user["role_id"] == 1){

    header("Location:dashboard.php");
    exit();

}
elseif($user["role_id"] == 2){

    header("Location: staff_dashboard.php");
    exit();

}
elseif($user["role_id"] == 3){

    header("Location: admin_dashboard.php");
    exit();

}
else{

    echo "<script>
        alert('Invalid Role');
        window.location='login.php';
    </script>";
    exit();

}


        } else {

            echo "<script>
                    alert('Incorrect Password');
                    window.location='login.php';
                  </script>";

        }

    } else {

        echo "<script>
                alert('Register Number not found');
                window.location='login.php';
              </script>";

    }

    mysqli_stmt_close($stmt);

    mysqli_close($conn);


}

?>