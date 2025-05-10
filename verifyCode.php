<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_code = $_POST['user_code'];

    if (isset($_SESSION['verification_code'])) {
        if ($user_code == $_SESSION['verification_code']) {
            $_SESSION['verified'] = true; 
            header("Location: resetPassword.php");
            exit();
        } else {
            echo "❌ Incorrect verification code.";
        }
    } else {
        echo "⚠️ Verification code not found. Please request a new one.";
    }
}
?>
