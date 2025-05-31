<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php if (isset($data['process'])): ?>
    <h2>Process: <?php echo htmlspecialchars($data['process']['title']); ?></h2>
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($data['process']['description'] ?? 'N/A')); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($data['process']['status']); ?></p>
    <p><strong>Start Date:</strong> <?php echo htmlspecialchars($data['process']['start_date'] ?? 'N/A'); ?></p>
    <p><strong>End Date:</strong> <?php echo htmlspecialchars($data['process']['end_date'] ?? 'N/A'); ?></p>
    <p><strong>Created By:</strong> <?php echo htmlspecialchars($data['process']['created_by_name'] ?? 'N/A'); ?> on <?php echo htmlspecialchars(date('Y-m-d', strtotime($data['process']['created_at']))); ?></p>

    <hr>
    <h3>Documents for this Process</h3>
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
        <p><a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=document/showCreateForm/<?php echo $data['process']['id']; ?>">Add New Document</a></p>
    <?php endif; ?>
    <?php if (isset($data['documents']) && !empty($data['documents'])): ?>
        <ul>
            <?php foreach ($data['documents'] as $doc): ?>
                <li id="doc<?php echo $doc['id'];?>">
                    <a href="<?php echo htmlspecialchars($doc['onedrive_url'] ?? '#'); ?>" target="_blank"><?php echo htmlspecialchars($doc['name']); ?></a>
                    (Status: <?php echo htmlspecialchars($doc['status']); ?>)
                    | <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=task/listByDocument/<?php echo $doc['id']; ?>">View/Manage Tasks</a>
                    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                        | <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=document/showEditForm/<?php echo $doc['id']; ?>">Edit Doc</a>
                        | <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=document/delete/<?php echo $doc['id']; ?>/<?php echo $data['process']['id']; ?>" onclick="return confirm('Are you sure you want to delete this document and all its tasks?');">Delete Doc</a>
                    <?php endif; ?>
                     | <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=comment/showByEntity/document/<?php echo $doc['id']; ?>">Comments</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No documents found for this process.</p>
    <?php endif; ?>

    <hr>
    <h3>Comments for this Process</h3>
    <p><a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=comment/showByEntity/process/<?php echo $data['process']['id']; ?>">View/Add Comments for Process</a></p>

    <p><a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=accreditation/index">Back to All Processes</a></p>
<?php else: ?>
    <p>Accreditation process not found.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
