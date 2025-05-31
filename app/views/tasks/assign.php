<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php if (isset($data['task']) && isset($data['allUsers']) && isset($data['assignedUserIds'])): ?>
    <h2 class="text-2xl font-semibold text-neon-purple mb-4">Assign Task: "<?php echo htmlspecialchars($data['task']['description']); ?>"</h2>
    <p class="text-gray-400 mb-6"><strong>For Document:</strong> <?php echo htmlspecialchars($data['task']['document_name']); ?></p>
    <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/assign/<?php echo $data['task']['id']; ?>" method="POST" class="space-y-6 bg-brand-gray p-6 rounded-lg shadow-lg">
        <div>
            <label for="select-users-assign" class="block text-gray-300 mb-1 font-semibold">Select Users:</label>
            <select id="select-users-assign" name="user_ids[]" multiple style="width: 100%;" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
                <?php foreach ($data['allUsers'] as $user): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo in_array($user['id'], $data['assignedUserIds']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user['name']) . " (" . htmlspecialchars($user['email']) . ")"; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple users. Select2 will enhance this.</p>
        </div>
        <input type="hidden" name="document_id" value="<?php echo $data['task']['document_id']; ?>">
        <button type="submit" class="w-full md:w-auto bg-neon-purple text-white hover:bg-purple-700 font-bold py-3 px-6 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-users-cog mr-2"></i> Assign/Update Users</button>
    </form>
    <p class="mt-6"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $data['task']['document_id']; ?>" class="text-neon-purple hover:text-purple-400"><i class="fas fa-arrow-left mr-2"></i>Back to Tasks for Document</a></p>
<?php else: ?>
    <p class="text-red-400">Task details or user list not found. Cannot display assignment form.</p>
    <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>index.php?url=accreditation/index">Go to Accreditation Processes</a></p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
