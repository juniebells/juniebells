<?php
// Connect to your database
$servername = "localhost";
$username = "root";        // adjust as needed
$password = "";            // adjust as needed
$dbname = "login"; // replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if file was uploaded
if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['csvFile']['tmp_name'];

    // Open the file for reading
    if (($handle = fopen($fileTmpPath, 'r')) !== false) {
        // Skip the header row
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            // Expected format: RFIDtag, FirstName, LastName, Email, Age, Course, YearLevel
            if (count($data) == 7) {
                $rfid = $conn->real_escape_string(trim($data[0]));
                $firstName = $conn->real_escape_string(trim($data[1]));
                $lastName = $conn->real_escape_string(trim($data[2]));
                $email = $conn->real_escape_string(trim($data[3]));
                $age = (int) trim($data[4]);
                $course = $conn->real_escape_string(trim($data[5]));
                $yearLevel = $conn->real_escape_string(trim($data[6]));

                // Insert into your student registration table
                $sql = "INSERT INTO registration (RFIDtag, FirstName, LastName, Email, Age, Course, YearLevel)
                        VALUES ('$rfid', '$firstName', '$lastName', '$email', $age, '$course', '$yearLevel')";

                $conn->query($sql); // You may want to check for errors here too
            }
        }

        "<script>
                alert('Import successful!');
                window.location.href = 'adminStudentlist.php';
              </script>";
        exit();
    } else {
        echo "Failed to open the uploaded file.";
    }
} else {
    echo "No file uploaded or there was an upload error.";
}



$conn->close();
?>
