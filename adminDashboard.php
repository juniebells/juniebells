
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css" />
    <link rel="stylesheet" href="adminDashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> <!-- Add this script for data labels -->
    <style>
        .card-body{
            border-color: black;
            border-style: solid;
            border-radius: 10px;
        }
        
       

        .sidebar .nav-link:hover {
            background-color: #b39f34;
        }

        .main-content {
            padding: 20px;
        }
        .result{
            border: solid;
            border-color: black;
            border-radius: 20px;
        }
        
    </style>
</head>

<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column p-3" style="height: 100vh;">
    <h3 class="text-warning">
        <a ><img src="Logo.png"><span class="text-warning">ADMIN PORTAL</span></a>
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




        

<!-- Main Content -->
        <div class="main-content flex-grow-1">
<!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="title container-fluid ">
                    <h3 class="mb-0"><b>Dashboard</b></h3>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
<!--DATEEEEEEEE-->               
                    <div>
                        <p class ="mb-0"id="currentDate" class="fs-4 fw-bold"></p>
                    </div>
                </div>
            </nav>

<?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login";

    $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

                // Query to count total number of students
                $query = "SELECT COUNT(studentID) AS total_students FROM registration";
                $result = $conn->query($query);
                $total_students = 0;
                if ($result && $row = $result->fetch_assoc()) {
                    $total_students = $row['total_students'];
                }

                // Get today's date
                $today = date('Y-m-d');

                // Query to count students present today
                $query = "SELECT COUNT(*) AS present_today FROM attendance WHERE status = 'Present' AND DATE = '$today'";
                $result = $conn->query($query);
                $present_today = 0;
                if ($result && $row = $result->fetch_assoc()) {
                    $present_today = $row['present_today'];
                }
                $query = "SELECT COUNT(*) AS no_time_out_today FROM attendance 
                          WHERE status = 'No Time Out' AND date = '$today'";
                $result = $conn->query($query);
                $no_time_out_today = 0;
                if ($result && $row = $result->fetch_assoc()) {
                    $no_time_out_today = $row['no_time_out_today'];
    }

              
               // Query to get student data grouped by year level, excluding NULL and 0
                $query = "SELECT yearLevel, COUNT(*) AS count 
                          FROM registration 
                          WHERE yearLevel IS NOT NULL AND yearLevel != 0 
                          GROUP BY yearLevel";

                $result = $conn->query($query);
                $year_data = [];
                while ($row = $result->fetch_assoc()) {
                    $year_data[$row['yearLevel']] = $row['count'];
                }
                                // Query to get student count grouped by course
                $query = "SELECT course, COUNT(*) AS count FROM registration GROUP BY course";
                $result = $conn->query($query);

               $course_data = [];
                $total_students = 0;

                // First pass: build array and total count (pie chart), excluding NULL or empty courses
                while ($row = $result->fetch_assoc()) {
                    if (!empty($row['course'])) {  // Check if course is not NULL or empty
                        $course_data[$row['course']] = $row['count'];
                        $total_students += $row['count'];
                    }
                }

                // Calculate rounded percentages (pie chart)
                $course_percentages = [];
                foreach ($course_data as $course => $count) {
                    $percentage = round(($count / $total_students) * 100);
                    $course_percentages[$course] = $percentage;
                }

                // Query to count 'Present' attendance grouped by date (last 6 days including today)
                $query = "
                     SELECT DATE(time_in) as scan_date, COUNT(*) AS count 
                        FROM attendanceLogs 
                        WHERE time_in IS NOT NULL 
                        GROUP BY scan_date 
                        ORDER BY scan_date DESC 
                        LIMIT 6
                ";

                $result = $conn->query($query);

                // Arrays to hold date and count data
                $trend_data = [];
                $trend_labels = [];

                // Get data from query
                while ($row = $result->fetch_assoc()) {
                    $trend_data[] = (int)$row['count'];
                    $trend_labels[] = date('D', strtotime($row['scan_date'])); // Format date to day abbreviation
                }

                // Since we used DESC in SQL, reverse to match Mon–Sat order

                $trend_data = array_reverse($trend_data);
                $trend_labels = array_reverse($trend_labels);

                // Get current week's Monday and Saturday
                $monday = date('Y-m-d', strtotime('monday this week'));
                $saturday = date('Y-m-d', strtotime('saturday this week'));

                // Query attendance count per day (based on `date` column)
                $sql = "
                    SELECT DAYNAME(date) AS day, COUNT(*) AS count
                    FROM attendanceLogs
                    WHERE date BETWEEN '$monday' AND '$saturday'
                    GROUP BY date
                    ORDER BY FIELD(DAYNAME(date), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')
                ";

                $result = $conn->query($sql);

                // Prepare full week with default 0 counts
                $week_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $attendance_data = array_fill_keys($week_days, 0);

                // Replace with actual counts from DB
                while ($row = $result->fetch_assoc()) {
                    $day = $row['day'];
                    $attendance_data[$day] = (int)$row['count'];
                }



        $conn->close();
                            ?>
            
            <!-- Attendance Statistics -->
            <div class=" container mt-4">
                <div class="row">
                    <div class=" col-md-3">
                        <div class="result card text-dark  mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Students</h5>
                                <h1 class="card-text"><?php echo $total_students; ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="result card text-dark  mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Present Today</h5>
                                <h1 class="card-text"><?php echo $present_today; ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                    <div class="result card text-dark mb-3">
                        <div class="card-body">
                            <h5 class="card-title">No Time out</h5>
                            <h1 class="card-text"><?php echo $no_time_out_today; ?></h1>
                        </div>
                    </div>
                </div>
    
 <!-- Charts -->
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Course Distribution</h5>
                            <div class="chart-container">
                                <canvas id="courseChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Year Level Distribution</h5>
                            <div class="chart-container">
                                <canvas id="yearLevelChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Attendance Trend</h5>
                            <div class="chart-container">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script>
         function updateDate() {
          let today = new Date();
          let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
          document.getElementById("currentDate").innerText = today.toLocaleDateString("en-US", options);
      }
      updateDate();

      Chart.register(ChartDataLabels);

// PHP data to JS
const courseLabels = <?php echo json_encode(array_keys($course_percentages)); ?>;
const coursePercentages = <?php echo json_encode(array_values($course_percentages)); ?>;

const courseChart = new Chart(document.getElementById('courseChart'), {
    type: 'pie',
    data: {
        labels: courseLabels,
        datasets: [{
            data: coursePercentages,
            backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#00a896']
        }]
    },
    options: {
        responsive: true,
        animation: {
            duration: 2000,  // Set animation duration (2 seconds)
            easing: 'easeOutBounce',  // Apply easing (bounce)
            onComplete: function() {
                console.log("Animation Complete!");
            }
        },
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 20
                }
            },
            datalabels: {
                display: true,
                color: '#fff',
                font: {
                    weight: 'bold',
                    size: 14
                },
                formatter: function(value, context) {
                    return value + '%';
                }
            }
        },
        layout: {
            padding: 10,
        },
        elements: {
            arc: {
                borderWidth: 0
            }
        }
    }
});

     // Year Level Horizontal Bar Chart
