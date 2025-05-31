<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <?php if (isset($data['document'])): ?>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-black">Tasks for Document: <?php echo htmlspecialchars($data['document']['name']); ?></h2>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/showCreateForm/<?php echo $data['document']['id']; ?>" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 focus:outline-none focus:shadow-outline"><i class="fas fa-plus-square mr-2"></i> Create New Task</a>
            <?php endif; ?>
        </div>

        <?php if (isset($data['tasks']) && !empty($data['tasks'])): ?>
            <ul class="space-y-4">
                <?php foreach ($data['tasks'] as $task): ?>
                    <li id="task<?php echo $task['id'];?>" class="bg-white border border-black p-4 rounded-lg shadow-md">
                        <h4 class="text-xl font-semibold text-black"><?php echo htmlspecialchars($task['description']); ?></h4>
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
                            Due Date: <?php echo htmlspecialchars($task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'N/A'); ?> |
                            Created by: <?php echo htmlspecialchars($task['created_by_name'] ?? 'N/A'); ?>
                        </div>
                        <?php if (!empty($task['assigned_users'])): ?>
                            <p class="text-sm text-gray-700 mt-1">Assigned to:
                            <?php
                            $user_names = [];
                            foreach ($task['assigned_users'] as $assigned_user) {
                                $user_names[] = htmlspecialchars($assigned_user['name']);
                            }
                            echo implode(', ', $user_names);
                            ?>
                            </p>
                        <?php else: ?>
                            <p class="text-sm text-gray-700 mt-1"><em>Not assigned</em></p>
                        <?php endif; ?>

                        <div class="mt-3 space-x-3 text-sm">
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/showEditForm/<?php echo $task['id']; ?>" class="text-black hover:underline"><i class="fas fa-edit mr-1"></i> Edit</a>
                                <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/showAssignForm/<?php echo $task['id']; ?>" class="text-black hover:underline"><i class="fas fa-user-plus mr-1"></i> Assign</a>
                                <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/delete/<?php echo $task['id']; ?>/<?php echo $data['document']['id']; ?>" data-message="Are you sure you want to delete this task?" class="delete-confirm-link text-red-600 hover:text-red-800 underline"><i class="fas fa-trash mr-1"></i> Delete</a>
                            <?php endif; ?>

                            <?php
                            $canUpdate = (isset($task['is_assigned_to_current_user']) && $task['is_assigned_to_current_user']) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser');
                            ?>
                            <?php if ($canUpdate): ?>
                                <?php if ($task['status'] !== 'completed'): ?>
                                    <a href="#" class="ajax-update-task-status text-green-600 hover:text-green-800 hover:underline"
                                       data-task-id="<?php echo $task['id']; ?>"
                                       data-new-status="completed"
                                       data-context-id="<?php echo $data['document']['id']; ?>"
                                       data-redirect-context="document"
                                       id="update-link-task-<?php echo $task['id']; ?>-completed"><i class="fas fa-check-circle mr-1"></i> Mark Completed</a>
                                <?php endif; ?>
                                <?php if ($task['status'] === 'completed' || $task['status'] === 'overdue' || $task['status'] === 'in_progress'): ?>
                                    <a href="#" class="ajax-update-task-status text-gray-600 hover:text-gray-800 hover:underline"
                                       data-task-id="<?php echo $task['id']; ?>"
                                       data-new-status="pending"
                                       data-context-id="<?php echo $data['document']['id']; ?>"
                                       data-redirect-context="document"
                                       id="update-link-task-<?php echo $task['id']; ?>-pending"><i class="fas fa-clock mr-1"></i> Mark Pending</a>
                                <?php endif; ?>
                                 <?php if ($task['status'] === 'pending'): ?>
                                    <a href="#" class="ajax-update-task-status text-blue-600 hover:text-blue-800 hover:underline"
                                       data-task-id="<?php echo $task['id']; ?>"
                                       data-new-status="in_progress"
                                       data-context-id="<?php echo $data['document']['id']; ?>"
                                       data-redirect-context="document"
                                       id="update-link-task-<?php echo $task['id']; ?>-inprogress"><i class="fas fa-spinner mr-1"></i> Mark In Progress</a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/showByEntity/task/<?php echo $task['id']; ?>" class="text-black hover:underline"><i class="fas fa-comment mr-1"></i> Comments</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-black">No tasks found for this document.</p>
        <?php endif; ?>

        <p class="mt-8"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $data['document']['accreditation_process_id']; ?>" class="text-black hover:underline font-semibold"><i class="fas fa-arrow-left mr-2"></i>Back to Process Details</a></p>

    <?php else: ?>
        <p class="text-red-600 text-center">Document information not found.</p>
        <p class="text-center mt-2"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index" class="text-black hover:underline">Go to Accreditation Processes</a></p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
