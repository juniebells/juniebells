<?php
$conn = mysqli_connect("localhost", "root", "", "login");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Form was not submitted using POST method.");
}


if(isset($_POST['edit_id'])) {
    $id = $_POST['edit_id'];
    $RFIDtag = $_POST['edit_rfidtag'];
    $fname = $_POST['edit_fname'];
    $lname = $_POST['edit_lname'];
    $email = $_POST['edit_email'];
    $age = $_POST['edit_age'];
    $sex = $_POST['edit_sex'];
    $course = $_POST['edit_course'];
    $year = $_POST['edit_year'];

    $query = "UPDATE registration SET RFIDtag= '$RFIDtag',firstName='$fname', lastName='$lname', email='$email', age='$age', sex='$sex',course='$course', yearLevel='$year' WHERE studentID='$id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run){
        echo "<script>alert('Student Updated Successfully'); window.location.href='adminStudentlist.php';</script>";
    } else {
        echo "<script>alert('Update Failed'); window.location.href='adminStudentlist.php';</script>";
    }
}
?>
