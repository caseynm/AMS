<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2 class="text-2xl font-semibold text-neon-purple mb-6">Edit Task</h2>
<?php if (isset($data['task'])): ?>
<form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/update/<?php echo $data['task']['id']; ?>" method="POST" class="space-y-6 bg-brand-gray p-6 rounded-lg shadow-lg">
    <div>
        <label for="description" class="block text-gray-300 mb-1 font-semibold">Description:</label>
        <textarea id="description" name="description" required class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors" rows="4"><?php echo htmlspecialchars($data['task']['description']); ?></textarea>
    </div>
    <div>
        <label for="due_date" class="block text-gray-300 mb-1 font-semibold">Due Date:</label>
        <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($data['task']['due_date']); ?>" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
    </div>
    <div>
        <label for="status" class="block text-gray-300 mb-1 font-semibold">Status:</label>
        <select id="status" name="status" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
            <option value="pending" <?php echo ($data['task']['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="in_progress" <?php echo ($data['task']['status'] === 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
            <option value="completed" <?php echo ($data['task']['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
            <option value="overdue" <?php echo ($data['task']['status'] === 'overdue') ? 'selected' : ''; ?>>Overdue</option>
        </select>
    </div>
    <input type="hidden" name="document_id" value="<?php echo $data['task']['document_id']; ?>">
    <button type="submit" class="w-full md:w-auto bg-neon-purple text-white hover:bg-purple-700 font-bold py-3 px-6 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-save mr-2"></i> Update Task</button>
</form>
<p class="mt-6"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $data['task']['document_id']; ?>" class="text-neon-purple hover:text-purple-400"><i class="fas fa-arrow-left mr-2"></i>Back to Tasks for Document</a></p>
<?php else: ?>
    <p class="text-red-400">Task not found.</p>
    <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>index.php?url=accreditation/index">Go to Accreditation Processes</a></p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
