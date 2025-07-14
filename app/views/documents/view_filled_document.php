<?php // app/views/documents/view_filled_document.php
require_once __DIR__ . '/../layouts/header.php';
$doc = $data['filledDocument'] ?? null;
$fields = $data['fields'] ?? [];
$formData = $data['form_data'] ?? [];
?>
<div class="container mx-auto p-4">
    <?php if ($doc && !empty($fields)): ?>
        <div class="bg-brand-gray p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-start mb-4">
              <div>
                  <h2 class="text-3xl font-bold text-neon-purple mb-1"><?php echo htmlspecialchars($doc['name']); ?></h2>
                  <p class="text-sm text-gray-500">Template: <?php echo htmlspecialchars($doc['template_name']); ?></p>
                  <p class="text-sm text-gray-500">Status: <span class="font-semibold <?php
                        switch (strtolower($doc['status'] ?? '')) {
                            case 'submitted': echo 'text-blue-400'; break;
                            case 'draft': echo 'text-yellow-400'; break;
                            case 'completed': echo 'text-green-400'; break;
                            case 'approved': echo 'text-green-400'; break;
                            case 'rejected': echo 'text-red-400'; break;
                            default: echo 'text-gray-400';
                        }
                    ?>"><?php echo ucfirst(htmlspecialchars($doc['status'])); ?></span></p>
                  <p class="text-sm text-gray-500">Created by: <?php echo htmlspecialchars($doc['created_by_username']); ?> on <?php echo htmlspecialchars(date('M d, Y H:i', strtotime($doc['created_at'])));?></p>
                  <p class="text-sm text-gray-500">Last updated: <?php echo htmlspecialchars(date('M d, Y H:i', strtotime($doc['updated_at'])));?></p>
              </div>
              <div class="space-x-2 whitespace-nowrap">
                  <?php if($doc['user_id'] == ($_SESSION['user_id'] ?? null) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser')): ?>
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/edit/<?php echo $doc['id']; ?>" class="bg-blue-500 text-white hover:bg-blue-700 font-semibold py-2 px-4 rounded transition-colors text-sm"><i class="fas fa-pencil-alt mr-1"></i> Edit</a>
                  <?php endif; ?>
                  <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $doc['id']; ?>" class="bg-green-500 text-white hover:bg-green-700 font-semibold py-2 px-4 rounded transition-colors text-sm"><i class="fas fa-tasks mr-1"></i> View Tasks</a>
                  <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/exportAsJson/<?php echo $doc['id']; ?>" target="_blank" class="bg-gray-600 text-white hover:bg-gray-700 font-semibold py-2 px-4 rounded transition-colors text-sm inline-flex items-center">
                      <i class="fas fa-file-export mr-2"></i> Export as JSON
                  </a>
              </div>
            </div>
            <hr class="border-gray-700 my-6">
            <div class="space-y-6">
                <?php foreach ($fields as $field):
                    $fieldName = $field['name'] ?? uniqid('field_');
                    $fieldLabel = htmlspecialchars($field['label'] ?? ucfirst(str_replace('_', ' ', $fieldName)));
                    $fieldValue = $formData[$fieldName] ?? null;
                    $fieldType = $field['type'] ?? 'text';
                ?>
                    <div>
                        <h4 class="block text-sm font-semibold text-gray-400 mb-1"><?php echo $fieldLabel; ?>:</h4>
                        <div class="p-3 bg-brand-dark rounded text-gray-200 min-h-[40px] prose prose-invert max-w-none"> <!-- prose-invert for markdown if used -->
                            <?php if ($fieldType === 'textarea'): ?>
                                <?php echo nl2br(htmlspecialchars($fieldValue ?? 'N/A')); ?>
                            <?php elseif ($fieldType === 'checkbox'): ?>
                                <?php
                                    if (is_array($fieldValue)) { // For multiple checkboxes
                                        echo !empty($fieldValue) ? htmlspecialchars(implode(', ', array_keys($fieldValue))) : 'No options selected';
                                    } else { // Single checkbox
                                        echo ($fieldValue == '1' || $fieldValue === true) ? 'Yes' : 'No';
                                    }
                                ?>
                            <?php elseif ($fieldType === 'select' && isset($field['options']) && is_array($field['options'])):
                                $displayValue = 'N/A';
                                foreach($field['options'] as $option_key => $option_val) {
                                    $val = is_string($option_key) ? $option_key : $option_val;
                                    $label = $option_val;
                                    if ($fieldValue == $val) {
                                        $displayValue = htmlspecialchars($label);
                                        break;
                                    }
                                }
                                echo $displayValue;
                            ?>
                            <?php else: ?>
                                <?php echo htmlspecialchars($fieldValue ?? 'N/A'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
             <div class="mt-8">
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $doc['accreditation_process_id']; ?>" class="text-neon-purple hover:text-purple-400"><i class="fas fa-arrow-left mr-2"></i> Back to Process Documents</a>
            </div>
        </div>
    <?php else: ?>
        <p class="text-red-400">Error: Filled document data or template definition not found or invalid.</p>
        <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-neon-purple hover:text-purple-400">Go to Processes</a>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
