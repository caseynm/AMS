<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accreditation Management System</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($APP_BASE_URL); ?>public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100 text-gray-900"> <!-- Body classes updated for light theme -->
<header class="bg-white text-gray-800 p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>home/index" class="text-blue-600 hover:text-blue-800 transition-colors">
                Accreditation Management System
            </a>
        </h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/profile" class="px-3 py-2 text-gray-700 hover:bg-gray-200 rounded transition-colors">Profile</a>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="px-3 py-2 text-gray-700 hover:bg-gray-200 rounded transition-colors">Processes</a>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/myTasks" class="px-3 py-2 text-gray-700 hover:bg-gray-200 rounded transition-colors">My Tasks</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/listUsers" class="px-3 py-2 text-gray-700 hover:bg-gray-200 rounded transition-colors">Users</a>
                <?php endif; ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/logout" class="px-3 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded transition-colors"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
            <?php else: ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showLoginForm" class="px-3 py-2 text-gray-700 hover:bg-gray-200 rounded transition-colors">Login</a>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showRegistrationForm" class="px-3 py-2 text-gray-700 hover:bg-gray-200 rounded transition-colors">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container mx-auto p-6 bg-white min-h-[calc(100vh-160px)] shadow-lg rounded-b-md"> <!-- Adjusted padding and min-height -->
