<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accreditation Management System</title>
    <link rel="stylesheet" href="/css/style.css"> <!-- Assuming public/css/style.css -->
</head>
<body>
<header>
    <h1><a href="/index.php?url=home/index" style="color:white;text-decoration:none;">Accreditation Management System</a></h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/index.php?url=user/profile">Profile</a> |
            <a href="/index.php?url=accreditation/index">Processes</a> |
            <a href="/index.php?url=task/myTasks">My Tasks</a> |
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                <a href="/index.php?url=user/listUsers">Users</a> |
            <?php endif; ?>
            <a href="/index.php?url=user/logout">Logout</a>
        <?php else: ?>
            <a href="/index.php?url=user/showLoginForm">Login</a> |
            <a href="/index.php?url=user/showRegistrationForm">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container"> <!-- Added a container class for basic centering/padding -->
<?php
// Display messages if any (e.g., success or error)
if (isset($_GET['error'])) {
    echo '<p style="color:red;">Error: ' . htmlspecialchars($_GET['error']) . '</p>';
}
if (isset($_GET['success'])) {
    echo '<p style="color:green;">Success: ' . htmlspecialchars($_GET['success']) . '</p>';
}
?>
