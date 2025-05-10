<?php
session_start();
require 'database.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: allLogin.php");
    exit();
}

$email = $_SESSION['email'];

// Get student details
$sql = "SELECT CONCAT(firstName, ' ', lastName) AS full_name, age, sex, yearLevel, course, studentID 
        FROM registration WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_name = $row['full_name'];
    $student_id = $row['studentID'];
    $full_name = $row['full_name'];
    $age = $row['age'];
    $sex = $row['sex'];
    $year_level = $row['yearLevel'];
    $course = $row['course'];
} else {
    echo "<p>No student found with this email.</p>";
    exit();
}

$course_level = $year_level . ' ' . $course;

// Get today's attendance from attendance table
$sql_today = "SELECT date, time_in, time_out 
              FROM attendance 
              WHERE studentID = ? AND DATE(date) = CURDATE() 
              ORDER BY date DESC LIMIT 1";
$stmt_today = $conn->prepare($sql_today);
$stmt_today->bind_param("i", $student_id);
$stmt_today->execute();
$result_today = $stmt_today->get_result();

if ($result_today->num_rows > 0) {
    $today_attendance = $result_today->fetch_assoc();
    $today_date = $today_attendance['date'];
    $time_in = $today_attendance['time_in'];
    $time_out = $today_attendance['time_out'];
} else {
    $today_date = $time_in = $time_out = 'No attendance recorded for today.';
}

// Get overall attendance history from attendancelogs table
$sql_history = "SELECT DATE(a.date) AS date, MIN(a.time_in) AS time_in, 
                IFNULL(MAX(a.time_out), 'No Time Out') AS time_out, 
                CONCAT(r.yearLevel, ' ', r.course) AS course_level, 
                r.age AS student_age, r.sex AS student_sex
                FROM attendancelogs a
                JOIN registration r ON a.studentID = r.studentID
                WHERE r.email = ? 
                GROUP BY DATE(a.date) 
                ORDER BY a.date DESC";
$stmt_history = $conn->prepare($sql_history);
$stmt_history->bind_param("s", $email);
$stmt_history->execute();
$result_history = $stmt_history->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SAS - Attendance History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
  <script defer src="script.js"></script>
  <style>
    * { font-family: 'Poppins', sans-serif; }
    body { min-height: 100vh; }
    .sidebar {
      height: 100vh;
      position: fixed;
      top: 56px;
      left: 0;
      width: 200px;
      background-color: #4F7942;
      padding-top: 1rem;
    }
    .logo { height: 3.5rem; width: 3.5rem; }
    .sidebar .nav-link { color: gold; }
    .sidebar .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
    }
    .navbar { background-color: #4F7942; }
    .main-content th { background-color: green; color: white; }
    .content { margin-left: 200px; padding: 1.5rem; padding-top: 80px; }
    .navbar-brand { font-weight: bold; }
    .name {
      background-color: #27391C;
      color: white;
      padding: 10px;
      border-radius: 10px;
    }
    h4 {
      background-color: #27391C;
      border-radius: 10px;
      color: white;
      padding: 10px;
    }
    .table {
      border-radius: 10px;
      overflow: hidden;
    }
    .table thead th { border-bottom: 2px solid #dee2e6; }
    .table-hover tbody tr:hover {
      background-color: #f8f9fa;
      transition: 0.2s;
    }
    .table td, .table th { vertical-align: middle; }
  </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container-fluid">
    <a href="#"><img class="logo" src="Logo.png" alt="Logo"></a>
    <a class="navbar-brand text-warning" href="#"><h3>SAS - Student Portal</h3></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
            <?php echo htmlspecialchars($student_name); ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item text-danger" href="allLogin.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Sidebar -->
<div class="sidebar border-end">
  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link text-light fs-6" href="studentDashboard.php">Dashboard</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-light fs-6" href="studentProfile.php">Profile</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-light fs-6" href="studentHistory.php">Attendance History</a>
    </li>
  </ul>
</div>

<!-- Main Content -->
<div class="main-content" style="margin-left: 220px; padding: 20px;">
  <div class="container mt-3">
    <h2 class="mb-4">📋 Attendance Dashboard</h2>

    <!-- Today's Attendance -->
    <h4 class="mb-3">📅 Today's Attendance</h4>
    <div class="table-responsive">
      <table class="table table-hover table-borderless shadow-sm rounded bg-white">
        <thead class="table-success text-dark">
          <tr>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Course Level</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo is_numeric(strtotime($today_date)) ? date("F j, Y", strtotime($today_date)) : $today_date; ?></td>
            <td><?php echo htmlspecialchars($time_in); ?></td>
            <td><?php echo htmlspecialchars($time_out); ?></td>
            <td><?php echo htmlspecialchars($course_level); ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Overall Attendance -->
    <h4 class="mt-5 mb-3">📊 Overall Attendance</h4>
    <div class="table-responsive">
      <table id="example" class="table table-hover table-borderless shadow-sm rounded bg-white">
        <thead class="table-primary text-dark">
          <tr>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Course Level</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result_history->num_rows > 0): ?>
            <?php while ($row = $result_history->fetch_assoc()): ?>
              <tr>
                <td><?php echo date("F j, Y", strtotime($row['date'])); ?></td>
                <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                <td><?php echo htmlspecialchars($row['time_out']); ?></td>
                <td><?php echo htmlspecialchars($row['course_level']); ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center text-danger">No attendance history available.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- DataTables Init -->
<script>
  $(document).ready(function() {
    // Initialize DataTables
    $('#example').DataTable({
      "paging": true,
      "searching": true,
      "info": true,
    });
  });
</script>
</body>
</html>
