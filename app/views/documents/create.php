<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php if (isset($data['process_id']) && isset($data['process_title'])): ?>
    <h2>Add New Document to Process: <?php echo htmlspecialchars($data['process_title']); ?></h2>
    <form action="/index.php?url=document/create/<?php echo $data['process_id']; ?>" method="POST">
        <div>
            <label for="name">Document Name:</label>
            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>">
        </div>
        <div>
            <label for="onedrive_url">OneDrive URL:</label>
            <input type="url" id="onedrive_url" name="onedrive_url" placeholder="https://example.onedrive.com/..." value="<?php echo htmlspecialchars($_GET['onedrive_url'] ?? ''); ?>">
        </div>
        <div>
            <label for="status">Status:</label>
            <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($_GET['status'] ?? 'pending'); ?>">
        </div>
        <button type="submit">Add Document</button>
    </form>
    <p><a href="/index.php?url=accreditation/show/<?php echo $data['process_id']; ?>">Back to Process Details</a></p>
<?php else: ?>
    <p>Process information is missing. Cannot add document.</p>
    <p><a href="/index.php?url=accreditation/index">Go to Accreditation Processes</a></p>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
