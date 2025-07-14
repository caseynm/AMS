<?php // app/views/document_templates/index.php
require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-neon-purple">Document Templates</h2>
        <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/showCreateForm" class="bg-neon-purple text-white hover:bg-purple-700 font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i> Create New Template
        </a>
    </div>

    <?php if (empty($data['templates'])): ?>
        <p class="text-gray-400">No document templates found. Create one to get started!</p>
    <?php else: ?>
        <div class="overflow-x-auto bg-brand-gray shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-brand-dark">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach ($data['templates'] as $template): ?>
                        <tr class="hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-200"><?php echo htmlspecialchars($template['name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400"><?php echo htmlspecialchars(substr($template['description'] ?? '', 0, 50)) . (strlen($template['description'] ?? '') > 50 ? '...' : ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400"><?php echo htmlspecialchars($template['created_by_username'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400"><?php echo htmlspecialchars(date('M d, Y H:i', strtotime($template['updated_at']))); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/showEditForm/<?php echo $template['id']; ?>" class="text-blue-400 hover:text-blue-600"><i class="fas fa-pencil-alt mr-1"></i> Edit</a>
                                <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/delete/<?php echo $template['id']; ?>" data-message="Are you sure you want to delete the template '<?php echo htmlspecialchars($template['name']); ?>'? This cannot be undone AND might fail if documents are using it." class="delete-confirm-link text-red-500 hover:text-red-700"><i class="fas fa-trash-alt mr-1"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
