<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2 class="text-2xl font-semibold text-neon-purple mb-6">Create New Accreditation Process</h2>
<form action="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/create" method="POST" class="space-y-6 bg-brand-gray p-6 rounded-lg shadow-lg">
    <div>
        <label for="title" class="block text-gray-300 mb-1 font-semibold">Title:</label>
        <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($_GET['title'] ?? ''); ?>" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
    </div>
    <div>
        <label for="description" class="block text-gray-300 mb-1 font-semibold">Description:</label>
        <textarea id="description" name="description" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors" rows="4"><?php echo htmlspecialchars($_GET['description'] ?? ''); ?></textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="start_date" class="block text-gray-300 mb-1 font-semibold">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
        </div>
        <div>
            <label for="end_date" class="block text-gray-300 mb-1 font-semibold">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
        </div>
    </div>
    <div>
        <label for="status" class="block text-gray-300 mb-1 font-semibold">Status:</label>
        <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($_GET['status'] ?? 'pending'); ?>" class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors">
    </div>
    <button type="submit" class="w-full md:w-auto bg-neon-purple text-white hover:bg-purple-700 font-bold py-3 px-6 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-save mr-2"></i> Create Process</button>
</form>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
