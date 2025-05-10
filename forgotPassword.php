<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        *{
            font-family: poppins;
        }

        body {
            background-image: url('BG.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background-color: transparent;
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border-style: solid;
            border-color: black;
        }
        .card h2, label{
            color: white;
        }

        .card button {
            background-color: black;
            color: white;
            border-color: black;
        }
        .card button:hover{
            transition: 0.1;
            background-color: white;
            color: black;
            border-color: white;
        }

        </style>
</head>
<body>

<div class="card">
    <h2 class="text-center mb-4">Forgot Password</h2>

    <form method="post" action="sendPasswordReset.php">
        <div class="mb-3">
            <label for="email" class="form-label">Enter your email address:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Send Verification Code</button>
    </form>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
