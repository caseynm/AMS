<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>User Profile</h2>
<?php if (isset($data['user'])): ?>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($data['user']['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($data['user']['email']); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars($data['user']['role']); ?></p>
<?php else: ?>
    <p>User information not found.</p>
<?php endif; ?>
<p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/logout">Logout</a></p>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
