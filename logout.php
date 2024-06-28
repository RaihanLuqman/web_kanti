<?php
session_start();
include 'konek_db.php'; 

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: /Kanti/login.php');
exit();
?>
