<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css" />
    <link rel="stylesheet" href="adminDashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
   
    <style>
        .table th,
        .table td {
            vertical-align: middle;
        }
        .table th{
        background-color: #57853c;
        color: white;
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
         .pastel-green-table thead {
            background-color: #000000;
        }

        .pastel-green-table tbody tr:nth-child(even) {
            background-color: #f5fff0;
        }

        .pastel-green-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .pastel-green-table th,
        .pastel-green-table td {
            text-align: center;
            vertical-align: middle;
        }
        #rfidForm .form-control {
    max-width: 300px; /* Adjust this value to make the input smaller */
    display: inline-block; /* To keep label and input on the same line if space allows */
}
    </style>
</head>

<body>


<div class="d-flex">
    <div class="sidebar d-flex flex-column p-3" style="height: 100vh;">
        <h3 class="text-warning">
            <a ><img src="Logo.png" alt=""><span class="text-warning">ADMIN PORTAL</span></a>
        </h3>

        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item">
                <a class="nav-link active" href="adminDashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="adminAttRecords.php">Tracking</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="attendanceLogs.php">Attendance logs</a>
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

    <div class="main-content flex-grow-1">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="title container-fluid">
                <h3><b>Tracking</b></h3>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div>
                    <p class="mb-0" id="currentDate" class="fs-4 fw-bold"></p>
                </div>
            </div>
        </nav>
        <?php
        // Set timezone to Philippine Time (Ensuring both script and ini setting)
        date_default_timezone_set('Asia/Manila');
        ini_set('date.timezone', 'Asia/Manila');

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

        // Handle archive request
        if (isset($_POST['archive_attendance'])) {
            // Archiving attendance data to attendanceLogs table
            $archive_sql = "INSERT INTO attendanceLogs (studentID, name, date, time_in, time_out, status,hoursRendered)
                                SELECT studentID, name, date, time_in, time_out, status, hoursRendered FROM attendance";

            if ($conn->query($archive_sql)) {
                // If data successfully inserted, delete all records from attendance table
                $delete_sql = "DELETE FROM attendance";
                if ($conn->query($delete_sql)) {
                    $success = "All attendance records archived successfully!";
                } else {
                    $error = "Error deleting records: " . $conn->error;
                }
            } else {
                $error = "Error archiving records: " . $conn->error;
            }
        }

                
       if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rfid_tag'])) {
    $rfid_tag = trim($_POST['rfid_tag']);

    if (empty($rfid_tag)) {
        $error = "Please scan a valid RFID tag.";
    } elseif (!preg_match('/^[a-zA-Z0-9]{8,16}$/', $rfid_tag)) {
        $error = "Invalid RFID tag format.";
    } else {
        $check_sql = "SELECT studentID, firstName, lastName FROM registration WHERE RFIDtag = '$rfid_tag' LIMIT 1";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows === 0) {
            $error = "RFID tag not registered!";
        } else {
            $row_reg = $check_result->fetch_assoc();
            $studentID = $row_reg['studentID'];
            $name = $row_reg['firstName'] . ' ' . $row_reg['lastName'];

            $current_date = date("Y-m-d");
            $attendance_check = "SELECT * FROM attendance WHERE studentID = '$studentID' AND date = '$current_date' LIMIT 1";
            $attendance_result = $conn->query($attendance_check);

            $manila_timezone = new DateTimeZone('Asia/Manila');
            $current_datetime = new DateTime('now', $manila_timezone);
            $current_time = $current_datetime->format('h:i:s A');
            $current_timestamp = $current_datetime->getTimestamp();

            if ($attendance_result->num_rows === 0) {
                // First scan today → Time In only
                $insert_sql = "INSERT INTO attendance (studentID, name, date, time_in, status, time_in_timestamp)
                               VALUES ('$studentID', '$name', '$current_date', '$current_time', 'No Time Out', '$current_timestamp')";
                if ($conn->query($insert_sql)) {
                    $success = "Time In recorded for " . htmlspecialchars($name) . "! Please wait 5 minutes before scanning Time Out.";
                } else {
                    $error = "Error: " . $conn->error;
                }
            } else {
                // Check if 5 minutes have passed
                $attendance_data = $attendance_result->fetch_assoc();
                $time_in_timestamp = $attendance_data['time_in_timestamp'];
                $time_difference = $current_timestamp - $time_in_timestamp;

                if ($time_difference < 300) { // 300 seconds = 5 minutes
                    $error = "Please wait for 5 minutes before scanning Time Out.";
                } else {
                    // Time Out update
                    $update_sql = "UPDATE attendance
                                   SET time_out = '$current_time', status = 'Present'
                                   WHERE studentID = '$studentID' AND date = '$current_date'";
                    if ($conn->query($update_sql)) {
                        $success = "Time Out updated for " . htmlspecialchars($name) . "!";
                    } else {
                        $error = "Error: " . $conn->error;
                    }
                }
            }
        }
    }
}

        ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" id="rfidForm" class="mb-3"><br>
            <div class="mb-3">
                 <label for="rfid_tag" class="form-label"><h3>Scan RFID Tag:</h3></label>
                <input type="text" class="form-control" id="rfid_tag" name="rfid_tag" required autofocus autocomplete="off">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            
        </form>

        <div class="d-flex justify-content-end mb-3">
            <form method="POST" class="archive-form" onsubmit="return confirmArchive();">
                <button type="submit" name="archive_attendance" class="btn btn-warning fw-bold px-4 py-2 shadow" style="font-size: 1.1rem;">
    <i class="fas fa-save me-2"></i>Save Today’s Attendance Records
