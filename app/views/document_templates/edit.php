<?php // app/views/document_templates/edit.php
require_once __DIR__ . '/../layouts/header.php';
$template = $data['template'] ?? null;
?>
<div class="container mx-auto p-4">
    <h2 class="text-3xl font-bold text-neon-purple mb-6">Edit Document Template: <?php echo htmlspecialchars($template['name'] ?? 'N/A'); ?></h2>
    <?php if ($template): ?>
    <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/update/<?php echo $template['id']; ?>" method="POST" class="space-y-6 bg-brand-gray p-6 rounded-lg shadow-lg">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Template Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars(isset($_GET['name']) ? $_GET['name'] : $template['name']); ?>" required class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-300 mb-1">Description:</label>
            <textarea id="description" name="description" rows="3" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors"><?php echo htmlspecialchars(isset($_GET['description']) ? $_GET['description'] : $template['description']); ?></textarea>
        </div>
        <div>
            <label for="fields_definition" class="block text-sm font-medium text-gray-300 mb-1">Fields Definition (JSON):</label>
            <textarea id="fields_definition" name="fields_definition" rows="10" required class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded font-mono text-sm focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors"><?php echo htmlspecialchars(isset($_GET['fields_definition']) ? $_GET['fields_definition'] : $template['fields_definition']); ?></textarea>
            <p class="text-xs text-gray-500 mt-1">Edit the JSON array defining the fields. Ensure valid JSON format.</p>
        </div>
        <div>
            <button type="submit" class="bg-neon-purple text-white hover:bg-purple-700 font-bold py-3 px-6 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-save mr-2"></i> Update Template</button>
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/index" class="ml-4 text-gray-400 hover:text-gray-200">Cancel</a>
        </div>
    </form>
    <?php else: ?>
        <p class="text-red-500">Template data not found.</p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
