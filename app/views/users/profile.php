<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="bg-brand-gray p-6 md:p-8 rounded-lg shadow-xl max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-neon-purple mb-6 text-center">User Profile</h2>
    <?php if (isset($data['user'])): ?>
        <div class="space-y-4">
            <div>
                <strong class="block text-sm font-medium text-gray-400">Name:</strong>
                <p class="mt-1 text-lg text-gray-200 p-3 bg-brand-dark rounded-md"><?php echo htmlspecialchars($data['user']['name']); ?></p>
            </div>
            <div>
                <strong class="block text-sm font-medium text-gray-400">Email:</strong>
                <p class="mt-1 text-lg text-gray-200 p-3 bg-brand-dark rounded-md"><?php echo htmlspecialchars($data['user']['email']); ?></p>
            </div>
            <div>
                <strong class="block text-sm font-medium text-gray-400">Role:</strong>
                <p class="mt-1 text-lg text-gray-200 p-3 bg-brand-dark rounded-md"><?php echo htmlspecialchars(ucfirst($data['user']['role'])); ?></p>
            </div>
        </div>
    <?php else: ?>
        <p class="text-red-400 text-center">User information not found.</p>
    <?php endif; ?>
    <div class="mt-8 text-center">
        <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/logout" class="inline-block bg-red-600 text-white hover:bg-red-700 font-bold py-2 px-4 rounded transition-colors duration-300"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
