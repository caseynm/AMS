<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Create New Accreditation Process</h2>
<form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/create" method="POST">
    <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($_GET['title'] ?? ''); ?>">
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea id="description" name="description"><?php echo htmlspecialchars($_GET['description'] ?? ''); ?></textarea>
    </div>
    <div>
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>">
    </div>
    <div>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>">
    </div>
    <div>
        <label for="status">Status:</label>
        <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($_GET['status'] ?? 'pending'); ?>">
    </div>
    <button type="submit">Create Process</button>
</form>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
