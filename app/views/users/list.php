<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>User List</h2>
<?php if (isset($data['users']) && !empty($data['users'])): ?>
    <ul>
        <?php foreach ($data['users'] as $user_item): ?>
            <li>
                <?php echo htmlspecialchars($user_item['name']); ?>
                (<?php echo htmlspecialchars($user_item['email']); ?>) -
                Role: <?php echo htmlspecialchars($user_item['role']); ?>
                <!-- Add edit/delete links here if needed later and if superuser -->
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No users found.</p>
<?php endif; ?>
<?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
    <p><a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=user/showRegistrationForm">Add New User</a></p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
