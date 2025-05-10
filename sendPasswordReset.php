<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'database.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $_SESSION['email'] = $email; 

    // Check if email exists in database
    $query = "SELECT * FROM logindb WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("❌ Email not found in our records.");
    }

    $verification = random_int(100000, 999999); 
    $_SESSION['verification_code'] = $verification;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Username = 'arguellesjohnson0716@gmail.com'; 
        $mail->Password = 'cpfbuifihcaneobe'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('arguellesjohnson0716@gmail.com', 'Attendance System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body = 'Your verification code is <h1>' . $verification . '</h1>';

        if($mail->send()) {
            header("Location: verifyEmail.php");
            exit();
        }
    } catch (Exception $e) {
        echo "❌ Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
