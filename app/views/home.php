<?php require_once __DIR__ . '/layouts/header.php'; // Adjusted path if home.php is in views/ directly ?>
<h2>Accreditation Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?>!</p>

<h3>Accreditation Processes</h3>
<?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
    <p><a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=accreditation/showCreateForm">Create New Accreditation Process</a></p>
<?php endif; ?>

<?php if (isset($data['processes']) && !empty($data['processes'])): ?>
    <ul>
        <?php foreach ($data['processes'] as $process): ?>
            <li>
                <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=accreditation/show/<?php echo $process['id']; ?>">
                    <?php echo htmlspecialchars($process['title']); ?>
                </a>
                (Status: <?php echo htmlspecialchars($process['status']); ?>)
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                    | <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=accreditation/showEditForm/<?php echo $process['id']; ?>">Edit</a>
                    | <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=accreditation/delete/<?php echo $process['id']; ?>" onclick="return confirm('Are you sure you want to delete this process and all related data?');">Delete</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No accreditation processes found.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/layouts/footer.php'; // Adjusted path ?>
