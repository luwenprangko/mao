<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
    <h1>Hello World</h1>
    <p>You are logged in as: <?= htmlspecialchars($_SESSION['email']) ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
