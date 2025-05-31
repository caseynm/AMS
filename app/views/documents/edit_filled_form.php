<?php // app/views/documents/edit_filled_form.php
require_once __DIR__ . '/../layouts/header.php';
$doc = $data['filledDocument'] ?? null;
$template = ['name' => $doc['template_name'] ?? 'N/A']; // Simplified template data for header
$fields = $data['fields'] ?? [];
$formData = $data['form_data'] ?? [];
?>
<div class="container mx-auto p-4">
    <h2 class="text-3xl font-bold text-neon-purple mb-2">Edit Document: <?php echo htmlspecialchars($doc['name'] ?? 'N/A'); ?></h2>
    <p class="text-gray-400 mb-6">Template: <?php echo htmlspecialchars($template['name']); ?></p>

    <?php if ($doc && !empty($fields)): ?>
    <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/update/<?php echo $doc['id']; ?>" method="POST" class="space-y-6 bg-brand-gray p-6 rounded-lg shadow-lg">
        <div>
            <label for="filled_document_title" class="block text-sm font-medium text-gray-300 mb-1">Document Title:</label>
            <input type="text" id="filled_document_title" name="filled_document_title" value="<?php echo htmlspecialchars($doc['name']); ?>" required class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
        </div>

        <?php foreach ($fields as $field):
            $fieldName = htmlspecialchars($field['name'] ?? uniqid('field_'));
            $fieldLabel = htmlspecialchars($field['label'] ?? ucfirst(str_replace('_', ' ', $fieldName)));
            $fieldType = $field['type'] ?? 'text';
            $fieldRequired = isset($field['required']) && $field['required'] ? 'required' : '';
            $fieldPlaceholder = htmlspecialchars($field['placeholder'] ?? '');
            $fieldValue = $formData[$field['name']] ?? '';
        ?>
            <div>
                <label for="<?php echo $fieldName; ?>" class="block text-sm font-medium text-gray-300 mb-1"><?php echo $fieldLabel; ?><?php if($fieldRequired) echo '<span class="text-red-500">*</span>'; ?></label>
                <?php if ($fieldType === 'textarea'): ?>
                    <textarea id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" rows="5" placeholder="<?php echo $fieldPlaceholder; ?>" <?php echo $fieldRequired; ?> class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors"><?php echo htmlspecialchars($fieldValue); ?></textarea>
                <?php elseif ($fieldType === 'select' && isset($field['options']) && is_array($field['options'])): ?>
                    <select id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" <?php echo $fieldRequired; ?> class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
                        <option value="">Select an option</option>
                        <?php foreach($field['options'] as $option_key => $option_val):
                             $val = is_string($option_key) ? $option_key : $option_val;
                             $label = $option_val;
                        ?>
                            <option value="<?php echo htmlspecialchars($val); ?>" <?php if ($fieldValue == $val) echo 'selected'; ?>><?php echo htmlspecialchars($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php elseif ($fieldType === 'checkbox'): ?>
                     <?php if (isset($field['options']) && is_array($field['options'])): // Multiple checkboxes ?>
                        <div class="space-y-2 mt-1">
                        <?php foreach($field['options'] as $option_key => $option_val):
                            $val = is_string($option_key) ? $option_key : $option_val;
                            $label = $option_val;
                            $checkboxName = $fieldName . '[' . htmlspecialchars($val) . ']';
                            $isChecked = isset($fieldValue[$val]);
                        ?>
                            <label class="flex items-center text-gray-300">
                                <input type="checkbox" name="<?php echo $checkboxName; ?>" value="<?php echo htmlspecialchars($val); ?>" <?php if ($isChecked) echo 'checked'; ?> class="h-4 w-4 bg-brand-dark border-gray-600 text-neon-purple focus:ring-neon-purple rounded mr-2">
                                <?php echo htmlspecialchars($label); ?>
                            </label>
                        <?php endforeach; ?>
                        </div>
                    <?php else: // Single checkbox ?>
                        <label class="flex items-center text-gray-300">
                            <input type="checkbox" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" value="1" <?php if ($fieldValue == '1' || $fieldValue === true) echo 'checked'; ?> class="h-4 w-4 bg-brand-dark border-gray-600 text-neon-purple focus:ring-neon-purple rounded mr-2">
                            <span class="ml-2"><?php echo $fieldPlaceholder ?: ($fieldLabel !== 'Yes' ? $fieldLabel : 'Yes'); ?></span>
                        </label>
                    <?php endif; ?>
                <?php else: // text, date, number, email, etc. ?>
                    <input type="<?php echo htmlspecialchars($fieldType); ?>" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" value="<?php echo htmlspecialchars($fieldValue); ?>" placeholder="<?php echo $fieldPlaceholder; ?>" <?php echo $fieldRequired; ?> class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-300 mb-1">Status:</label>
                <select id="status" name="status" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
                    <option value="draft" <?php if ($doc['status'] === 'draft') echo 'selected'; ?>>Draft</option>
                    <option value="submitted" <?php if ($doc['status'] === 'submitted') echo 'selected'; ?>>Submitted</option>
                    <option value="completed" <?php if ($doc['status'] === 'completed') echo 'selected'; ?>>Completed</option>
                    <option value="approved" <?php if ($doc['status'] === 'approved') echo 'selected'; ?>>Approved</option>
                    <option value="rejected" <?php if ($doc['status'] === 'rejected') echo 'selected'; ?>>Rejected</option>
                </select>
            </div>
        <div>
            <button type="submit" class="bg-neon-purple text-white hover:bg-purple-700 font-bold py-3 px-6 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-save mr-2"></i> Update Document</button>
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/view/<?php echo $doc['id']; ?>" class="ml-4 text-gray-400 hover:text-gray-200">Cancel</a>
        </div>
    </form>
    <?php else: ?>
        <p class="text-red-500">Error: Document data not found or template definition invalid.</p>
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-neon-purple hover:text-purple-400">Go to Processes</a>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
