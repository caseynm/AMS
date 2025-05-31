<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Login</h2>
<form action="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=user/login" method="POST">
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=user/showRegistrationForm">Register here</a>.</p>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
