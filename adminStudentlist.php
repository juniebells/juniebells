<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Import CSV</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<!-- Scripts should be placed at the end -->


</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records - Smart Attendance System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adminDashboard.css">
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet"  href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">

    <script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
    <script defer src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
    <script defer src="script.js"></script>
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
        .addNewStudent{
            background-color: ;
        }
    </style>
</head>

<body>
    

<div class="d-flex">
<!-- Sidebar -->
<div class="sidebar d-flex flex-column p-3" style="height: 100vh;">
    <h3 class="text-warning">
        <a ><img src="Logo.png" alt=""><span class="text-warning">ADMIN PORTAL</span></a> 
    </h3>

    <ul class="nav flex-column flex-grow-1">
        <li class="nav-item">
            <a class="nav-link active" href="adminDashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">Tracking
            <a class="nav-link" href="adminAttRecords.php"></a>
        </li> 
        <li class="nav-item">
            <a class="nav-link" href="attendanceLogs.php">Attendance logs</a>
        </li>  
        
        <li class="nav-item">
            <a class="nav-link" href="adminStudentlist.php">Student List</a>
        </li>
         <a href="adminStudentlist.php" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="lni lni-plus"></i> Add New Student
            </a>
       
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
                <div class="title container-fluid">
                    <h3><b>Student Lists</b></h3>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
<!--DATEEEEEEEE-->
                    <div>
                    <p class="mb-0"id="currentDate" class=" fs-4 fw-bold"></p>
                    </div>
                </div>
            </nav>

            
           
<div class="container mt-5">
  <div class="d-flex justify-content-end mb-3">
    <button class="btn btn-warning" onclick="toggleCSVForm()">+ Import CSV file</button>
  </div>

  <!-- Floating Import CSV Form -->
  <div id="floatingCSVForm" class="position-fixed top-50 end-0 translate-middle-y me-4" style="z-index: 1050; width: 340px; display: none;">
    <div class="card border-0 shadow rounded-3">
      <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-success fw-bold">Import CSV</h6>
        <button class="btn-close" onclick="toggleCSVForm()"></button>
      </div>
      <form id="csvImportForm" action="importCSV.php" method="POST" enctype="multipart/form-data">
        <div class="card-body bg-white">
          <div class="mb-3">
            <label for="csvFile" class="form-label text-success fw-semibold">Select CSV File</label>
            <input type="file" class="form-control border-success" id="csvFile" name="csvFile" accept=".csv" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-success">Upload</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

 
             
        
        
            
<!--MODALLLLLLLL-->
            <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel"><span class="text-warning"><img class="modal-logo"src="Logo.png">SAS</span> - Add New Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                         
                        <div class="modal-body">
                            <form id="studentForm" action="adminConnection.php" method="POST">
                                <!-- RFID tag  -->
                                <div class="mb-3">
                                    <label for="studentID" class="form-label">Student ID</label>
                                    <input type="text" class="form-control" id="student_id" name="StudentID" required>
                                </div>
                                <!-- RFID tag  -->
                                <div class="mb-3">
                                    <label for="RFIDtag" class="form-label">RFID tag #</label>
                                    <input type="number" class="form-control" id="RFIDtag" name="RFIDtag" required>
                                </div>
                                
                                <!-- First Name -->
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="student_fname" name="FirstName" required>
                                </div>
            
                                <!-- Last Name -->
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="student_lname" name="LastName" required>
                                </div>
            
                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="studentEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="studentEmail" name="Email" required>
                                </div>
            
                                <!-- Age -->
                                <div class="mb-3">
                                    <label for="studentAge" class="form-label">Age</label>
                                    <input type="number" class="form-control" id="age" name="Age" required>
                                </div>
                                  <!-- Gender -->
                                <div class="mb-3">
                                    <label for="studentSex" class="form-label">Sex</label>
                                    <select class="form-select" id="studentSex" name="Sex" required>
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="other">other</option>
                                    </select>
                                </div>
            
                                <!-- Course -->
                                <div class="mb-3">
                                    <label for="studentCourse" class="form-label">Course</label>
                                    <select class="form-select" id="studentCourse" name="Course" required>
                                        <option value="" disabled selected>Select Course</option>
                                        <option value="BS Computer Science">BS Computer Science</option>
                                        <option value="BS Information Technology">BS Information Technology</option>
                                        <option value="BS Computer Engineering">BS Computer Engineering</option>
                                        <option value="BS Business Administration">BS Business Administration</option>
                                        <option value="BS Hospitality Management">BS Hospitality Management</option>
                                        <option value="BS Accountancy">BS Accountancy</option>
                                        <option value="BS Tourism Management">BS Tourism Management</option>
                                        <!-- Add other courses as needed -->
                                    </select>
                                </div>
            
                                <!-- Year Level -->
                                <div class="mb-3">
                                    <label for="studentYearLevel" class="form-label">Year Level</label>
                                    <select class="form-select" id="studentYearLevel" name="YearLevel" required>
                                        <option value="" disabled selected>Select Year Level</option>
                                        <option value="1st Year">1st Year</option>
                                        <option value="2nd Year">2nd Year</option>
                                        <option value="3rd Year">3rd Year</option>
                                        <option value="4th Year">4th Year</option>
                                        
                                    </select>
                                </div>
            
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-success" >Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        


        




      <div id="student-records" class="container mt-4">
            <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Student ID</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Student Email</th>
                    <th scope="col">Age</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Course</th>
                    <th scope="col">Year Level</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody id="studentList">
                <!-- Example of a student record -->
                
             
                 <?php
                    $conn = mysqli_connect("localhost", "root","", "login");
                    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$defaultPassword = password_hash('password1234', PASSWORD_DEFAULT);

