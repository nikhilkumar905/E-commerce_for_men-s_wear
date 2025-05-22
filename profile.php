<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'];

$stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if (!$result) {
    echo "User not found.";
    exit();
}

$email = $result['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f0f0; /* Match your website's background */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .profile-card {
            background: white;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .profile-card:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .profile-card h2 {
            margin: 10px 0 5px;
            color: #333;
            font-size: 24px;
        }
        .profile-card p {
            color: #555;
            font-size: 16px;
            margin: 5px 0 20px;
        }
        .profile-card .logout-btn {
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .profile-card .logout-btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<div class="profile-card">
    <h2><?php echo htmlspecialchars($username); ?></h2>
    <p><?php echo htmlspecialchars($email); ?></p>
    <a href="edit_profile.php" style="display:inline-block; margin-top:15px; background:#2196F3; color:white; padding:10px 15px; border-radius:5px; text-decoration:none;">Edit Profile</a>

    <a class="logout-btn" href="logout.php">Logout</a>
</div>

</body>
</html>
