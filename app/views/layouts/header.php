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
<body class="bg-brand-dark text-gray-300"> <!-- Applied to body for full page effect -->
<header class="bg-brand-dark text-gray-200 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>home/index" class="text-neon-purple hover:text-purple-400 transition-colors">
                Accreditation Management System
            </a>
        </h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/profile" class="px-3 py-2 hover:bg-brand-gray rounded transition-colors">Profile</a>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="px-3 py-2 hover:bg-brand-gray rounded transition-colors">Processes</a>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/myTasks" class="px-3 py-2 hover:bg-brand-gray rounded transition-colors">My Tasks</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/listUsers" class="px-3 py-2 hover:bg-brand-gray rounded transition-colors">Users</a>
                <?php endif; ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/logout" class="px-3 py-2 bg-neon-purple text-white hover:bg-purple-700 rounded transition-colors"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
            <?php else: ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showLoginForm" class="px-3 py-2 hover:bg-brand-gray rounded transition-colors">Login</a>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showRegistrationForm" class="px-3 py-2 hover:bg-brand-gray rounded transition-colors">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container mx-auto p-4 bg-brand-gray min-h-screen">
