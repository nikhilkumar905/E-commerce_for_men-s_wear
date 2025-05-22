<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);

    if ($stmt->execute()) {
        // ✅ Success: show alert + redirect to login
        echo "<script>
            alert('Registration successful! Please login.');
            window.location.href = 'login.html';
        </script>";
    } else {
        // ❌ Error (e.g. username/email already taken)
        echo "<script>
            alert('Error: Username or Email already exists.');
            window.location.href = 'register.html';
        </script>";
    }
}
?>
