<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php if (isset($data['document'])): ?>
    <h2 class="text-3xl font-bold text-neon-purple mb-4">Tasks for Document: <span class="text-gray-200"><?php echo htmlspecialchars($data['document']['name']); ?></span></h2>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
        <p class="mb-6"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/showCreateForm/<?php echo $data['document']['id']; ?>" class="inline-block bg-neon-purple text-white hover:bg-purple-700 font-bold py-2 px-4 rounded transition-colors duration-300"><i class="fas fa-plus-square mr-2"></i> Create New Task</a></p>
    <?php endif; ?>

    <?php if (isset($data['tasks']) && !empty($data['tasks'])): ?>
        <ul class="space-y-4">
            <?php foreach ($data['tasks'] as $task): ?>
                <li id="task<?php echo $task['id'];?>" class="bg-brand-dark p-4 rounded-lg shadow hover:shadow-neon-purple/20 transition-shadow duration-300">
                    <h4 class="text-xl font-semibold text-gray-200"><?php echo htmlspecialchars($task['description']); ?></h4>
                    <div class="text-sm text-gray-400 mt-1">
                        Status: <span class="task-status-text font-semibold <?php
                            switch (strtolower($task['status'] ?? '')) {
                                case 'completed': echo 'text-green-400'; break;
                                case 'pending': echo 'text-yellow-400'; break;
                                case 'in_progress': echo 'text-blue-400'; break;
                                case 'overdue': echo 'text-red-400'; break;
                                default: echo 'text-gray-400';
                            }
                        ?>" id="task-status-<?php echo $task['id']; ?>"><?php echo htmlspecialchars(ucfirst($task['status'])); ?></span> |
                        Due Date: <?php echo htmlspecialchars($task['due_date'] ?? 'N/A'); ?> |
                        Created by: <?php echo htmlspecialchars($task['created_by_name'] ?? 'N/A'); ?>
                    </div>
                    <?php if (!empty($task['assigned_users'])): ?>
                        <p class="text-sm text-gray-500 mt-1">Assigned to:
                        <?php
                        $user_names = [];
                        foreach ($task['assigned_users'] as $assigned_user) {
                            $user_names[] = htmlspecialchars($assigned_user['name']);
                        }
                        echo implode(', ', $user_names);
                        ?>
                        </p>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 mt-1"><em>Not assigned</em></p>
                    <?php endif; ?>

                    <div class="mt-3 space-x-3 text-sm">
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/showEditForm/<?php echo $task['id']; ?>" class="text-yellow-400 hover:text-yellow-300"><i class="fas fa-edit mr-1"></i> Edit</a>
                            <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/showAssignForm/<?php echo $task['id']; ?>" class="text-blue-400 hover:text-blue-300"><i class="fas fa-user-plus mr-1"></i> Assign</a>
                            <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/delete/<?php echo $task['id']; ?>/<?php echo $data['document']['id']; ?>" data-message="Are you sure you want to delete this task?" class="delete-confirm-link text-red-400 hover:text-red-300"><i class="fas fa-trash mr-1"></i> Delete</a>
                        <?php endif; ?>

                        <?php
                        $canUpdate = (isset($task['is_assigned_to_current_user']) && $task['is_assigned_to_current_user']) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser');
                        ?>
                        <?php if ($canUpdate): ?>
                            <?php if ($task['status'] !== 'completed'): ?>
                                <a href="#" class="ajax-update-task-status text-green-400 hover:text-green-300"
                                   data-task-id="<?php echo $task['id']; ?>"
                                   data-new-status="completed"
                                   data-context-id="<?php echo $data['document']['id']; ?>"
                                   data-redirect-context="document"
                                   id="update-link-task-<?php echo $task['id']; ?>-completed"><i class="fas fa-check-circle mr-1"></i> Mark Completed</a>
                            <?php endif; ?>
                            <?php if ($task['status'] === 'completed' || $task['status'] === 'overdue' || $task['status'] === 'in_progress'): ?>
                                <a href="#" class="ajax-update-task-status text-gray-400 hover:text-gray-300"
                                   data-task-id="<?php echo $task['id']; ?>"
                                   data-new-status="pending"
                                   data-context-id="<?php echo $data['document']['id']; ?>"
                                   data-redirect-context="document"
                                   id="update-link-task-<?php echo $task['id']; ?>-pending"><i class="fas fa-clock mr-1"></i> Mark Pending</a>
                            <?php endif; ?>
                             <?php if ($task['status'] === 'pending'): ?>
                                <a href="#" class="ajax-update-task-status text-orange-400 hover:text-orange-300"
                                   data-task-id="<?php echo $task['id']; ?>"
                                   data-new-status="in_progress"
                                   data-context-id="<?php echo $data['document']['id']; ?>"
                                   data-redirect-context="document"
                                   id="update-link-task-<?php echo $task['id']; ?>-inprogress"><i class="fas fa-spinner mr-1"></i> Mark In Progress</a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/showByEntity/task/<?php echo $task['id']; ?>" class="text-gray-500 hover:text-gray-300"><i class="fas fa-comment mr-1"></i> Comments</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-400">No tasks found for this document.</p>
    <?php endif; ?>

    <p class="mt-6"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $data['document']['accreditation_process_id']; ?>" class="text-neon-purple hover:text-purple-400 font-semibold"><i class="fas fa-arrow-left mr-2"></i>Back to Process Details</a></p>

<?php else: ?>
    <p class="text-red-400">Document information not found.</p>
    <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/index">Go to Accreditation Processes</a></p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
