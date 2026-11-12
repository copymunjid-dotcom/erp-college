<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <title>Student Login</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h1>Student Login</h1>

    <form action="login_process.php" method="POST">

        <label>Register Number</label>

        <input
        type="text"
        name="register_number"
        placeholder="Enter Register Number"
        required>

        <label>Password</label>

<div class="password-box">

    <input
    type="password"
    id="loginPassword"
    name="password"
    placeholder="Enter Password"
    required>

    <i class="fa-solid fa-eye" id="toggleLoginPassword"></i>

</div>

        <button type="submit">

            Login

        </button>

        <p style="text-align:center; margin-top:20px;">

            Don't have an account?

            <a class="register-link" href="register.php" style="color:red; text-decoration:underline;">

                Register Here

            </a>

        </p>

    </form>

</div>
<script src="script.js"></script>


</body>

</html>