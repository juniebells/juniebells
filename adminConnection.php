<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'login');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$StudentID = $_POST['StudentID'];
$RFIDtag = $_POST['RFIDtag'];
$FirstName = $_POST['FirstName'];
$LastName = $_POST['LastName'];
$Email = $_POST['Email'];
$Age = $_POST['Age'];
$Sex = $_POST['Sex'];
$Course = $_POST['Course'];
$YearLevel = $_POST['YearLevel'];

// Validate POST fields
$requiredFields = ['StudentID','RFIDtag','FirstName', 'LastName', 'Email', 'Age','Sex', 'Course', 'YearLevel'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        die("Missing or empty field: $field");
    }
}

// Sanitize inputs to avoid SQL injection
$StudentID= $conn->real_escape_string($StudentID);
$RFIDtag = $conn->real_escape_string($RFIDtag);
$FirstName = $conn->real_escape_string($FirstName);
$LastName = $conn->real_escape_string($LastName);
$Email = $conn->real_escape_string($Email);
$Age = $conn->real_escape_string($Age);
$Sex = $conn->real_escape_string($Sex);
$Course = $conn->real_escape_string($Course);
$YearLevel = $conn->real_escape_string($YearLevel);

// Prepare the SQL query
$sql = "INSERT INTO registration (studentID,RFIDtag,firstName, lastName, email, age,sex,  course, yearLevel)
VALUES ('$StudentID','$RFIDtag', '$FirstName', '$LastName', '$Email', '$Age', '$Sex', '$Course', '$YearLevel')";

// Execute the query and check for success
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('New record created successfully'); window.location.href='adminStudentlist.php';</script>";
} else {
    echo "<script>alert('Error: " . $conn->error . "'); window.location.href='adminStudentlist.php';</script>";
}

// Close the connection
$conn->close();
?> 