const yearData = <?php echo json_encode(array_values($year_data)); ?>;
const yearLabels = <?php echo json_encode(array_keys($year_data)); ?>;

const barColors = ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#00a896'];

const yearLevelChart = new Chart(document.getElementById('yearLevelChart'), {
    type: 'bar',
    data: {
        labels: yearLabels,
        datasets: [{
            label: 'Students by Year Level',
            data: yearData,
            backgroundColor: barColors.slice(0, yearData.length),
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        animation: {
            duration: 1500,
            easing: 'easeInOutQuad',
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: {
                    display: false
                },
                ticks: {
                    display: false // Hides the numbers at the bottom
                }
            },
            y: {
                grid: {
                    display: false
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});


   const labels = <?php echo json_encode(array_keys($attendance_data)); ?>;
const values = <?php echo json_encode(array_values($attendance_data)); ?>;

new Chart(document.getElementById('attendanceChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Weekly Attendance Logs',
            data: values,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            fill: true,
            tension: 0.3,
            pointRadius: 5,
            pointBackgroundColor: 'white',
            pointBorderColor: 'rgba(54, 162, 235, 1)'
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                grid: {
                    display: false // Hide X-axis grid lines
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    display: false // Hide Y-axis ticks
                },
                grid: {
                    display: false // Hide Y-axis grid lines
                }
            }
        }
    }
});

    </script>

    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
