<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accreditation Management System</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($APP_BASE_URL); ?>public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<header>
    <h1><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>home/index" style="color:white;text-decoration:none;">Accreditation Management System</a></h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/profile">Profile</a> |
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index">Processes</a> |
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/myTasks">My Tasks</a> |
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/listUsers">Users</a> |
            <?php endif; ?>
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/logout">Logout</a>
        <?php else: ?>
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showLoginForm">Login</a> |
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showRegistrationForm">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container"> <!-- Added a container class for basic centering/padding -->
