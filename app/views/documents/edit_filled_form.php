<?php // app/views/documents/edit_filled_form.php
require_once __DIR__ . '/../layouts/header.php';
$doc = $data['filledDocument'] ?? null;
$template = ['name' => $doc['template_name'] ?? 'N/A']; // Simplified template data for header
$fields = $data['fields'] ?? [];
$formData = $data['form_data'] ?? [];
?>
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-black mb-2">Edit Document: <?php echo htmlspecialchars($doc['name'] ?? 'N/A'); ?></h2>
    <p class="text-sm text-black mb-6">Template: <?php echo htmlspecialchars($template['name']); ?></p>

    <?php if ($doc && !empty($fields)): ?>
    <div class="bg-white p-6 rounded-lg shadow-md border border-black">
        <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/update/<?php echo $doc['id']; ?>" method="POST" class="space-y-6">
            <div>
                <label for="filled_document_title" class="block text-sm font-bold text-black mb-1">Document Title:</label>
                <input type="text" id="filled_document_title" name="filled_document_title" value="<?php echo htmlspecialchars($doc['name']); ?>" required class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
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
                    <label for="<?php echo $fieldName; ?>" class="block text-sm font-bold text-black mb-1"><?php echo $fieldLabel; ?><?php if($fieldRequired) echo '<span class="text-red-500 ml-1">*</span>'; ?></label>
                    <?php if ($fieldType === 'textarea'): ?>
                        <textarea id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" rows="5" placeholder="<?php echo $fieldPlaceholder; ?>" <?php echo $fieldRequired; ?> class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black"><?php echo htmlspecialchars($fieldValue); ?></textarea>
                    <?php elseif ($fieldType === 'select' && isset($field['options']) && is_array($field['options'])): ?>
                        <select id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" <?php echo $fieldRequired; ?> class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
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
                            <div class="space-y-2 mt-2">
                            <?php foreach($field['options'] as $option_key => $option_val):
                                $val = is_string($option_key) ? $option_key : $option_val;
                                $label = $option_val;
                                $checkboxName = $fieldName . '[' . htmlspecialchars($val) . ']';
                                $isChecked = isset($fieldValue[$val]);
                            ?>
                                <label class="flex items-center text-sm text-black">
                                    <input type="checkbox" name="<?php echo $checkboxName; ?>" value="<?php echo htmlspecialchars($val); ?>" <?php if ($isChecked) echo 'checked'; ?> class="h-4 w-4 bg-white border-black text-blue-600 focus:ring-blue-500 rounded mr-2">
                                    <?php echo htmlspecialchars($label); ?>
                                </label>
                            <?php endforeach; ?>
                            </div>
                        <?php else: // Single checkbox ?>
                            <label class="flex items-center text-sm text-black mt-2">
                                <input type="checkbox" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" value="1" <?php if ($fieldValue == '1' || $fieldValue === true) echo 'checked'; ?> class="h-4 w-4 bg-white border-black text-blue-600 focus:ring-blue-500 rounded mr-2">
                                <span class="ml-2"><?php echo $fieldPlaceholder ?: ($fieldLabel !== 'Yes' ? $fieldLabel : 'Yes'); ?></span>
                            </label>
                        <?php endif; ?>
                    <?php else: // text, date, number, email, etc. ?>
                        <input type="<?php echo htmlspecialchars($fieldType); ?>" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" value="<?php echo htmlspecialchars($fieldValue); ?>" placeholder="<?php echo $fieldPlaceholder; ?>" <?php echo $fieldRequired; ?> class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <div>
                <label for="status" class="block text-sm font-bold text-black mb-1">Status:</label>
                <select id="status" name="status" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                    <option value="draft" <?php if ($doc['status'] === 'draft') echo 'selected'; ?>>Draft</option>
                    <option value="submitted" <?php if ($doc['status'] === 'submitted') echo 'selected'; ?>>Submitted</option>
                    <option value="completed" <?php if ($doc['status'] === 'completed') echo 'selected'; ?>>Completed</option>
                    <option value="approved" <?php if ($doc['status'] === 'approved') echo 'selected'; ?>>Approved</option>
                    <option value="rejected" <?php if ($doc['status'] === 'rejected') echo 'selected'; ?>>Rejected</option>
                </select>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline"><i class="fas fa-save mr-2"></i> Update Document</button>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/view/<?php echo $doc['id']; ?>" class="text-black hover:underline">Cancel</a>
            </div>
        </form>
    </div>
    <?php else: ?>
        <p class="text-red-600 text-center">Error: Document data not found or template definition invalid.</p>
        <p class="text-center mt-2"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-black hover:underline">Go to Processes</a></p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
