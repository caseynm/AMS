<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Edit Accreditation Process</h2>
<?php if (isset($data['process'])): ?>
<form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/update/<?php echo $data['process']['id']; ?>" method="POST">
    <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($data['process']['title']); ?>" required>
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea id="description" name="description"><?php echo htmlspecialchars($data['process']['description']); ?></textarea>
    </div>
    <div>
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($data['process']['start_date']); ?>">
    </div>
    <div>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($data['process']['end_date']); ?>">
    </div>
    <div>
        <label for="status">Status:</label>
        <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($data['process']['status']); ?>">
    </div>
    <button type="submit">Update Process</button>
</form>
<?php else: ?>
    <p>Process not found.</p>
<?php endif; ?>
<p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $data['process']['id']; ?>">Back to Process Details</a></p>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
