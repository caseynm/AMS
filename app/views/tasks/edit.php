<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <?php if (isset($data['task'])): ?>
    <h2 class="text-2xl font-bold text-black mb-6">Edit Task: <?php echo htmlspecialchars(substr($data['task']['description'], 0, 50)) . (strlen($data['task']['description']) > 50 ? '...' : ''); ?></h2>
    <div class="bg-white p-6 rounded-lg shadow-md border border-black">
        <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/update/<?php echo $data['task']['id']; ?>" method="POST" class="space-y-6">
            <div>
                <label for="description" class="block text-sm font-bold text-black mb-1">Description:</label>
                <textarea id="description" name="description" required class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black" rows="4"><?php echo htmlspecialchars($data['task']['description']); ?></textarea>
            </div>
            <div>
                <label for="due_date" class="block text-sm font-bold text-black mb-1">Due Date:</label>
                <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($data['task']['due_date']); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
            </div>
            <div>
                <label for="status" class="block text-sm font-bold text-black mb-1">Status:</label>
                <select id="status" name="status" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                    <option value="pending" <?php echo ($data['task']['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="in_progress" <?php echo ($data['task']['status'] === 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="completed" <?php echo ($data['task']['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="overdue" <?php echo ($data['task']['status'] === 'overdue') ? 'selected' : ''; ?>>Overdue</option>
                </select>
            </div>
            <input type="hidden" name="document_id" value="<?php echo $data['task']['document_id']; ?>">
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline"><i class="fas fa-save mr-2"></i> Update Task</button>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $data['task']['document_id']; ?>" class="text-black hover:underline"><i class="fas fa-arrow-left mr-2"></i>Back to Tasks for Document</a>
            </div>
        </form>
    </div>
    <?php else: ?>
        <p class="text-red-600 text-center">Task not found.</p>
        <p class="text-center mt-2"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-black hover:underline">Go to Accreditation Processes</a></p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
