<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-black">User List</h2>
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>user/showRegistrationForm" class="inline-block bg-black text-white hover:bg-gray-800 font-bold py-2 px-4 rounded"><i class="fas fa-user-plus mr-2"></i> Add New User</a>
        <?php endif; ?>
    </div>

    <?php if (isset($data['users']) && !empty($data['users'])): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-black rounded-lg shadow-md">
                <thead class="bg-black text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Role</th>
                        <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th> -->
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($data['users'] as $user_item): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black border-b border-gray-200"><?php echo htmlspecialchars($user_item['name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black border-b border-gray-200"><?php echo htmlspecialchars($user_item['email']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black border-b border-gray-200"><?php echo htmlspecialchars(ucfirst($user_item['role'])); ?></td>
                            <!-- Future actions could go here, e.g., edit user link -->
                            <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-black border-b border-gray-200">
                                <a href="#" class="text-black hover:underline">Edit</a>
                            </td> -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-black">No users found.</p>
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'superuser'): ?>
             <p class="text-black mt-4">User list is only available to superusers.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
