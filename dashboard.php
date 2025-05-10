<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="dashboard_admin.css">
    <title>SAS-Dashboard</title>
</head>
<body>

 <!--BUONG SIDEBAR--> 
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                
                <button id="toggle-btn" type="button">
                    <i class="lni lni-dashboard-square-1"></i>
                </button>

                <div class="sidebar-logo">
                    <a href="#">Admin Portal</a>                
                </div>         
            </div>

            <ul class="sidebar-nav">
                <li class="sidebar-items">
                    <a href="dashboard.html" class="sidebar-link">
                        <i class="lni lni-home-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-items">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-plus"></i>
                        <span>Add Student</span>
                    </a>
                </li>

                <li class="sidebar-items">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-notebook-1"></i>
                        <span>Attendance</span>
                    </a>
                </li>

            </ul>  

            <div class="sidebar-footer">
                <a href="logout.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
            
        </aside>

        
<!--NAANDITO LAHAT NG TABLE/DATE/SEARCHBAR/ADDSTUDENT-->
        <div class="main d-flex justify-content-center pt-5 ">

            <div class="position-absolute top-0 start-5 pb-5 pt-3">
                <h1 class="fw-bold text-uppercase">DASHBOARD</h1>
            </div>
            
            <div class="w-50">
                <div class="d-flex justify-content-center align-items-center"> 
<!--DATE-->             
                  <div class="container mt-4">
                    <div class="card bg-transparent align-items-start">
                        <div class="card-body text-center">
                            <p id="currentDate" class="fs-4 fw-bold"></p>
                        </div>
                    </div>
                </div>
<!--SEARCH-->                
                    <div class="container">
                        <div class="row justify-content-end">
                          <div class="col-md-7">
                            <div class="search-container">
                              <input type="text" class="form-control search-input" placeholder="Search student">
                              <i class="fas fa-search search-icon"></i>
                            </div>
                          </div>
                        </div>
                      </div>
            
                </div>
                
<!--TABLE-->     
<!--for back end, gawing extendable yung table everytime may bagong student na pumasok-->           
              <div class="table-responsive">
                  <table class="table table-bordered">
                  <thead class="bg-dark">
                      <tr>
                          <th>ID Number</th>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Year Level</th>
                          <th>Time In</th>
                      </tr>
                  </thead>
                  <tbody id="studentTableBody">
                      <tr>
                          <td><strong>1-22342</strong></td>
                          <td>Mark</td>
                          <td>Otto</td>
                          <td>@mdo</td>
                          <td>08:00 AM</td>
                      </tr>
                      <tr>
                          <td><strong>2-214343</strong></td>
                          <td>Jacob</td>
                          <td>Thornton</td>
                          <td>@fat</td>
                          <td>08:05 AM</td>
                      </tr>
                      <tr>
                          <td><strong>3-231422</strong></td>
                          <td>Larry</td>
                          <td>the Bird</td>
                          <td>@twitter</td>
                          <td>08:10 AM</td>
                      </tr>
                  </tbody>
              </table>
            </div>
                
<!--ADD STUDENT-->              

                  <div class="d-flex ">
                    <p class="ms-auto">Newly enrolled student? <a href="add_student.html">add here</a></p>  
                  </div>
            </div>           
          </div>         
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
<!--Javascript for REAL TIME DATE-->    
    <script>
      function updateDate() {
          let today = new Date();
          let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
          document.getElementById("currentDate").innerText = today.toLocaleDateString("en-US", options);
      }
      updateDate();
  </script>
</body>
</html>