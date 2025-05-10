<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'login'; // your database name

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
        // Check if studentID already exists
        $check_id_sql = "SELECT * FROM logindb WHERE studentID = ? LIMIT 1";
        $stmt = $conn->prepare($check_id_sql);
        $stmt->bind_param("s", $studentID);
        $stmt->execute();
        $check_id_result = $stmt->get_result();

        if ($check_id_result->num_rows > 0) {
            $error = "Student ID already registered!";
        } else {
            // Check if username already exists
            $check_username_sql = "SELECT * FROM logindb WHERE username = ? LIMIT 1";
            $stmt = $conn->prepare($check_username_sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $check_username_result = $stmt->get_result();

            if ($check_username_result->num_rows > 0) {
                $error = "Username already registered!";
            } else {
                $default_password = password_hash('password1234', PASSWORD_DEFAULT);

                $insert_sql = "INSERT INTO logindb (studentID, username, email, password_hash) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
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