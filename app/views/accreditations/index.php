<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800">All Accreditation Processes</h2>
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
        <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/showCreateForm" class="bg-blue-600 text-white hover:bg-blue-700 font-bold py-2 px-4 rounded transition-colors duration-300"><i class="fas fa-plus mr-2"></i> Create New Process</a>
    <?php endif; ?>
</div>

<?php if (isset($data['processes']) && !empty($data['processes'])): ?>
    <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200">
    <table class="min-w-full table-auto divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($data['processes'] as $process): ?>
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $process['id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium"><?php echo htmlspecialchars($process['title']); ?></a></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                        <?php
                            switch (strtolower($process['status'] ?? '')) {
                                case 'active': echo 'bg-green-100 text-green-800'; break;
                                case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                case 'completed': echo 'bg-blue-100 text-blue-800'; break;
                                case 'archived': echo 'bg-gray-100 text-gray-800'; break;
                                default: echo 'bg-gray-200 text-gray-700';
                            }
                        ?>">
                            <?php echo htmlspecialchars(ucfirst($process['status'])); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($process['start_date'] ?? 'N/A'); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($process['end_date'] ?? 'N/A'); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($process['created_by_name'] ?? 'N/A'); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $process['id']; ?>" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye mr-1"></i> View</a>
                        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/showEditForm/<?php echo $process['id']; ?>" class="text-yellow-500 hover:text-yellow-700 ml-4"><i class="fas fa-pencil-alt mr-1"></i> Edit</a>
                            <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/delete/<?php echo $process['id']; ?>" data-message="Are you sure you want to delete this process and ALL related data (documents, tasks, comments)? This action cannot be undone." class="delete-confirm-link text-red-600 hover:text-red-800 ml-4"><i class="fas fa-trash-alt mr-1"></i> Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
<?php else: ?>
    <p class="text-gray-600">No accreditation processes found.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
