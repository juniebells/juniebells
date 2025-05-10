<?php
$conn = mysqli_connect("localhost", "root", "", "login");

if(isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $query = "DELETE FROM registration WHERE studentID='$id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run){
        echo "<script>alert('Student Deleted Successfully'); window.location.href='adminStudentlist.php';</script>";
    } else {
        echo "<script>alert('Delete Failed'); window.location.href='adminStudentlist.php';</script>";
    }
}
?>
