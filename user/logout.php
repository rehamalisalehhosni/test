<?php
session_start(); // Start a session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: login.php"); // Redirect the user to the login page
exit();
?>
