<?php // app/views/documents/fill_form.php
require_once __DIR__ . '/../layouts/header.php';
$template = $data['template'] ?? null;
$process = $data['process'] ?? null;
$fields = $data['fields'] ?? [];
?>
<div class="container mx-auto p-4">
    <h2 class="text-3xl font-bold text-neon-purple mb-2">Fill Document: <?php echo htmlspecialchars($template['name'] ?? 'N/A'); ?></h2>
    <p class="text-gray-400 mb-6">For Process: <?php echo htmlspecialchars($process['title'] ?? 'N/A'); ?></p>

    <?php if ($template && $process && !empty($fields)): ?>
    <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/save/<?php echo $process['id']; ?>/<?php echo $template['id']; ?>" method="POST" class="space-y-6 bg-brand-gray p-6 rounded-lg shadow-lg">
        <div>
            <label for="filled_document_title" class="block text-sm font-medium text-gray-300 mb-1">Document Title:</label>
            <input type="text" id="filled_document_title" name="filled_document_title" value="<?php echo htmlspecialchars($template['name'] . ' - ' . date('Y-m-d')); ?>" required class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
        </div>

        <?php foreach ($fields as $field):
            $fieldName = htmlspecialchars($field['name'] ?? uniqid('field_'));
            $fieldLabel = htmlspecialchars($field['label'] ?? ucfirst(str_replace('_', ' ', $fieldName)));
            $fieldType = $field['type'] ?? 'text';
            $fieldRequired = isset($field['required']) && $field['required'] ? 'required' : '';
            $fieldPlaceholder = htmlspecialchars($field['placeholder'] ?? '');
            $fieldValue = ''; // For create form, value is empty
        ?>
            <div>
                <label for="<?php echo $fieldName; ?>" class="block text-sm font-medium text-gray-300 mb-1"><?php echo $fieldLabel; ?><?php if($fieldRequired) echo '<span class="text-red-500">*</span>'; ?></label>
                <?php if ($fieldType === 'textarea'): ?>
                    <textarea id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" rows="5" placeholder="<?php echo $fieldPlaceholder; ?>" <?php echo $fieldRequired; ?> class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors"><?php echo $fieldValue; ?></textarea>
                <?php elseif ($fieldType === 'select' && isset($field['options']) && is_array($field['options'])): ?>
                    <select id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" <?php echo $fieldRequired; ?> class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
                        <option value="">Select an option</option>
                        <?php foreach($field['options'] as $option_key => $option_val):  // Allow for key => value or just value
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
                            $checkboxName = $fieldName . '[' . htmlspecialchars($val) . ']'; // For array of checkboxes
                        ?>
                            <label class="flex items-center text-gray-300">
                                <input type="checkbox" name="<?php echo $checkboxName; ?>" value="<?php echo htmlspecialchars($val); ?>" class="h-4 w-4 bg-brand-dark border-gray-600 text-neon-purple focus:ring-neon-purple rounded mr-2">
                                <?php echo htmlspecialchars($label); ?>
                            </label>
                        <?php endforeach; ?>
                        </div>
                    <?php else: // Single checkbox ?>
                         <label class="flex items-center text-gray-300">
                            <input type="checkbox" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" value="1" <?php if ($fieldValue == '1') echo 'checked'; ?> class="h-4 w-4 bg-brand-dark border-gray-600 text-neon-purple focus:ring-neon-purple rounded mr-2">
                            <span class="ml-2"><?php echo $fieldPlaceholder ?: 'Yes'; ?></span>
                        </label>
                    <?php endif; ?>
                <?php else: // text, date, number, email, etc. ?>
                    <input type="<?php echo htmlspecialchars($fieldType); ?>" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" value="<?php echo $fieldValue; ?>" placeholder="<?php echo $fieldPlaceholder; ?>" <?php echo $fieldRequired; ?> class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <div>
            <button type="submit" class="bg-neon-purple text-white hover:bg-purple-700 font-bold py-3 px-6 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-save mr-2"></i> Save Document</button>
            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/selectTemplate/<?php echo $process['id']; ?>" class="ml-4 text-gray-400 hover:text-gray-200">Cancel / Change Template</a>
        </div>
    </form>
    <?php else: ?>
        <p class="text-red-500">Error: Template or process information is missing, or template definition is invalid.</p>
        <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-neon-purple hover:text-purple-400">Go to Processes</a>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
