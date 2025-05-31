<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-black">Accreditation Processes</h2>
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/showCreateForm" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline"><i class="fas fa-plus mr-2"></i> Create New Process</a>
        <?php endif; ?>
    </div>

    <?php if (isset($data['processes']) && !empty($data['processes'])): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-black rounded-lg shadow-md">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($data['processes'] as $process): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $process['id']; ?>" class="text-black hover:underline font-semibold"><?php echo htmlspecialchars($process['title']); ?></a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                <span class="font-medium px-2 py-0.5 rounded-full text-xs
                                <?php
                                    switch (strtolower($process['status'] ?? '')) {
                                        case 'active': echo 'bg-green-100 text-green-800 border border-green-400'; break;
                                        case 'pending': echo 'bg-yellow-100 text-yellow-800 border border-yellow-400'; break;
                                        case 'completed': echo 'bg-blue-100 text-blue-800 border border-blue-400'; break;
                                        case 'archived': echo 'bg-gray-100 text-gray-800 border border-gray-400'; break;
                                        default: echo 'bg-gray-200 text-gray-800 border border-gray-400';
                                    }
                                ?>">
                                <?php echo htmlspecialchars(ucfirst($process['status'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black"><?php echo htmlspecialchars($process['start_date'] ? date('M d, Y', strtotime($process['start_date'])) : 'N/A'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black"><?php echo htmlspecialchars($process['end_date'] ? date('M d, Y', strtotime($process['end_date'])) : 'N/A'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black"><?php echo htmlspecialchars($process['created_by_name'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black space-x-2">
                                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $process['id']; ?>" class="text-black hover:underline">View</a>
                                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/showEditForm/<?php echo $process['id']; ?>" class="text-black hover:underline">Edit</a>
                                    <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/delete/<?php echo $process['id']; ?>" data-message="Are you sure you want to delete this process and ALL related data (documents, tasks, comments)? This action cannot be undone." class="delete-confirm-link text-red-600 hover:text-red-800 underline">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-black">No accreditation processes found.</p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
