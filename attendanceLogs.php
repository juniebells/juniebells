<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "login");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle date filter
$search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';

// Initialize $result variable
$result = null;

$sql = "SELECT studentID, name, date, time_in, time_out, status FROM attendanceLogs";

// Apply the date filter if set
if (!empty($search_date)) {
    $sql .= " WHERE date = '" . $conn->real_escape_string($search_date) . "'";
}
$sql .= " ORDER BY date DESC, time_in DESC";

// Execute query and assign the result to $result
if ($result = $conn->query($sql)) {
    // Query executed successfully
} else {
    // Handle error if the query fails
    echo "Error executing query: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records - Smart Attendance System</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adminDashboard.css">

    <!-- JS -->
    <script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
    <script defer src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
    <script defer>
        document.addEventListener('DOMContentLoaded', function () {
            $('#example').DataTable({
                order: [[2, 'desc']] // Sort by Date column (index 2)
            });
        });
    </script>

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
          .sidebar.minimized {
    width: 60px; /* Minimized width */
    transition: all 0.3s ease;
}

.sidebar.minimized #sidebarLinks {
    display: none;
}

.sidebar.minimized #sidebarText {
    display: none;
}

.sidebar.minimized #sidebarLogo {
    display: block;
}

    
    </style>
</head>

<body>

<div class="wrapper d-flex">
    <div class="sidebar d-flex flex-column p-3" id="sidebar" style="height: 100vh; position: relative;">
    <!-- Sidebar Header with logo and title -->
    <h3 class="text-warning d-flex align-items-center">
        <a href="#" class="d-flex align-items-center">
            <img src="Logo.png" alt="" class="logo" id="sidebarLogo" style="width: 40px; height: auto; margin-right: 10px;">
            <span class="text-warning" id="sidebarText">ADMIN PORTAL</span>
        </a>
    </h3>

    <!-- Button to toggle the sidebar -->
    <button class="navbar-toggler d-md-none" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Sidebar Links -->
    <ul class="nav flex-column flex-grow-1" id="sidebarLinks">
        <li class="nav-item"><a class="nav-link active" href="adminDashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="adminAttRecords.php">Tracking</a></li>
        <li class="nav-item"><a class="nav-link" href="attendanceLogs.php">Attendance logs</a></li>
        <li class="nav-item"><a class="nav-link" href="adminStudentlist.php">Student List</a></li>
    </ul>

    <!-- Logout -->
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
                <h3><b>Attendance Logs</b></h3>
                
                <div>
                    <p class="mb-0 fs-4 fw-bold" id="currentDate"></p>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <table id="example" class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Status</th>
            <th>Hours Rendered</th> <!-- Add this for the calculated column -->
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['studentID']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                    <td><?php echo htmlspecialchars($row['time_out']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <?php
                        if (!empty($row['time_in']) && !empty($row['time_out']) && $row['time_out'] !== '00:00:00') {
                            $timeIn = new DateTime($row['time_in']);
                            $timeOut = new DateTime($row['time_out']);
                            $interval = $timeIn->diff($timeOut);
                            $hours = $interval->h + ($interval->days * 24);
                            $minutes = $interval->i;
                            echo sprintf('%d:%02d', $hours, $minutes);
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-center">No records found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
        </div>
    </div>
</div>


<!-- Show current date in navbar -->
<script>
    document.getElementById("currentDate").textContent = new Date().toDateString();

      document.getElementById('sidebarToggle').addEventListener('click', function () {
        let sidebar = document.getElementById('sidebar');
        let logo = document.getElementById('sidebarLogo');
        let text = document.getElementById('sidebarText');
        let links = document.getElementById('sidebarLinks');
        
        sidebar.classList.toggle('minimized');
        
        if (sidebar.classList.contains('minimized')) {
            logo.style.display = 'block';
            text.style.display = 'none';
            links.style.display = 'none';
        } else {
            logo.style.display = 'none';
            text.style.display = 'block';
            links.style.display = 'block';
        }
    });
      document.getElementById('sidebarToggle').addEventListener('click', function () {
    let sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('minimized');
});

</script>

</body>
</html>
