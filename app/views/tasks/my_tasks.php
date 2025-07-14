<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>My Assigned Tasks</h2>
<?php if (isset($data['tasks']) && !empty($data['tasks'])): ?>
    <ul>
        <?php foreach ($data['tasks'] as $task): ?>
            <li>
                <strong>Process:</strong> <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $task['accreditation_process_id']; ?>"><?php echo htmlspecialchars($task['process_title']); ?></a><br>
                <strong>Document:</strong> <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>accreditation/show/<?php echo $task['accreditation_process_id']; ?>#doc<?php echo $task['document_id'];?>"><?php echo htmlspecialchars($task['document_name']); ?></a>
                 ( <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>task/listByDocument/<?php echo $task['document_id']; ?>#task<?php echo $task['id'];?>">View all tasks for this doc</a> )<br>
                <strong>Task:</strong> <?php echo htmlspecialchars($task['description']); ?><br>
                Status: <span class="task-status-text" id="task-status-<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['status']); ?></span><br>
                Due Date: <?php echo htmlspecialchars($task['due_date'] ?? 'N/A'); ?><br>
                <?php if ($task['status'] !== 'completed'): ?>
                    <a href="#" class="ajax-update-task-status"
                       data-task-id="<?php echo $task['id']; ?>"
                       data-new-status="completed"
                       data-context-id="<?php echo $_SESSION['user_id']; ?>"
                       data-redirect-context="mytasks"
                       id="update-link-task-<?php echo $task['id']; ?>-completed">Mark as Completed</a> |
                <?php else: ?>
                    <a href="#" class="ajax-update-task-status"
                       data-task-id="<?php echo $task['id']; ?>"
                       data-new-status="pending"
                       data-context-id="<?php echo $_SESSION['user_id']; ?>"
                       data-redirect-context="mytasks"
                       id="update-link-task-<?php echo $task['id']; ?>-pending">Mark as Pending</a> |
                <?php endif; ?>
                 <?php if ($task['status'] === 'pending'): ?>
                    <a href="#" class="ajax-update-task-status"
                       data-task-id="<?php echo $task['id']; ?>"
                       data-new-status="in_progress"
                       data-context-id="<?php echo $_SESSION['user_id']; ?>"
                       data-redirect-context="mytasks"
                       id="update-link-task-<?php echo $task['id']; ?>-inprogress">Mark In Progress</a> |
                <?php endif; ?>
                 <a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/showByEntity/task/<?php echo $task['id']; ?>">Comments</a>
            </li>
            <br>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You have no tasks assigned to you.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
