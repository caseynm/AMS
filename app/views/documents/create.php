<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php if (isset($data['process_id']) && isset($data['process_title'])): ?>
    <h2 class="text-2xl font-semibold text-neon-purple mb-6">Add New Document to Process: <?php echo htmlspecialchars($data['process_title']); ?></h2>
    <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/create/<?php echo $data['process_id']; ?>" method="POST" class="space-y-6 bg-brand-gray p-6 rounded-lg shadow-lg">
        <div>
            <label for="name" class="block text-gray-300 mb-1 font-semibold">Document Name:</label>
            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
        </div>
        <div>
            <label for="onedrive_url" class="block text-gray-300 mb-1 font-semibold">OneDrive URL:</label>
            <input type="url" id="onedrive_url" name="onedrive_url" placeholder="https://example.onedrive.com/..." value="<?php echo htmlspecialchars($_GET['onedrive_url'] ?? ''); ?>" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
        </div>
        <div>
            <label for="status" class="block text-gray-300 mb-1 font-semibold">Status:</label>
            <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($_GET['status'] ?? 'pending'); ?>" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
        </div>
    <button type="submit" class="w-full md:w-auto bg-neon-purple text-white hover:bg-purple-700 font-bold py-3 px-6 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-plus mr-2"></i> Add Document</button>
    </form>
<p class="mt-6"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $data['process_id']; ?>" class="text-neon-purple hover:text-purple-400"><i class="fas fa-arrow-left mr-2"></i>Back to Process Details</a></p>
<?php else: ?>
    <p class="text-red-400">Process information is missing. Cannot add document.</p>
    <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index">Go to Accreditation Processes</a></p>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
