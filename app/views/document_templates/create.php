<?php // app/views/document_templates/create.php
require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-black mb-6">Create New Document Template</h2>
    <div class="bg-white p-6 rounded-lg shadow-md border border-black">
        <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/create" method="POST" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-bold text-black mb-1">Template Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>" required class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
            </div>
            <div>
                <label for="description" class="block text-sm font-bold text-black mb-1">Description:</label>
                <textarea id="description" name="description" rows="3" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black"><?php echo htmlspecialchars($data['description'] ?? ''); ?></textarea>
            </div>
            <div>
                <label for="fields_definition" class="block text-sm font-bold text-black mb-1">Fields Definition (JSON):</label>
                <textarea id="fields_definition" name="fields_definition" rows="10" required class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black font-mono text-sm"><?php echo htmlspecialchars($data['fields_definition'] ?? '[{"name": "field_name", "label": "Field Label", "type": "text", "required": false, "placeholder": "Enter value"}, {"name": "another_field", "label": "Another Field", "type": "textarea"}]'); ?></textarea>
                <p class="text-xs text-gray-600 mt-1">Enter a JSON array defining the fields. E.g., `[{"name": "title", "label": "Title", "type": "text"}, {"name": "content", "label": "Content", "type": "textarea"}]`</p>
                <p class="text-xs text-gray-600 mt-1">Supported types: `text`, `textarea`, `date`, `number`, `checkbox`, `select` (provide `options` array for select).</p>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline"><i class="fas fa-save mr-2"></i> Create Template</button>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>documenttemplate/index" class="text-black hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
