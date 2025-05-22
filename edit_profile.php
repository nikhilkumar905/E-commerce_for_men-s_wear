<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'];
$userResult = $db->query("SELECT * FROM users WHERE username = '$username'")->fetchArray();
$user_id = $userResult['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = htmlspecialchars($_POST['username']);
    $new_email = htmlspecialchars($_POST['email']);
    $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $userResult['password'];

    // Update user in database
    $stmt = $db->prepare("UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id");
    $stmt->bindValue(':username', $new_username, SQLITE3_TEXT);
    $stmt->bindValue(':email', $new_email, SQLITE3_TEXT);
    $stmt->bindValue(':password', $new_password, SQLITE3_TEXT);
    $stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
    $stmt->execute();

    $_SESSION['username'] = $new_username; // Update session username
    header("Location: profile.php"); // Redirect to profile to see updated info
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .form-container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 350px; }
        .form-container h2 { text-align: center; margin-bottom: 20px; }
        .form-container input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        .form-container button { width: 100%; padding: 10px; background: #4CAF50; color: white; border: none; border-radius: 5px; font-size: 16px; }
        .form-container a { display: inline-block; margin-top: 15px; text-align: center; width: 100%; text-decoration: none; color: #2196F3; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Profile</h2>
    <form method="post">
        <input type="text" name="username" value="<?php echo htmlspecialchars($userResult['username']); ?>" required>
        <input type="email" name="email" value="<?php echo htmlspecialchars($userResult['email']); ?>" required>
        <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
        <button type="submit">Save Changes</button>
    </form>
    <a href="profile.php">Cancel</a>
</div>

</body>
</html>
