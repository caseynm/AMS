<?php require_once __DIR__ . '/layouts/header.php'; // Adjusted path if home.php is in views/ directly ?>
<h2 class="text-3xl font-bold text-neon-purple mb-4">Accreditation Dashboard</h2>
<p class="text-lg text-gray-400 mb-6">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?>!</p>

<h3 class="text-2xl font-semibold text-gray-200 mb-4">Accreditation Processes</h3>
<?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
    <p class="mb-4"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/showCreateForm" class="inline-block bg-neon-purple text-white hover:bg-purple-700 font-bold py-2 px-4 rounded transition-colors duration-300"><i class="fas fa-plus mr-2"></i> Create New Process</a></p>
<?php endif; ?>

<?php if (isset($data['processes']) && !empty($data['processes'])): ?>
    <ul class="space-y-4">
        <?php foreach ($data['processes'] as $process): ?>
            <li class="bg-brand-gray p-4 rounded-lg shadow hover:shadow-neon-purple/30 transition-shadow duration-300">
                <div class="flex justify-between items-center">
                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $process['id']; ?>" class="text-xl font-semibold text-neon-purple hover:text-purple-400">
                        <?php echo htmlspecialchars($process['title']); ?>
                    </a>
                    <span class="text-sm font-medium px-3 py-1 rounded-full
                        <?php
                            switch (strtolower($process['status'] ?? '')) {
                                case 'active': echo 'bg-green-600 text-green-100'; break;
                                case 'pending': echo 'bg-yellow-600 text-yellow-100'; break;
                                case 'completed': echo 'bg-blue-600 text-blue-100'; break;
                                case 'archived': echo 'bg-gray-600 text-gray-100'; break;
                                default: echo 'bg-gray-500 text-gray-100';
                            }
                        ?>">
                        <?php echo htmlspecialchars(ucfirst($process['status'])); ?>
                    </span>
                </div>
                <p class="text-gray-400 text-sm mt-1">Created by: <?php echo htmlspecialchars($process['created_by_name'] ?? 'N/A'); ?></p>
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                    <div class="mt-3 text-right">
                        <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/showEditForm/<?php echo $process['id']; ?>" class="text-yellow-400 hover:text-yellow-600 mr-3"><i class="fas fa-pencil-alt mr-1"></i> Edit</a>
                        <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/delete/<?php echo $process['id']; ?>" data-message="Are you sure you want to delete this process and ALL related data (documents, tasks, comments)? This action cannot be undone." class="delete-confirm-link text-red-400 hover:text-red-600"><i class="fas fa-trash-alt mr-1"></i> Delete</a>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No accreditation processes found.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/layouts/footer.php'; // Adjusted path ?>
