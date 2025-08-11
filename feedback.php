<?php
session_start();
$message = $_SESSION['feedback'] ?? null;
unset($_SESSION['feedback']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feedback</title>
    <style>
        .message.success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px auto;
            width: fit-content;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px auto;
            width: fit-content;
        }
    </style>
</head>
<body>
    <?php if ($message): ?>
        <div class="message <?= $message['type'] ?>">
            <?= $message['text'] ?>
        </div>
    <?php else: ?>
        <p style="text-align: center;">No feedback message available.</p>
    <?php endif; ?>
</body>
</html>