// Get all from registration
$regQuery = "SELECT studentID, email FROM registration";
$regResult = mysqli_query($conn, $regQuery);

if (mysqli_num_rows($regResult) > 0) {
    while ($row = mysqli_fetch_assoc($regResult)) {
        $studentID = mysqli_real_escape_string($conn, $row['studentID']);
        $email = mysqli_real_escape_string($conn, $row['email']);

        // Assign usertype based on studentID
        $usertype = ($studentID === '1') ? 'admin' : 'user';

        $syncQuery = "
            INSERT INTO logindb (studentID, password_hash, email, usertype)
            VALUES ('$studentID', '$defaultPassword', '$email', '$usertype')
            ON DUPLICATE KEY UPDATE 
                email = VALUES(email),
                usertype = '$usertype'
        ";

        if (!mysqli_query($conn, $syncQuery)) {
            echo "❌ Error syncing studentID $studentID: " . mysqli_error($conn) . "<br>";
        }
    }
} else {
    echo "⚠️ No records found in registration table.";
}

                    $query = "SELECT studentID, firstName, lastName, email, age,sex, course, yearLevel FROM registration"; // make sure this table exists
                    $query_run = mysqli_query($conn, $query);

                    if (mysqli_num_rows($query_run) > 0) {
    foreach ($query_run as $row) {
        // Skip the row where studentID is 1
        if ($row['studentID'] == '1') {
            continue; // Skip this iteration
        }
                            ?>
                            <tr>
                                <td><?php echo $row['studentID']; ?></td>
                                <td><?php echo $row['firstName']; ?></td>
                                <td><?php echo $row['lastName']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['age']; ?></td>
                                <td><?php echo $row['sex']; ?></td>
                                <td><?php echo $row['course']; ?></td>
                                <td><?php echo $row['yearLevel']; ?></td>
                                <td>
                                    <!--edit button--> 
                                    <button 
                                        class="btn btn-warning btn-sm editBtn"
                                        data-id="<?php echo $row['studentID']; ?>"
                                        data-fname="<?php echo $row['firstName']; ?>"
                                        data-lname="<?php echo $row['lastName']; ?>"
                                        data-email="<?php echo $row['email']; ?>"
                                        data-age="<?php echo $row['age']; ?>"
                                        data-sex="<?php echo $row['sex']; ?>"
                                        data-course="<?php echo $row['course']; ?>"
                                        data-year="<?php echo $row['yearLevel']; ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editStudentModal" >
                                        Edit
                                    </button>

                                    <!--delete button-->
                                     <form action="deleteStudent.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['studentID']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No Record Found</td></tr>";
                    }



                    ?>


                <!-- Additional students will be added dynamically here -->
            </tbody>
        </table></div>    
           
        </div>
    </div>   



       <!-- Edit Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editStudentForm" action="updateStudents.php" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit_id">

           <!-- First Name -->
          <div class="mb-3">
            <label for="edit_rfidtag">RFID tag</label>
            <input type="text" name="edit_rfidtag" id="edit_rfidtag" class="form-control" required>
          </div>


          <!-- First Name -->
          <div class="mb-3">
            <label for="edit_fname">First Name</label>
            <input type="text" name="edit_fname" id="edit_fname" class="form-control" required>
          </div>

          <!-- Last Name -->
          <div class="mb-3">
            <label for="edit_lname">Last Name</label>
            <input type="text" name="edit_lname" id="edit_lname" class="form-control" required>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="edit_email">Email</label>
            <input type="email" name="edit_email" id="edit_email" class="form-control" required>
          </div>

          <!-- Age -->
          <div class="mb-3">
            <label for="edit_age">Age</label>
            <input type="number" name="edit_age" id="edit_age" class="form-control" required>
          </div>
          <!-- Gender -->
          <div class="mb-3">
            <label for="edit_sex">Gender</label>
            <select class="form-select" name="edit_sex" id="edit_sex" required>
              <option value="" disabled selected>Select Year Level</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="other">other</option>
            </select>
          </div>

          <!-- Course -->
          <div class="mb-3">
            <label for="edit_course">Course</label>
            <select class="form-select" name="edit_course" id="edit_course" required>
              <option value="" disabled selected>Select Course</option>
              <option value="BS Computer Science">BS Computer Science</option>
              <option value="BS Information Technology">BS Information Technology</option>
              <option value="BS Business Administration">BS Business Administration</option>
              <option value="BS Computer Engineering">BS Computer Engineering</option>
              <option value="BS Business Administration">BS Business Administration</option>
              <option value="BS Hospitality Management">BS Hospitality Management</option>
              <option value="BS Accountancy">BS Accountancy</option>
              <option value="BS Tourism Management">BS Tourism Management</option>
            </select>
          </div>

          <!-- Year Level -->
          <div class="mb-3">
            <label for="edit_year">Year Level</label>
            <select class="form-select" name="edit_year" id="edit_year" required>
              <option value="" disabled selected>Select Year Level</option>
              <option value="1st Year">1st Year</option>
              <option value="2nd Year">2nd Year</option>
              <option value="3rd Year">3rd Year</option>
              <option value="4th Year">4th Year</option>
            </select>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>


        <td>

          

          <!-- Delete Button -->
            <form action="delete_student.php" method="POST" style="display:inline;">
                <input type="hidden" name="delete_id" value="<?php echo $row['studentID']; ?>">
                <button></button>
            </form>
        </td>


     <!-- Bootstrap 5 JS and dependencies -->
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

   <script>

// Event listener for when the page is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add an event listener to the edit buttons
    const editButtons = document.querySelectorAll('.editBtn');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get data attributes from the clicked button
            const studentId = this.getAttribute('data-id');
            const RFIDtag = this.getAttribute('data-RFIDtag');
            const firstName = this.getAttribute('data-fname');
            const lastName = this.getAttribute('data-lname');
            const email = this.getAttribute('data-email');
            const age = this.getAttribute('data-age');
            const sex = this.getAttribute('data-sex');
            const course = this.getAttribute('data-course');
            const yearLevel = this.getAttribute('data-year');
            
            // Populate the modal form fields with the data
            document.getElementById('edit_id').value = studentId;
             document.getElementById('edit_rfidtag').value = RFIDtag;
            document.getElementById('edit_fname').value = firstName;
            document.getElementById('edit_lname').value = lastName;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_age').value = age;
            document.getElementById('edit_sex').value = sex;
            document.getElementById('edit_course').value = course;
            document.getElementById('edit_year').value = yearLevel;
        });
    });
});
</script>

 <script>


        // Handle form submission for adding new student
        document.getElementById('studentForm').addEventListener('submit', function (event) {
            event.preventDefault();

            // Get input values
            const RFIDtag = document.getElementById('RFIDtag').value;
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const email = document.getElementById('email').value;
            const age = document.getElementById('age').value;
             const age = document.getElementById('sex').value;
            const course = document.getElementById('course').value;
            const yearLevel = document.getElementById('yearLevel').value;

            // Add new student to the table
            const studentList = document.getElementById('studentList');
            const newRow = studentList.insertRow();

            newRow.innerHTML = `
                <td>${studentId}</td>
                <td>${firstName}</td>
                <td>${lastName}</td>
                <td>${email}</td>
                <td>${age}</td>
                <td>${sex}</td>
                <td>${course}</td>
                <td>${yearLevel}</td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editStudentModal">
                        Edit
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteStudent(this)">Delete</button>
                </td>
            `;

        
            });
    </script>


    <script >
      
            // Trigger modal with data
