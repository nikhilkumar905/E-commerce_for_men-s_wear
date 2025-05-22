<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($result && password_verify($password, $result['password'])) {
        $_SESSION['username'] = $username;

        // Redirect to index.html
        header('Location: index.html');
        exit();
    } else {
        echo "Invalid credentials. <a href='login.html'>Try again</a>";
    }
}
?>
