<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session completely
session_unset();
session_destroy();

// Prevent browser from loading cached admin pages after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login page
header("Location: loging.php?logged_out=1");
exit();
?>
