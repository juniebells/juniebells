<?php
session_start();
require 'database.php'; 

if (!isset($_SESSION['email'])) {
    header("Location: allLogin.php");
    exit();
}

$email = $_SESSION['email'];

$sql = "SELECT studentID, CONCAT(firstName, ' ', lastName) AS full_name, age, sex, yearLevel, course FROM registration WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$studentID = '';
$student_name = $full_name = $age = $sex = $yearLevel = $course = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentID = $row['studentID'];
    $student_name = $row['full_name'];
    $full_name = $row['full_name'];
    $age = $row['age'];
    $sex = $row['sex'];
    $yearLevel = $row['yearLevel'];
    $course = $row['course'];
}

// Monthly attendance data
$sql = "SELECT DATE(date) AS log_date, COUNT(*) AS present_count 
        FROM attendanceLogs 
        WHERE studentID = ? AND status = 'Present' 
        AND MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE()) 
        GROUP BY log_date ORDER BY log_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$presentData = [];
while ($row = $result->fetch_assoc()) {
    $presentData[] = [
        'date' => $row['log_date'],
        'count' => $row['present_count']
    ];
}

// Weekly data: Mon to Sat
$weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$weekdayData = array_fill_keys($weekdays, 0);

$sql = "SELECT DAYNAME(date) AS day_name, COUNT(*) AS count 
        FROM attendanceLogs 
        WHERE studentID = ? AND status = 'Present' 
        AND WEEK(date) = WEEK(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE()) 
        GROUP BY day_name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if (isset($weekdayData[$row['day_name']])) {
        $weekdayData[$row['day_name']] = (int)$row['count'];
    }
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SAS-Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
    * { font-family: Poppins, sans-serif; }
    body { min-height: 100vh; }
    .logo { height: 3.5rem; width: 3.5rem; }
    .sidebar {
      height: 100vh; position: fixed; top: 56px; left: 0; width: 200px;
      background-color: #4F7942; padding-top: 1rem;
    }
    .sidebar .nav-link { color: gold; }
    .sidebar .nav-link:hover { background-color: #1F7D53; color: white; }
    .content { margin-left: 200px; padding: 1.5rem; padding-top: 80px; }
    .navbar { background-color: #4F7942; }
    .nav-link { color: white; }
    .navbar-brand { font-weight: bold; }
    .clock {
      font-size: 1.3rem; font-weight: 500; color: white;
      justify-content: flex-end; align-items: center;
    }
    .student-info h5 { font-size: 1.15rem; }
    .student-id { color: #ff4500; }
    .name {
      background-color: #27391C; color: white;
      padding: 10px; border-radius: 10px;
    }
    .student-info { padding-bottom: 50px; }
    .card-header, thead {
      background-color: #1F7D53; color: white; padding: 10px;
    }
    .card-body { padding: 0.75rem; }
    .card-header { font-size: 0.95rem; padding: 0.5rem 1rem; }
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
    <li class="nav-item"><a class="nav-link text-light fs-6" href="studentDashboard.php">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link text-light fs-6" href="studentProfile.php">Profile</a></li>
    <li class="nav-item"><a class="nav-link text-light fs-6" href="studentHistory.php">Attendance History</a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="content">
  <div class="name d-flex justify-content-between align-items-center mb-2">
    <h2 class="mb-0">Dashboard</h2>
    <p class="clock mb-0"><span id="datetime"></span></p>
  </div>

  <!-- Monthly Line Chart -->
  <div class="card mt-4">
    <div class="card-header">Monthly Attendance Overview</div>
    <div class="card-body">
      <canvas id="presentChart" style="height: 60px; max-height: 200px;"></canvas>
    </div>
  </div>

  <!-- Weekly Bar Chart -->
  <div class="card mt-4">
    <div class="card-header">Weekly Attendance (Mon–Sat)</div>
    <div class="card-body">
      <canvas id="weekdayChart" style="height: 60px; max-height: 200px;"></canvas>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
  function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('datetime').textContent = now.toLocaleString('en-US', options);
  }
  setInterval(updateDateTime, 1000);
  updateDateTime();

  // Monthly Chart
  const presentData = <?php echo json_encode($presentData); ?>;
  const lineLabels = presentData.map(item => item.date);
  const lineCounts = presentData.map(item => item.count);

  const ctxLine = document.getElementById('presentChart').getContext('2d');
  new Chart(ctxLine, {
    type: 'line',
    data: {
      labels: lineLabels,
      datasets: [{
        label: 'Number of Presents',
        data: lineCounts,
        borderColor: 'green',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        tension: 0.3,
        fill: true,
        pointRadius: 4,
        pointBackgroundColor: 'green'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true },
        x: { title: { display: true, text: 'Date' } }
      }
    }
  });

  // Weekly Bar Chart
  const weekLabels = <?php echo json_encode(array_keys($weekdayData)); ?>;
  const weekCounts = <?php echo json_encode(array_values($weekdayData)); ?>;

  const ctxBar = document.getElementById('weekdayChart').getContext('2d');
  new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: weekLabels,
      datasets: [{
        label: 'Presents',
        data: weekCounts,
        backgroundColor: '#4F7942',
        borderColor: '#355E3B',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
