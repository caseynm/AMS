<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Edit Task</h2>
<?php if (isset($data['task'])): ?>
<form action="/index.php?url=task/update/<?php echo $data['task']['id']; ?>" method="POST">
    <div>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($data['task']['description']); ?></textarea>
    </div>
    <div>
        <label for="due_date">Due Date:</label>
        <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($data['task']['due_date']); ?>">
    </div>
    <div>
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="pending" <?php echo ($data['task']['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="in_progress" <?php echo ($data['task']['status'] === 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
            <option value="completed" <?php echo ($data['task']['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
            <option value="overdue" <?php echo ($data['task']['status'] === 'overdue') ? 'selected' : ''; ?>>Overdue</option>
        </select>
    </div>
    <input type="hidden" name="document_id" value="<?php echo $data['task']['document_id']; ?>">
    <button type="submit">Update Task</button>
</form>
<p><a href="/index.php?url=task/listByDocument/<?php echo $data['task']['document_id']; ?>">Back to Tasks for Document</a></p>
<?php else: ?>
    <p>Task not found.</p>
    <p><a href="/index.php?url=accreditation/index">Go to Accreditation Processes</a></p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
