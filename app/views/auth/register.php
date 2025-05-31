<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen flex flex-col items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-black mb-6">Register</h2>

        <!-- Placeholder for error/success messages -->
        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <?php echo htmlspecialchars(urldecode($_GET['success'])); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/register" method="POST" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-bold text-black mb-1">Name</label>
                <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black" placeholder="Your Name">
            </div>
            <div>
                <label for="email" class="block text-sm font-bold text-black mb-1">Email address</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black" placeholder="you@example.com">
            </div>
            <div>
                <label for="password" class="block text-sm font-bold text-black mb-1">Password</label>
                <input type="password" id="password" name="password" required class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black" placeholder="Password">
            </div>

            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): // Only logged-in superuser can set role  ?>
            <div>
                <label for="role" class="block text-sm font-bold text-black mb-1">Role</label>
                <select id="role" name="role" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                    <option value="regular" <?php echo (isset($_GET['role']) && $_GET['role'] === 'regular') ? 'selected' : ''; ?>>Regular</option>
                    <option value="superuser" <?php echo (isset($_GET['role']) && $_GET['role'] === 'superuser') ? 'selected' : ''; ?>>Superuser</option>
                </select>
            </div>
            <?php else: ?>
                <input type="hidden" name="role" value="regular">
            <?php endif; ?>

            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <i class="fas fa-user-plus mr-2"></i> Register
            </button>
        </form>
        <p class="mt-6 text-center text-sm">
            Already have an account?
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showLoginForm" class="font-medium text-black hover:text-gray-700 underline">
                Login here
            </a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
