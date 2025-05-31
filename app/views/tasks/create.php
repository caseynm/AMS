<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <?php if (isset($data['document_id']) && isset($data['document_name'])): ?>
        <h2 class="text-2xl font-bold text-black mb-6">Create New Task for Document: <?php echo htmlspecialchars($data['document_name']); ?></h2>
        <div class="bg-white p-6 rounded-lg shadow-md border border-black">
            <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/create/<?php echo $data['document_id']; ?>" method="POST" class="space-y-6">
                <div>
                    <label for="description" class="block text-sm font-bold text-black mb-1">Description:</label>
                    <textarea id="description" name="description" required class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black" rows="4"><?php echo htmlspecialchars($_GET['description'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label for="due_date" class="block text-sm font-bold text-black mb-1">Due Date:</label>
                    <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($_GET['due_date'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                </div>
                <div>
                    <label for="status" class="block text-sm font-bold text-black mb-1">Status:</label>
                    <select id="status" name="status" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                        <option value="pending" <?php echo ((isset($_GET['status']) && $_GET['status'] === 'pending') || !isset($_GET['status'])) ? 'selected' : ''; ?>>Pending</option>
                        <option value="in_progress" <?php echo (isset($_GET['status']) && $_GET['status'] === 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                        <option value="completed" <?php echo (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="overdue" <?php echo (isset($_GET['status']) && $_GET['status'] === 'overdue') ? 'selected' : ''; ?>>Overdue</option>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline"><i class="fas fa-plus mr-2"></i> Create Task</button>
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $data['document_id']; ?>" class="text-black hover:underline"><i class="fas fa-arrow-left mr-2"></i>Back to Tasks for Document</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <p class="text-red-600 text-center">Document information is missing. Cannot create task.</p>
        <p class="text-center mt-2"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-black hover:underline">Go to Accreditation Processes</a></p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
