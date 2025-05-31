<?php // app/views/documents/select_template.php
require_once __DIR__ . '/../layouts/header.php';
$process = $data['process'] ?? null;
?>
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-black mb-6">Select a Template for Process: <?php echo htmlspecialchars($process['title'] ?? 'N/A'); ?></h2>
    <?php if (empty($data['templates'])): ?>
        <p class="text-black">No document templates available. A superuser needs to create them first.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($data['templates'] as $template): ?>
                <div class="bg-white border border-black p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-black mb-2"><?php echo htmlspecialchars($template['name']); ?></h3>
                        <p class="text-black text-sm mb-4"><?php echo htmlspecialchars($template['description'] ?? 'No description.'); ?></p>
                    </div>
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/fill/<?php echo $process['id']; ?>/<?php echo $template['id']; ?>" class="self-start bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline">
                        <i class="fas fa-file-alt mr-2"></i> Use this template
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="mt-8">
      <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $process['id']; ?>" class="text-black hover:underline"><i class="fas fa-arrow-left mr-2"></i> Back to Process</a>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
