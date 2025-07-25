<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php if (isset($data['document_id']) && isset($data['document_name'])): ?>
    <h2>Create New Task for Document: <?php echo htmlspecialchars($data['document_name']); ?></h2>
    <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/create/<?php echo $data['document_id']; ?>" method="POST">
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($_GET['description'] ?? ''); ?></textarea>
        </div>
        <div>
            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($_GET['due_date'] ?? ''); ?>">
        </div>
        <div>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="in_progress" <?php echo (isset($_GET['status']) && $_GET['status'] === 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                <option value="completed" <?php echo (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                <option value="overdue" <?php echo (isset($_GET['status']) && $_GET['status'] === 'overdue') ? 'selected' : ''; ?>>Overdue</option>
            </select>
        </div>
        <button type="submit" class="bg-neon-purple text-white hover:bg-purple-700 font-bold py-2 px-4 rounded transition-colors"><i class="fas fa-plus mr-2"></i> Create Task</button>
    </form>
    <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $data['document_id']; ?>" class="text-blue-400 hover:text-blue-600 mt-4 inline-block"><i class="fas fa-arrow-left mr-2"></i>Back to Tasks for Document</a></p>
<?php else: ?>
    <p>Document information is missing. Cannot create task.</p>
     <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>index.php?url=accreditation/index">Go to Accreditation Processes</a></p>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
