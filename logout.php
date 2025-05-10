<?php 
session_start();

// Destroy session variables and session itself
session_unset();
session_destroy();

// Clear browser cache to prevent going back to previous pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.0
header("Pragma: no-cache"); // For HTTP/1.0 compatibility

// Redirect to the login page
header("Location: allLogin.php");
exit();
?>
