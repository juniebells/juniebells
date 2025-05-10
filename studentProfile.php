<?php
session_start();
require 'database.php'; 

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: allLogin.php");
    exit();
}

$email = $_SESSION['email'];

// Query to get student details (concatenate firstName and lastName)
$sql = "SELECT CONCAT(firstName, ' ', lastName) AS full_name, age, sex, yearLevel, course FROM registration WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_name = $row['full_name'];  // used in navbar
    $full_name = $row['full_name'];     // used in profile
    $age = $row['age'];
    $sex = $row['sex'];
    $year_level = $row['yearLevel'];
    $course = $row['course'];
} else {
    // Default/fallback values to prevent undefined variable warnings
    $student_name = 'Student Name';
    $full_name = 'Student Name';
    $age = '-';
    $sex = '-';
    $year_level = '-';
    $course = '-';
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SAS-Student Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
     @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    *{
      font-family: poppins;
    }
    body {
      min-height: 100vh;
    }
    .sidebar {
      height: 100vh;
      position: fixed;
      top: 56px;
      left: 0;
      width: 200px;
      background-color: #4F7942;
      padding-top: 1rem;
    }
    .logo{
      height:3.5rem;
      width:3.5rem;
    }
    .sidebar .nav-link {
      color: gold;
    }
    .sidebar .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
    }
    .content {
      margin-left: 200px;
      padding: 1.5rem;
      padding-top: 80px;
    }
    .navbar{
      background-color: #4F7942;
    }
    .navbar-brand {
      font-weight: bold;
    }
    .name {
    background-color: #27391C; 
    color:white;
    padding: 10px;
    border-radius: 10px; 
    }
    .clock {
      font-size: 1rem;
      font-weight: 500;
      color: white;
      text-align: right;
    }
    .profile-image {
      width: 200px;
      height: 200px;
      object-fit: cover;
      border-radius: 8px;
      border: 3px solid #198754;
      margin-bottom: 1rem;
    }
    .details-label {
      font-weight: 600;
      color: #198754;
    }
    .details-row {
      margin-bottom: 0.5rem;
    }
    .card-header, thead {
      background-color: #1F7D53;
      color: white;
      padding: 10px;
    }
    
  </style>
</head>
<body>
 <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container-fluid">
    <a href=""><img class="logo" src="Logo.png" alt="Logo"></a>
    <a class="navbar-brand text-warning" href="#"><h3>SAS-Student Portal</h3></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <!-- Dropdown for username -->
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
  <div class="sidebar border-end ">
    <ul class="nav flex-column ">
      <li class="nav-item ">
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
  <div class="content">
    <div class="name d-flex justify-content-between align-items-center mb-2">
      <h2>Profile</h2>
      <p class="clock mb-0"><span id="datetime"></span></p>
    </div>

    

    <div class="card mt-3">
  <div class="card-header">👤 Personal Details</div>
  <div class="card-body">
    <div class="row details-row">
      <div class="col-sm-4 details-label">Full Name:</div>
      <div class="col-sm-8"><?php echo htmlspecialchars($full_name); ?></div>
    </div>
    <div class="row details-row">
      <div class="col-sm-4 details-label">Age:</div>
      <div class="col-sm-8"><?php echo htmlspecialchars($age); ?></div>
    </div>
    <div class="row details-row">
      <div class="col-sm-4 details-label">Sex:</div>
      <div class="col-sm-8"><?php echo htmlspecialchars($sex); ?></div>
    </div>
    <div class="row details-row">
      <div class="col-sm-4 details-label">Year Level:</div>
      <div class="col-sm-8"><?php echo htmlspecialchars($year_level); ?></div>
    </div>
    <div class="row details-row">
      <div class="col-sm-4 details-label">Course:</div>
      <div class="col-sm-8"><?php echo htmlspecialchars($course); ?></div>
    </div>
  </div>
</div>


  <!-- Real-time Clock -->
  <script>
    function updateDateTime() {
      const now = new Date();
      const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
       
      };
      document.getElementById('datetime').textContent = now.toLocaleString('en-US', options);
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
