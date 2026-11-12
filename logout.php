<?php

// ============================
// Start Session
// ============================

session_start();

// ============================
// Remove All Session Data
// ============================

session_unset();

// ============================
// Destroy Session
// ============================

session_destroy();

// ============================
// Redirect to Login Page
// ============================

header("Location: login.php");

exit();

?>