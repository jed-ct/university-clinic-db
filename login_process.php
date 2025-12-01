<?php
session_start();
include("database.php");

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['Password'])) {
        $_SESSION['user_id'] = $row['UserID'];
        $_SESSION['username'] = $row['Username'];

        header("Location: ./index.php");
        exit();
    }
}

header("Location: login.php?error=1");
exit();
?>