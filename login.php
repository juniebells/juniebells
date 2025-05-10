<?php 

session_start();

include "connection.php";

if (isset($_POST['email']) && isset($_POST['password'])) {

    function validate($data) {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $email = validate($_POST['email']);
    $pass = validate($_POST['password']);

    if (empty($email)) {
        header("Location: allLogin.php?error=Email is required");
        exit();
    } else if (empty($pass)) {
        header("Location: allLogin.php?error=Password is required");
        exit();
    } else {
        $sql = "SELECT * FROM loginDB WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            // Use password_verify to check the entered password against the hashed password
            if ($row['email'] === $email && password_verify($pass, $row['password_hash'])) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['usertype'] = $row['usertype']; // Store the usertype for role-based access
                if ($_SESSION['usertype'] == 'admin') {
                    header("Location: adminDashboard.php"); // Redirect to admin dashboard
                } else {
                    header("Location: studentProfile.php"); // Redirect to user dashboard
                }
                exit();
            } else {
                header("Location: allLogin.php?error=Incorrect email or password");
                exit();
            }
        } else {
            header("Location: allLogin.php?error=Incorrect email or password");
            exit();
        }
    }

} else {
    header("Location: allLogin.php");
    exit();
}
