<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <?php if (isset($data['process'])): ?>
        <div class="bg-white p-6 rounded-lg shadow-md border border-black mb-6">
            <h2 class="text-3xl font-bold text-black mb-3"><?php echo htmlspecialchars($data['process']['title']); ?></h2>
            <div class="space-y-2">
                <p class="text-black"><strong class="font-semibold">Description:</strong> <?php echo nl2br(htmlspecialchars($data['process']['description'] ?? 'N/A')); ?></p>
                <p class="text-black"><strong class="font-semibold">Status:</strong>
                    <span class="font-medium px-2 py-0.5 rounded-full text-xs
                        <?php
                            switch (strtolower($data['process']['status'] ?? '')) {
                                case 'active': echo 'bg-green-100 text-green-800 border border-green-400'; break;
                                case 'pending': echo 'bg-yellow-100 text-yellow-800 border border-yellow-400'; break;
                                case 'completed': echo 'bg-blue-100 text-blue-800 border border-blue-400'; break;
                                case 'archived': echo 'bg-gray-100 text-gray-800 border border-gray-400'; break;
                                default: echo 'bg-gray-200 text-gray-800 border border-gray-400';
                            }
                        ?>">
                        <?php echo htmlspecialchars(ucfirst($data['process']['status'])); ?>
                    </span>
                </p>
                <p class="text-black"><strong class="font-semibold">Start Date:</strong> <?php echo htmlspecialchars($data['process']['start_date'] ? date('M d, Y', strtotime($data['process']['start_date'])) : 'N/A'); ?></p>
                <p class="text-black"><strong class="font-semibold">End Date:</strong> <?php echo htmlspecialchars($data['process']['end_date'] ? date('M d, Y', strtotime($data['process']['end_date'])) : 'N/A'); ?></p>
                <p class="text-black"><strong class="font-semibold">Created By:</strong> <?php echo htmlspecialchars($data['process']['created_by_name'] ?? 'N/A'); ?> on <?php echo htmlspecialchars(date('M d, Y', strtotime($data['process']['created_at']))); ?></p>
            </div>
        </div>

        <hr class="border-black my-6">

        <div class="bg-white p-6 rounded-lg shadow-md border border-black mb-6">
            <h3 class="text-xl font-semibold text-black mb-4">Filled Documents for this Process</h3>
            <p class="mb-4"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/selectTemplate/<?php echo $data['process']['id']; ?>" class="inline-block bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline"><i class="fas fa-file-alt mr-2"></i> Create New Document from Template</a></p>

            <?php if (isset($data['documents']) && !empty($data['documents'])): ?>
                <ul class="space-y-3">
                    <?php foreach ($data['documents'] as $doc): ?>
                        <li id="doc<?php echo $doc['id'];?>" class="bg-gray-50 p-4 rounded-md border border-gray-300 hover:shadow-lg transition-shadow duration-300 flex justify-between items-center">
                            <div>
                                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/view/<?php echo $doc['id']; ?>" class="text-lg font-medium text-black hover:underline"><?php echo htmlspecialchars($doc['name']); ?></a>
                                <span class="text-sm text-gray-600 ml-2">(Template: <?php echo htmlspecialchars($doc['template_name']); ?> - Status: <?php echo htmlspecialchars(ucfirst($doc['status'])); ?>)</span>
                            </div>
                            <div class="space-x-3 whitespace-nowrap">
                                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $doc['id']; ?>" class="text-black hover:underline"><i class="fas fa-tasks mr-1"></i> Tasks</a>
                                <?php if ($doc['user_id'] == ($_SESSION['user_id'] ?? null) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser')): ?>
                                    <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/edit/<?php echo $doc['id']; ?>" class="text-black hover:underline"><i class="fas fa-edit mr-1"></i> Edit</a>
                                    <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>document/delete/<?php echo $doc['id']; ?>/<?php echo $data['process']['id']; ?>" data-message="Are you sure you want to delete this document and all its tasks? This action cannot be undone." class="delete-confirm-link text-red-600 hover:text-red-800 underline"><i class="fas fa-trash-alt mr-1"></i> Delete</a>
                                <?php endif; ?>
                                 <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/showByEntity/document/<?php echo $doc['id']; ?>" class="text-black hover:underline"><i class="fas fa-comments mr-1"></i> Comments</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-black">No documents found for this process.</p>
            <?php endif; ?>
        </div>

        <hr class="border-black my-6">

        <div class="bg-white p-6 rounded-lg shadow-md border border-black">
            <h3 class="text-xl font-semibold text-black mb-3">Comments for this Process</h3>
            <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/showByEntity/process/<?php echo $data['process']['id']; ?>" class="text-black hover:underline"><i class="fas fa-comments mr-1"></i> View/Add Comments for Process</a></p>
        </div>

        <p class="mt-8"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-black hover:underline font-semibold"><i class="fas fa-arrow-left mr-2"></i>Back to All Processes</a></p>
    <?php else: ?>
        <p class="text-red-600 text-center">Accreditation process not found.</p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