$('.editBtn').on('click', function () {
  $('#edit_id').val($(this).data('id'));
  $('#edit_rfidtag').val($(this).data('RFIDtag'));
  $('#edit_fname').val($(this).data('fname'));
  $('#edit_lname').val($(this).data('lname'));
  $('#edit_email').val($(this).data('email'));
  $('#edit_age').val($(this).data('age'));
  $('#edit_sex').val($(this).data('sex'));
  $('#edit_course').val($(this).data('course'));
  $('#edit_year').val($(this).data('year'));
});

    </script>

    <script>
      //uploading csv
  function toggleCSVForm() {
    const form = document.getElementById('floatingCSVForm');
    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
  }

  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('csvImportForm');
    form.addEventListener('submit', function (e) {
      const fileInput = document.getElementById('csvFile');
      if (!fileInput.value) {
        e.preventDefault();
        alert('Please choose a CSV file before uploading.');
      }
    });
  });
</script>


   <script>  document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM fully loaded");
  document.getElementById('searchInput').addEventListener('keyup', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#studentList tr');

    rows.forEach(row => {
      // Only proceed if the row has at least 3 cells (ID, First Name, Last Name)
      if (row.cells.length < 3) return;

      const firstName = row.cells[1].textContent.toLowerCase();
      const lastName = row.cells[2].textContent.toLowerCase();

      if (firstName.includes(searchValue) || lastName.includes(searchValue)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
});

    </script>
</body>
  


</html>
