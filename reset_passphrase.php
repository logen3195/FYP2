<?php
// Database connection
$host = 'localhost';
$dbname = 'fyp';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if token is provided
if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Fetch user with this token
$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("<p style='color:red;'>Invalid or expired token.</p>");
}

// Process new passphrase submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassphrase = $_POST['passphrase'];

    if (!empty($newPassphrase)) {
        $passphraseHash = password_hash($newPassphrase, PASSWORD_DEFAULT);

        // Update database
        $stmt = $pdo->prepare("UPDATE users SET passphrase_hash = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
        $stmt->execute([$passphraseHash, $token]);

        echo "<p style='color:green;'>Passphrase reset successfully. <a href='login.php'>Login</a></p>";
        exit;
    } else {
        echo "<p style='color:red;'>Please enter a valid passphrase.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Passphrase</title>
</head>
<body>
    <h2>Reset Your Passphrase</h2>
    <form method="POST">
        <label for="passphrase">New Passphrase:</label>
        <input type="password" name="passphrase" required>
        <button type="submit">Reset</button>
    </form>
</body>
</html>
