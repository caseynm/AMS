<?php // app/views/documents/select_template.php
require_once __DIR__ . '/../layouts/header.php';
$process = $data['process'] ?? null;
?>
<div class="container mx-auto p-4">
    <h2 class="text-3xl font-bold text-neon-purple mb-6">Select a Template for Process: <?php echo htmlspecialchars($process['title'] ?? 'N/A'); ?></h2>
    <?php if (empty($data['templates'])): ?>
        <p class="text-gray-400">No document templates available. A superuser needs to create them first.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($data['templates'] as $template): ?>
                <div class="bg-brand-gray p-6 rounded-lg shadow-lg hover:shadow-neon-purple/30 transition-shadow duration-300">
                    <h3 class="text-xl font-semibold text-neon-purple mb-2"><?php echo htmlspecialchars($template['name']); ?></h3>
                    <p class="text-gray-400 mb-4 text-sm"><?php echo htmlspecialchars($template['description'] ?? 'No description.'); ?></p>
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/fill/<?php echo $process['id']; ?>/<?php echo $template['id']; ?>" class="bg-blue-500 text-white hover:bg-blue-700 font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out transform hover:scale-105">
                        <i class="fas fa-file-alt mr-2"></i> Use this template
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="mt-8">
      <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $process['id']; ?>" class="text-gray-400 hover:text-gray-200"><i class="fas fa-arrow-left mr-2"></i> Back to Process</a>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
