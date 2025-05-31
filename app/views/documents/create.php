<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <?php if (isset($data['process_id']) && isset($data['process_title'])): ?>
        <h2 class="text-2xl font-bold text-black mb-6">Add New Document to Process: <?php echo htmlspecialchars($data['process_title']); ?></h2>
        <div class="bg-white p-6 rounded-lg shadow-md border border-black">
            <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/create/<?php echo $data['process_id']; ?>" method="POST" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-bold text-black mb-1">Document Name:</label>
                    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                </div>
                <div>
                    <label for="onedrive_url" class="block text-sm font-bold text-black mb-1">OneDrive URL:</label>
                    <input type="url" id="onedrive_url" name="onedrive_url" placeholder="https://example.onedrive.com/..." value="<?php echo htmlspecialchars($_GET['onedrive_url'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                </div>
                <div>
                    <label for="status" class="block text-sm font-bold text-black mb-1">Status:</label>
                    <select id="status" name="status" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                        <option value="pending" <?php echo (($_GET['status'] ?? 'pending') === 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="submitted" <?php echo (($_GET['status'] ?? '') === 'submitted') ? 'selected' : ''; ?>>Submitted</option>
                        <option value="approved" <?php echo (($_GET['status'] ?? '') === 'approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo (($_GET['status'] ?? '') === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline"><i class="fas fa-plus mr-2"></i> Add Document</button>
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $data['process_id']; ?>" class="text-black hover:underline"><i class="fas fa-arrow-left mr-2"></i>Back to Process Details</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <p class="text-red-600 text-center">Process information is missing. Cannot add document.</p>
        <p class="text-center mt-2"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-black hover:underline">Go to Accreditation Processes</a></p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
