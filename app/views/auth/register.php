<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Register</h2>
<form action="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=user/register" method="POST">
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>">
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): // Only logged-in superuser can set role  ?>
    <div>
        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="regular" <?php echo (isset($_GET['role']) && $_GET['role'] === 'regular') ? 'selected' : ''; ?>>Regular</option>
            <option value="superuser" <?php echo (isset($_GET['role']) && $_GET['role'] === 'superuser') ? 'selected' : ''; ?>>Superuser</option>
        </select>
    </div>
    <?php else: ?>
        <input type="hidden" name="role" value="regular">
    <?php endif; ?>
    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=user/showLoginForm">Login here</a>.</p>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
