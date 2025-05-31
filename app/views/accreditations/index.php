<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>All Accreditation Processes</h2>
<?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
    <p><a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=accreditation/showCreateForm">Create New Process</a></p>
<?php endif; ?>

<?php if (isset($data['processes']) && !empty($data['processes'])): ?>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['processes'] as $process): ?>
                <tr>
                    <td><a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=accreditation/show/<?php echo $process['id']; ?>"><?php echo htmlspecialchars($process['title']); ?></a></td>
                    <td><?php echo htmlspecialchars($process['status']); ?></td>
                    <td><?php echo htmlspecialchars($process['start_date'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($process['end_date'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($process['created_by_name'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=accreditation/show/<?php echo $process['id']; ?>">View</a>
                        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                            | <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=accreditation/showEditForm/<?php echo $process['id']; ?>">Edit</a>
                            | <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=accreditation/delete/<?php echo $process['id']; ?>" onclick="return confirm('Are you sure you want to delete this process and ALL related data (documents, tasks, comments)? This action cannot be undone.');">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No accreditation processes found.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
