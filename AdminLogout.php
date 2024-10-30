<?php
    session_start(); // Start the session

    // Clear all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the new page
    header("Location: AdminLogin.html");

    // Stop further execution of the script
    exit();
?>