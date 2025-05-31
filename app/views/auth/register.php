<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2 class="text-2xl font-semibold text-neon-purple mb-6 text-center">Register</h2>
<form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/register" method="POST" class="max-w-md mx-auto bg-brand-gray p-8 rounded-lg shadow-xl">
    <div class="mb-4">
        <label for="name" class="block text-gray-300 mb-1 font-semibold">Name:</label>
        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
    </div>
    <div class="mb-4">
        <label for="email" class="block text-gray-300 mb-1 font-semibold">Email:</label>
        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
    </div>
    <div class="mb-6">
        <label for="password" class="block text-gray-300 mb-1 font-semibold">Password:</label>
        <input type="password" id="password" name="password" required class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
    </div>
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): // Only logged-in superuser can set role  ?>
    <div class="mb-4">
        <label for="role" class="block text-gray-300 mb-1 font-semibold">Role:</label>
        <select id="role" name="role" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
            <option value="regular" <?php echo (isset($_GET['role']) && $_GET['role'] === 'regular') ? 'selected' : ''; ?>>Regular</option>
            <option value="superuser" <?php echo (isset($_GET['role']) && $_GET['role'] === 'superuser') ? 'selected' : ''; ?>>Superuser</option>
        </select>
    </div>
    <?php else: ?>
        <input type="hidden" name="role" value="regular">
    <?php endif; ?>
    <button type="submit" class="w-full bg-neon-purple text-white hover:bg-purple-700 font-bold py-3 px-4 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-user-plus mr-2"></i> Register</button>
</form>
<p class="text-center mt-6">Already have an account? <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showLoginForm" class="text-neon-purple hover:text-purple-400 font-semibold">Login here</a>.</p>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
