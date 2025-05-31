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
<body> <!-- Ensure body class is controlled by input.css -->
<header class="bg-white border-b border-black shadow-sm p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>home/index" class="text-black">
                Accreditation Management System
            </a>
        </h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/profile" class="text-black hover:underline px-3 py-2 rounded-md text-sm font-medium">Profile</a>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-black hover:underline px-3 py-2 rounded-md text-sm font-medium">Processes</a>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/myTasks" class="text-black hover:underline px-3 py-2 rounded-md text-sm font-medium">My Tasks</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/listUsers" class="text-black hover:underline px-3 py-2 rounded-md text-sm font-medium">Users</a>
                <?php endif; ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/logout" class="text-black hover:underline px-3 py-2 rounded-md text-sm font-medium"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
            <?php else: ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showLoginForm" class="text-black hover:underline px-3 py-2 rounded-md text-sm font-medium">Login</a>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showRegistrationForm" class="text-black hover:underline px-3 py-2 rounded-md text-sm font-medium">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container mx-auto p-4 min-h-screen">
