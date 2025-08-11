<?php
session_start(); // Start the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest"; // Check if a username exists
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            text-align: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            font-size: 50px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background: #00c6ff;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #007acc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>Get started by logging into your account.</p>
        <a href="login.php" class="btn">Login</a>
    </div>
</body>
</html>
