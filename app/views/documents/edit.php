<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Edit Document</h2>
<?php if (isset($data['document'])): ?>
<form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/update/<?php echo $data['document']['id']; ?>" method="POST">
    <div>
        <label for="name">Document Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['document']['name']); ?>" required>
    </div>
    <div>
        <label for="onedrive_url">OneDrive URL:</label>
        <input type="url" id="onedrive_url" name="onedrive_url" value="<?php echo htmlspecialchars($data['document']['onedrive_url']); ?>">
    </div>
    <div>
        <label for="status">Status:</label>
        <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($data['document']['status']); ?>">
    </div>
    <input type="hidden" name="process_id" value="<?php echo $data['document']['accreditation_process_id']; ?>">
    <button type="submit" class="bg-neon-purple text-white hover:bg-purple-700 font-bold py-2 px-4 rounded transition-colors"><i class="fas fa-save mr-2"></i> Update Document</button>
</form>
<p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $data['document']['accreditation_process_id']; ?>#doc<?php echo $data['document']['id']; ?>" class="text-blue-400 hover:text-blue-600 mt-4 inline-block"><i class="fas fa-arrow-left mr-2"></i>Back to Process Details</a></p>
<?php else: ?>
    <p>Document not found.</p>
    <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>index.php?url=accreditation/index">Go to Accreditation Processes</a></p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
