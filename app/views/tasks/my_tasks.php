<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-black mb-6">My Assigned Tasks</h2>
    <?php if (isset($data['tasks']) && !empty($data['tasks'])): ?>
        <ul class="space-y-4">
            <?php foreach ($data['tasks'] as $task): ?>
                <li id="task<?php echo $task['id'];?>" class="bg-white border border-black p-4 rounded-lg shadow-md">
                    <h4 class="text-lg font-semibold text-black"><?php echo htmlspecialchars($task['description']); ?></h4>
                    <p class="text-sm text-black mt-1">
                        <strong>Process:</strong> <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $task['accreditation_process_id']; ?>" class="text-black hover:underline"><?php echo htmlspecialchars($task['process_title']); ?></a>
                    </p>
                    <p class="text-sm text-black">
                        <strong>Document:</strong> <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $task['accreditation_process_id']; ?>#doc<?php echo $task['document_id'];?>" class="text-black hover:underline"><?php echo htmlspecialchars($task['document_name']); ?></a>
                        ( <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $task['document_id']; ?>#task<?php echo $task['id'];?>" class="text-black hover:underline">View all tasks for this doc</a> )
                    </p>
                    <div class="text-sm text-black mt-1">
                        Status: <span class="task-status-text font-semibold px-2 py-0.5 rounded-full text-xs <?php
                            switch (strtolower($task['status'] ?? '')) {
                                case 'completed': echo 'bg-green-100 text-green-800 border border-green-400'; break;
                                case 'pending': echo 'bg-yellow-100 text-yellow-800 border border-yellow-400'; break;
                                case 'in_progress': echo 'bg-blue-100 text-blue-800 border border-blue-400'; break;
                                case 'overdue': echo 'bg-red-100 text-red-800 border border-red-400'; break;
                                default: echo 'bg-gray-100 text-gray-800 border border-gray-400';
                            }
                        ?>" id="task-status-<?php echo $task['id']; ?>"><?php echo htmlspecialchars(ucfirst($task['status'])); ?></span> |
                        Due Date: <?php echo htmlspecialchars($task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'N/A'); ?>
                    </div>

                    <div class="mt-3 space-x-3 text-sm">
                        <?php if ($task['status'] !== 'completed'): ?>
                            <a href="#" class="ajax-update-task-status text-green-600 hover:text-green-800 hover:underline"
                               data-task-id="<?php echo $task['id']; ?>"
                               data-new-status="completed"
                               data-context-id="<?php echo $_SESSION['user_id']; ?>"
                               data-redirect-context="mytasks"
                               id="update-link-task-<?php echo $task['id']; ?>-completed"><i class="fas fa-check-circle mr-1"></i> Mark Completed</a>
                        <?php endif; ?>
                        <?php if ($task['status'] === 'completed' || $task['status'] === 'overdue' || $task['status'] === 'in_progress'): ?>
                            <a href="#" class="ajax-update-task-status text-gray-600 hover:text-gray-800 hover:underline"
                               data-task-id="<?php echo $task['id']; ?>"
                               data-new-status="pending"
                               data-context-id="<?php echo $_SESSION['user_id']; ?>"
                               data-redirect-context="mytasks"
                               id="update-link-task-<?php echo $task['id']; ?>-pending"><i class="fas fa-clock mr-1"></i> Mark Pending</a>
                        <?php endif; ?>
                         <?php if ($task['status'] === 'pending'): ?>
                            <a href="#" class="ajax-update-task-status text-blue-600 hover:text-blue-800 hover:underline"
                               data-task-id="<?php echo $task['id']; ?>"
                               data-new-status="in_progress"
                               data-context-id="<?php echo $_SESSION['user_id']; ?>"
                               data-redirect-context="mytasks"
                               id="update-link-task-<?php echo $task['id']; ?>-inprogress"><i class="fas fa-spinner mr-1"></i> Mark In Progress</a>
                        <?php endif; ?>
                         <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/showByEntity/task/<?php echo $task['id']; ?>" class="text-black hover:underline"><i class="fas fa-comment mr-1"></i> Comments</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-black">You have no tasks assigned to you.</p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
