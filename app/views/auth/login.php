<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Login</h2>
<form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/login" method="POST">
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="bg-neon-purple text-white hover:bg-purple-700 font-bold py-2 px-4 rounded transition-colors"><i class="fas fa-sign-in-alt mr-2"></i> Login</button>
</form>
<p>Don't have an account? <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showRegistrationForm" class="text-neon-purple hover:text-purple-400">Register here</a>.</p>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
