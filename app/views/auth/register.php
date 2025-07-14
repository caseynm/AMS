<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-md mx-auto mt-10">
<h2 class="text-2xl font-semibold text-blue-600 mb-6 text-center">Register</h2>
<form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/register" method="POST" class="bg-white p-8 rounded-lg shadow-md border border-gray-200 space-y-6">
    <div class="mb-4">
        <label for="name" class="block text-gray-700 mb-1 font-medium">Name:</label>
        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-300 text-gray-900 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-colors">
    </div>
    <div class="mb-4">
        <label for="email" class="block text-gray-700 mb-1 font-medium">Email:</label>
        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-300 text-gray-900 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-colors">
    </div>
    <div class="mb-6">
        <label for="password" class="block text-gray-700 mb-1 font-medium">Password:</label>
        <input type="password" id="password" name="password" required class="w-full p-3 bg-gray-50 border border-gray-300 text-gray-900 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-colors">
    </div>
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): // Only logged-in superuser can set role  ?>
    <div class="mb-4">
        <label for="role" class="block text-gray-700 mb-1 font-medium">Role:</label>
        <select id="role" name="role" class="w-full p-3 bg-gray-50 border border-gray-300 text-gray-900 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-colors">
            <option value="regular" <?php echo (isset($_GET['role']) && $_GET['role'] === 'regular') ? 'selected' : ''; ?>>Regular</option>
            <option value="superuser" <?php echo (isset($_GET['role']) && $_GET['role'] === 'superuser') ? 'selected' : ''; ?>>Superuser</option>
        </select>
    </div>
    <?php else: ?>
        <input type="hidden" name="role" value="regular">
    <?php endif; ?>
    <button type="submit" class="w-full bg-blue-600 text-white hover:bg-blue-700 font-bold py-3 px-4 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-user-plus mr-2"></i> Register</button>
</form>
<p class="text-center mt-6 text-gray-600">Already have an account? <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showLoginForm" class="text-blue-600 hover:text-blue-800 font-semibold">Login here</a>.</p>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
