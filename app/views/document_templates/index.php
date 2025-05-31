<?php // app/views/document_templates/index.php
require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-black">Document Templates</h2>
        <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/showCreateForm" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline">
            <i class="fas fa-plus mr-2"></i> Create New Template
        </a>
    </div>

    <?php if (empty($data['templates'])): ?>
        <p class="text-black">No document templates found. Create one to get started!</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-black rounded-lg shadow-md">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($data['templates'] as $template): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-black"><?php echo htmlspecialchars($template['name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black"><?php echo htmlspecialchars(substr($template['description'] ?? '', 0, 50)) . (strlen($template['description'] ?? '') > 50 ? '...' : ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black"><?php echo htmlspecialchars($template['created_by_username'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black"><?php echo htmlspecialchars(date('M d, Y H:i', strtotime($template['updated_at']))); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/showEditForm/<?php echo $template['id']; ?>" class="text-black hover:underline"><i class="fas fa-pencil-alt mr-1"></i> Edit</a>
                                <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/delete/<?php echo $template['id']; ?>" data-message="Are you sure you want to delete the template '<?php echo htmlspecialchars($template['name']); ?>'? This cannot be undone AND might fail if documents are using it." class="delete-confirm-link text-red-600 hover:text-red-800 underline"><i class="fas fa-trash-alt mr-1"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
