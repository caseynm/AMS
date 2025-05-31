<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php if (isset($data['process'])): ?>
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-neon-purple mb-3"><?php echo htmlspecialchars($data['process']['title']); ?></h2>
        <p class="text-gray-400"><strong class="text-gray-300">Description:</strong> <?php echo nl2br(htmlspecialchars($data['process']['description'] ?? 'N/A')); ?></p>
        <p class="text-gray-400 mt-1"><strong class="text-gray-300">Status:</strong>
            <span class="font-medium px-2 py-0.5 rounded-full text-sm
                <?php
                    switch (strtolower($data['process']['status'] ?? '')) {
                        case 'active': echo 'bg-green-600 text-green-100'; break;
                        case 'pending': echo 'bg-yellow-600 text-yellow-100'; break;
                        case 'completed': echo 'bg-blue-600 text-blue-100'; break;
                        case 'archived': echo 'bg-gray-700 text-gray-200'; break;
                        default: echo 'bg-gray-600 text-gray-100';
                    }
                ?>">
                <?php echo htmlspecialchars(ucfirst($data['process']['status'])); ?>
            </span>
        </p>
        <p class="text-gray-400 mt-1"><strong class="text-gray-300">Start Date:</strong> <?php echo htmlspecialchars($data['process']['start_date'] ?? 'N/A'); ?></p>
        <p class="text-gray-400 mt-1"><strong class="text-gray-300">End Date:</strong> <?php echo htmlspecialchars($data['process']['end_date'] ?? 'N/A'); ?></p>
        <p class="text-gray-400 mt-1"><strong class="text-gray-300">Created By:</strong> <?php echo htmlspecialchars($data['process']['created_by_name'] ?? 'N/A'); ?> on <?php echo htmlspecialchars(date('M d, Y', strtotime($data['process']['created_at']))); ?></p>
    </div>

    <hr class="border-gray-700 my-6">

    <div>
        <h3 class="text-2xl font-semibold text-gray-200 mb-4">Documents for this Process</h3>
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
            <p class="mb-4"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/showCreateForm/<?php echo $data['process']['id']; ?>" class="inline-block bg-neon-purple text-white hover:bg-purple-700 font-bold py-2 px-4 rounded transition-colors duration-300"><i class="fas fa-file-plus mr-2"></i> Add New Document</a></p>
        <?php endif; ?>
        <?php if (isset($data['documents']) && !empty($data['documents'])): ?>
            <ul class="space-y-3">
                <?php foreach ($data['documents'] as $doc): ?>
                    <li id="doc<?php echo $doc['id'];?>" class="bg-brand-dark p-4 rounded-lg shadow hover:shadow-neon-purple/20 transition-shadow duration-300 flex justify-between items-center">
                        <div>
                            <a href="<?php echo htmlspecialchars($doc['onedrive_url'] ?? '#'); ?>" target="_blank" class="text-lg font-medium text-blue-400 hover:text-blue-300"><?php echo htmlspecialchars($doc['name']); ?></a>
                            <span class="text-sm text-gray-500 ml-2">(Status: <?php echo htmlspecialchars($doc['status']); ?>)</span>
                        </div>
                        <div class="space-x-3 whitespace-nowrap">
                            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $doc['id']; ?>" class="text-green-400 hover:text-green-300"><i class="fas fa-tasks mr-1"></i> Tasks</a>
                            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/showEditForm/<?php echo $doc['id']; ?>" class="text-yellow-400 hover:text-yellow-300"><i class="fas fa-edit mr-1"></i> Edit</a>
                                <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/delete/<?php echo $doc['id']; ?>/<?php echo $data['process']['id']; ?>" data-message="Are you sure you want to delete this document and all its tasks? This action cannot be undone." class="delete-confirm-link text-red-400 hover:text-red-300"><i class="fas fa-trash-alt mr-1"></i> Delete</a>
                            <?php endif; ?>
                             <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/showByEntity/document/<?php echo $doc['id']; ?>" class="text-gray-500 hover:text-gray-300"><i class="fas fa-comments mr-1"></i> Comments</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-400">No documents found for this process.</p>
        <?php endif; ?>
    </div>

    <hr class="border-gray-700 my-6">

    <div>
        <h3 class="text-2xl font-semibold text-gray-200 mb-3">Comments for this Process</h3>
        <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/showByEntity/process/<?php echo $data['process']['id']; ?>" class="text-gray-400 hover:text-gray-200 underline"><i class="fas fa-comments mr-1"></i> View/Add Comments for Process</a></p>
    </div>

    <p class="mt-8"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-neon-purple hover:text-purple-400 font-semibold"><i class="fas fa-arrow-left mr-2"></i>Back to All Processes</a></p>
<?php else: ?>
    <p class="text-red-400">Accreditation process not found.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
