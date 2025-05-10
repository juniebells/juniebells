<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Attendance System</title>
    <link rel="stylesheet" href="allLogin.css">
</head>
<body>
    <div class="overlay">
        <div class="container">
            <form action="login.php" method="POST">
                <h2>Login</h2>
                <?php if (isset($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>
                <?php } ?>

                <div class="email">
                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" required><br>
                </div>  

                <div class="pword">
                    <label for="pword">Password:</label><br>
                    <input type="password" id="pword" name="password" autocomplete="off" required><br>
                </div>

                <a href="forgotPassword.php">Forgot Password?</a>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
