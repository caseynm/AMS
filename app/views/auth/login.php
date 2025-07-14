<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-md mx-auto mt-10">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Login</h2>
    <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/login" method="POST" class="bg-white p-8 rounded-lg shadow-md border border-gray-200 space-y-6">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" class="w-full p-3 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-colors">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password:</label>
            <input type="password" id="password" name="password" required class="w-full p-3 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-colors">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white hover:bg-blue-700 font-bold py-3 px-4 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-sign-in-alt mr-2"></i> Login</button>
    </form>
    <p class="text-center mt-6 text-gray-600">Don't have an account? <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showRegistrationForm" class="text-blue-600 hover:text-blue-800 font-semibold">Register here</a>.</p>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
