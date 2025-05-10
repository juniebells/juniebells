<?php
session_start();
require 'database.php'; 

if (!isset($_SESSION['verified']) || !isset($_SESSION['email'])) {
    die("⛔ Access Denied.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['email'];

    if ($new_password !== $confirm_password) {
        echo "<script>alert('❌ Passwords do not match!');</script>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE logindb SET password_hash = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Password successfully reset.'); window.location.href='allLogin.php';</script>";
            session_destroy();
        } else {
            echo "<script>alert('❌ Failed to reset password.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        *{
            font-family: poppins;
        }
        body {
            background-image: url('BG.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background-color: transparent;
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border-style: solid;
            border-color: black;
        }
        .card h2, label{
            color: white;
        }

        .card button {
            background-color: black;
            color: white;
            border-color: black;
        }
        .card button:hover{
            transition: 0.1;
            background-color: white;
            color: black;
            border-color: white;
        }
    </style>
</head>
<body>

<div class="card">
    <h2 class="text-center mb-4">Reset Your Password</h2>

    <form method="post">
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password:</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label" autocomplete="off">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required autocomplete="off">

        </div>

        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>
</div>

</body>
</html>
