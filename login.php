<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
            text-align: center;
        }

        /* Glassmorphism Container */
        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
            transition: transform 0.3s ease-in-out;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        /* Heading */
        .container h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #fff;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        /* Input Fields */
        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            outline: none;
            transition: background 0.3s, transform 0.2s;
        }

        .form-group input:focus {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.02);
        }

        /* Passphrase Strength Indicator */
        #passphrase-strength {
            font-size: 0.9rem;
            margin-top: 5px;
            color: #ffd700;
            font-weight: 600;
        }

        /* Toggle Password Visibility */
        .toggle-password {
            display: block;
            text-align: right;
            margin-top: -12px;
            margin-bottom: 20px;
            margin-top: 10px;
            font-size: 0.9rem;
            cursor: pointer;
            color: #aad2ff;
            transition: color 0.3s;
        }

        .toggle-password:hover {
            color: #ffcccb;
        }

        /* Error Message */
        .error {
            background: rgba(255, 0, 0, 0.2);
            color: #ff4d4d;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        /* Buttons */
        button {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: #fff;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: linear-gradient(135deg, #0072ff, #00c6ff);
            transform: scale(1.05);
        }

        /* Forgot Passphrase Link */
        p a {
            color: #aad2ff;
            text-decoration: none;
            transition: color 0.3s;
        }

        p a:hover {
            color: #ffcccb;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .container {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome Back</h1>
        <?php
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username']);
            $passphrase = htmlspecialchars($_POST['passphrase']);

            // Database connection
            $host = 'localhost';
            $dbname = 'fyp';
            $dbusername = 'root';
            $dbpassword = '';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Check credentials
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
                $stmt->execute([':username' => $username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($passphrase, $user['passphrase_hash'])) {
                    $_SESSION['username'] = $username;
                    header("Location: screen.php");
                    exit();
                } else {
                    echo "<div class='error'>Invalid username or passphrase. Please try again.</div>";
                }
            } catch (PDOException $e) {
                echo "<div class='error'>Database error: " . $e->getMessage() . "</div>";
            }
        }
        ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="passphrase">Passphrase</label>
                <input type="password" id="passphrase" name="passphrase" required oninput="checkPassphraseStrength()">
                <p id="passphrase-strength"></p>
                <a class="toggle-password" onclick="togglePasswordVisibility()">Show Password</a>
            </div>
            <p>
                <a href="passphrase_recovery.php?username=<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">Forgot Passphrase?</a>
            </p>
            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passphraseInput = document.getElementById('passphrase');
            const toggleText = document.querySelector('.toggle-password');

            if (passphraseInput.type === 'password') {
                passphraseInput.type = 'text';
                toggleText.textContent = 'Hide Password';
            } else {
                passphraseInput.type = 'password';
                toggleText.textContent = 'Show Password';
            }
        }

        function checkPassphraseStrength() {
            const passphrase = document.getElementById('passphrase').value;
            const strengthIndicator = document.getElementById('passphrase-strength');
            
        }
    </script>
</body>
</html>
