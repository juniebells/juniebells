<?php
// Show errors (for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'login';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = isset($_POST['studentID']) ? trim($_POST['studentID']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (empty($username) || empty($email) || empty($studentID)) {
        $error = "Please fill in all fields.";
    } else {
        // Check student ID
        $stmt = $conn->prepare("SELECT * FROM logindb WHERE studentID = ? LIMIT 1");
        $stmt->bind_param("s", $studentID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = "Student ID already registered!";
        } else {
            // Check username
            $stmt = $conn->prepare("SELECT * FROM logindb WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $error = "Username already registered!";
            } else {
                $default_password = password_hash('password1234', PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO logindb (studentID, username, email, password_hash) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $studentID, $username, $email, $default_password);
                if ($stmt->execute()) {
                    $success = "User registered successfully!";
                } else {
                    $error = "Error: " . $conn->error;
                }
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records - Smart Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adminDashboard.css">
    <style>
        .table th, .table td {
            vertical-align: middle;
        }

        .sidebar .nav-link {
            color: white;
        }

        .sidebar .nav-link:hover {
            background-color: #b39f34;
        }

        .main-content {
            padding: 20px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        h3{
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3" style="height: 100vh;">
        <h3 class="text-warning">
            <a href="adminDashboard.php"><img src="Logo.png" alt=""><span class="text-warning">ADMIN PORTAL</span></a>
        </h3>

        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item">
                <a class="nav-link active" href="adminDashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="adminAttRecords.php">Attendance Scanning</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="attendanceLogs.php">Attendance logs</a>
        </li>  
            <li class="nav-item">
                <a class="nav-link" href="registerAccount.php">Register Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="adminStudentlist.php">Student List</a>
            </li>
            
        </ul>

        <div class="mt-auto">
            <a href="allLogin.php" class="nav-link text-warning">
                <i class="lni lni-exit"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="title container-fluid">
                <h2><b>Account Registration</b></h2>
                <div><p class="mb-0 fs-4 fw-bold" id="currentDate"></p></div>
            </div>
        </nav>

        <div class="container mt-4">
            <h3 class="mb-4">Register New User</h3>

            <?php if (!empty($success)): ?>
                <div class="success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="studentID" class="form-label">Student ID</label>
                    <input type="number" class="form-control" id="studentID" name="studentID" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Student Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <button type="submit" class="btn btn-primary">Register User</button>
            </form>
        </div>
    </div>
</div>



</body>
</html>
