<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-black mb-6">Create New Accreditation Process</h2>
    <div class="bg-white p-6 rounded-lg shadow-md border border-black">
        <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/create" method="POST" class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-bold text-black mb-1">Title:</label>
                <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($_GET['title'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
            </div>
            <div>
                <label for="description" class="block text-sm font-bold text-black mb-1">Description:</label>
                <textarea id="description" name="description" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black" rows="4"><?php echo htmlspecialchars($_GET['description'] ?? ''); ?></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-bold text-black mb-1">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-bold text-black mb-1">End Date:</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                </div>
            </div>
            <div>
                <label for="status" class="block text-sm font-bold text-black mb-1">Status:</label>
                 <select id="status" name="status" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                    <option value="pending" <?php echo (($_GET['status'] ?? 'pending') === 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="active" <?php echo (($_GET['status'] ?? '') === 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="completed" <?php echo (($_GET['status'] ?? '') === 'completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="archived" <?php echo (($_GET['status'] ?? '') === 'archived') ? 'selected' : ''; ?>>Archived</option>
                </select>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline"><i class="fas fa-save mr-2"></i> Create Process</button>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-black hover:underline">Back to List</a>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
