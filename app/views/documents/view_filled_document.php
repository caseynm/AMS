<?php // app/views/documents/view_filled_document.php
require_once __DIR__ . '/../layouts/header.php';
$doc = $data['filledDocument'] ?? null;
$fields = $data['fields'] ?? [];
$formData = $data['form_data'] ?? [];
?>
<div class="container mx-auto px-4 py-8">
    <?php if ($doc && !empty($fields)): ?>
        <div class="bg-white p-6 rounded-lg shadow-md border border-black">
            <div class="flex flex-col sm:flex-row justify-between items-start mb-4">
              <div class="mb-4 sm:mb-0">
                  <h2 class="text-2xl font-bold text-black mb-1"><?php echo htmlspecialchars($doc['name']); ?></h2>
                  <p class="text-sm text-gray-600">Template: <?php echo htmlspecialchars($doc['template_name']); ?></p>
                  <p class="text-sm text-gray-600">Status: <span class="font-semibold px-1 rounded-full text-xs <?php
                        switch (strtolower($doc['status'] ?? '')) {
                            case 'submitted': echo 'bg-blue-100 text-blue-800 border border-blue-400'; break;
                            case 'draft': echo 'bg-yellow-100 text-yellow-800 border border-yellow-400'; break;
                            case 'completed': echo 'bg-green-100 text-green-800 border border-green-400'; break; // Assuming completed is like approved
                            case 'approved': echo 'bg-green-100 text-green-800 border border-green-400'; break;
                            case 'rejected': echo 'bg-red-100 text-red-800 border border-red-400'; break;
                            default: echo 'bg-gray-100 text-gray-800 border border-gray-400';
                        }
                    ?>"><?php echo ucfirst(htmlspecialchars($doc['status'])); ?></span></p>
                  <p class="text-sm text-gray-600">Created by: <?php echo htmlspecialchars($doc['created_by_username']); ?> on <?php echo htmlspecialchars(date('M d, Y H:i', strtotime($doc['created_at'])));?></p>
                  <p class="text-sm text-gray-600">Last updated: <?php echo htmlspecialchars(date('M d, Y H:i', strtotime($doc['updated_at'])));?></p>
              </div>
              <div class="flex space-x-2 whitespace-nowrap">
                  <?php if($doc['user_id'] == ($_SESSION['user_id'] ?? null) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser')): ?>
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/edit/<?php echo $doc['id']; ?>" class="bg-black text-white px-3 py-2 rounded hover:bg-gray-800 text-sm"><i class="fas fa-pencil-alt mr-1"></i> Edit</a>
                  <?php endif; ?>
                  <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $doc['id']; ?>" class="bg-black text-white px-3 py-2 rounded hover:bg-gray-800 text-sm"><i class="fas fa-tasks mr-1"></i> View Tasks</a>
                  <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/exportAsJson/<?php echo $doc['id']; ?>" target="_blank" class="bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 text-sm inline-flex items-center">
                      <i class="fas fa-file-export mr-2"></i> Export JSON
                  </a>
              </div>
            </div>
            <hr class="border-black my-6">
            <div class="space-y-6">
                <?php foreach ($fields as $field):
                    $fieldName = $field['name'] ?? uniqid('field_');
                    $fieldLabel = htmlspecialchars($field['label'] ?? ucfirst(str_replace('_', ' ', $fieldName)));
                    $fieldValue = $formData[$fieldName] ?? null;
                    $fieldType = $field['type'] ?? 'text';
                ?>
                    <div>
                        <p class="block text-sm font-semibold text-black mb-1"><?php echo $fieldLabel; ?>:</p>
                        <div class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm min-h-[40px] text-black">
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
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $doc['accreditation_process_id']; ?>" class="text-black hover:underline"><i class="fas fa-arrow-left mr-2"></i> Back to Process Documents</a>
            </div>
        </div>
    <?php else: ?>
        <p class="text-red-600 text-center">Error: Filled document data or template definition not found or invalid.</p>
        <p class="text-center mt-2"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-black hover:underline">Go to Processes</a></p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