</button>
            </form>
        </div>


       <table  class="table table-bordered table-striped">
    <thead class="table">
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Status</th>
            <th>Hours Rendered</th> <!-- Added column -->
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch today's attendance
        $current_date = date("Y-m-d");
        $logs_sql = "SELECT a.studentID, a.date, a.time_in, a.time_out, a.status, r.firstName, r.lastName
                     FROM attendance a
                     JOIN registration r ON a.studentID = r.studentID
                     WHERE a.date = '$current_date'
                     ORDER BY a.studentID ASC";
        $logs_result = $conn->query($logs_sql);

        if ($logs_result && $logs_result->num_rows > 0) {
            while ($row = $logs_result->fetch_assoc()) {
                $time_in = htmlspecialchars($row['time_in']);
                $time_out_val = $row['time_out'];
                $time_out = !empty($time_out_val) && $time_out_val !== '00:00:00' ? htmlspecialchars($time_out_val) : '-';
                $name = htmlspecialchars($row['firstName'] . ' ' . $row['lastName']);
                $status = (!empty($time_out_val) && $time_out_val !== '00:00:00') ? 'Present' : 'No Time Out';

                // Calculate hours rendered
                $hours_rendered = 'N/A';
                if (!empty($time_in) && !empty($time_out_val) && $time_out_val !== '00:00:00') {
                    $in = new DateTime($time_in);
                    $out = new DateTime($time_out_val);
                    $interval = $in->diff($out);
                    $hours = $interval->h + ($interval->days * 24);
                    $minutes = $interval->i;
                    $hours_rendered = sprintf('%d:%02d', $hours, $minutes);
                }

                echo "<tr>
                        <td>" . htmlspecialchars($row['studentID']) . "</td>
                        <td>" . $name . "</td>
                        <td>" . htmlspecialchars($row['date']) . "</td>
                        <td>" . $time_in . "</td>
                        <td>" . $time_out . "</td>
                        <td>" . $status . "</td>
                        <td>" . $hours_rendered . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No records found for today.</td></tr>";
        }

        $conn->close();
        ?>
    </tbody>
</table>

    </div>
</div>

<script>
    function updateDate() {
        let today = new Date();
        let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById("currentDate").innerText = today.toLocaleDateString("en-US", options);
    }
    updateDate();

    function confirmArchive() {
        return confirm("Are you sure you want to save today's attendance?\nThis is recommended after school hours.");
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>