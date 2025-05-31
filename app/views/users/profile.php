<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-black mb-6 text-center">User Profile</h2>
    <?php if (isset($data['user'])): ?>
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 border border-black w-full max-w-lg mx-auto">
            <div class="mb-4">
                <p class="text-sm font-semibold text-black mb-1">Name:</p>
                <p class="text-lg text-black"><?php echo htmlspecialchars($data['user']['name']); ?></p>
            </div>
            <div class="mb-4">
                <p class="text-sm font-semibold text-black mb-1">Email:</p>
                <p class="text-lg text-black"><?php echo htmlspecialchars($data['user']['email']); ?></p>
            </div>
            <div class="mb-4">
                <p class="text-sm font-semibold text-black mb-1">Role:</p>
                <p class="text-lg text-black"><?php echo htmlspecialchars(ucfirst($data['user']['role'])); ?></p>
            </div>
            <!-- Add other profile information or actions here -->
        </div>
    <?php else: ?>
        <p class="text-black text-center">User information not found.</p>
    <?php endif; ?>
    <p class="text-center mt-6">
        <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/logout" class="text-black hover:underline">Logout</a>
    </p>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
