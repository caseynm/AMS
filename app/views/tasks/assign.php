<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <?php if (isset($data['task']) && isset($data['allUsers']) && isset($data['assignedUserIds'])): ?>
        <h2 class="text-2xl font-bold text-black mb-2">Assign Task: "<?php echo htmlspecialchars(substr($data['task']['description'], 0, 50)) . (strlen($data['task']['description']) > 50 ? '...' : ''); ?>"</h2>
        <p class="text-sm text-black mb-6"><strong>For Document:</strong> <?php echo htmlspecialchars($data['task']['document_name']); ?></p>
        <div class="bg-white p-6 rounded-lg shadow-md border border-black">
            <form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/assign/<?php echo $data['task']['id']; ?>" method="POST" class="space-y-6">
                <div>
                    <label for="select-users-assign" class="block text-sm font-bold text-black mb-1">Select Users:</label>
                    <select id="select-users-assign" name="user_ids[]" multiple style="width: 100%;" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black">
                        <?php foreach ($data['allUsers'] as $user): ?>
                            <option value="<?php echo $user['id']; ?>" <?php echo in_array($user['id'], $data['assignedUserIds']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($user['name']) . " (" . htmlspecialchars($user['email']) . ")"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-600 mt-1">Hold Ctrl/Cmd to select multiple users. This field can be enhanced with a JavaScript library like Select2.</p>
                </div>
                <input type="hidden" name="document_id" value="<?php echo $data['task']['document_id']; ?>">
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline"><i class="fas fa-users-cog mr-2"></i> Assign/Update Users</button>
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $data['task']['document_id']; ?>" class="text-black hover:underline"><i class="fas fa-arrow-left mr-2"></i>Back to Tasks for Document</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <p class="text-red-600 text-center">Task details or user list not found. Cannot display assignment form.</p>
        <p class="text-center mt-2"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-black hover:underline">Go to Accreditation Processes</a></p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
