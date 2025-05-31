<?php // app/views/document_templates/create.php
require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto p-4">
    <h2 class="text-3xl font-bold text-neon-purple mb-6">Create New Document Template</h2>
    <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/create" method="POST" class="space-y-6 bg-brand-gray p-6 rounded-lg shadow-lg">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Template Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>" required class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-300 mb-1">Description:</label>
            <textarea id="description" name="description" rows="3" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors"><?php echo htmlspecialchars($data['description'] ?? ''); ?></textarea>
        </div>
        <div>
            <label for="fields_definition" class="block text-sm font-medium text-gray-300 mb-1">Fields Definition (JSON):</label>
            <textarea id="fields_definition" name="fields_definition" rows="10" required class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded font-mono text-sm focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors"><?php echo htmlspecialchars($data['fields_definition'] ?? '[{"name": "field_name", "label": "Field Label", "type": "text", "required": false, "placeholder": "Enter value"}, {"name": "another_field", "label": "Another Field", "type": "textarea"}]'); ?></textarea>
            <p class="text-xs text-gray-500 mt-1">Enter a JSON array defining the fields. E.g., `[{"name": "title", "label": "Title", "type": "text"}, {"name": "content", "label": "Content", "type": "textarea"}]`</p>
            <p class="text-xs text-gray-500 mt-1">Supported types: `text`, `textarea`, `date`, `number`, `checkbox`, `select` (provide `options` array for select).</p>
        </div>
        <div>
            <button type="submit" class="bg-neon-purple text-white hover:bg-purple-700 font-bold py-3 px-6 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-save mr-2"></i> Create Template</button>
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/index" class="ml-4 text-gray-400 hover:text-gray-200">Cancel</a>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
