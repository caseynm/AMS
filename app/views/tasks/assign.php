<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php if (isset($data['task']) && isset($data['allUsers']) && isset($data['assignedUserIds'])): ?>
    <h2>Assign Task: "<?php echo htmlspecialchars($data['task']['description']); ?>"</h2>
    <p><strong>For Document:</strong> <?php echo htmlspecialchars($data['task']['document_name']); ?></p>
    <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/assign/<?php echo $data['task']['id']; ?>" method="POST">
        <div>
            <label for="select-users-assign">Select Users (Ctrl/Cmd + Click for multiple):</label>
            <select id="select-users-assign" name="user_ids[]" multiple style="width: 100%;">
                <?php foreach ($data['allUsers'] as $user): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo in_array($user['id'], $data['assignedUserIds']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user['name']) . " (" . htmlspecialchars($user['email']) . ")"; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="hidden" name="document_id" value="<?php echo $data['task']['document_id']; ?>">
        <button type="submit">Assign/Update Users</button>
    </form>
    <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $data['task']['document_id']; ?>">Back to Tasks for Document</a></p>
<?php else: ?>
    <p>Task details or user list not found. Cannot display assignment form.</p>
    <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>index.php?url=accreditation/index">Go to Accreditation Processes</a></p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
