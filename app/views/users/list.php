<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2 class="text-3xl font-bold text-neon-purple mb-6">User List</h2>
<?php if (isset($data['users']) && !empty($data['users'])): ?>
    <ul class="space-y-3">
        <?php foreach ($data['users'] as $user_item): ?>
            <li class="bg-brand-dark p-4 rounded-lg shadow">
                <p class="text-lg font-semibold text-gray-200"><?php echo htmlspecialchars($user_item['name']); ?></p>
                <p class="text-sm text-gray-400"><?php echo htmlspecialchars($user_item['email']); ?></p>
                <p class="text-sm text-gray-500 mt-1">Role: <span class="font-medium text-gray-300"><?php echo htmlspecialchars(ucfirst($user_item['role'])); ?></span></p>
                <!-- Add edit/delete links here if needed later and if superuser -->
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="text-gray-400">No users found.</p>
<?php endif; ?>
<?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
    <p class="mt-6"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showRegistrationForm" class="inline-block bg-neon-purple text-white hover:bg-purple-700 font-bold py-2 px-4 rounded transition-colors duration-300"><i class="fas fa-user-plus mr-2"></i> Add New User</a></p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
