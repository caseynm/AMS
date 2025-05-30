<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>My Assigned Tasks</h2>
<?php if (isset($data['tasks']) && !empty($data['tasks'])): ?>
    <ul>
        <?php foreach ($data['tasks'] as $task): ?>
            <li>
                <strong>Process:</strong> <a href="/index.php?url=accreditation/show/<?php echo $task['accreditation_process_id']; ?>"><?php echo htmlspecialchars($task['process_title']); ?></a><br>
                <strong>Document:</strong> <a href="/index.php?url=document/listByProcess/<?php echo $task['accreditation_process_id']; ?>#doc<?php echo $task['document_id'];?>"><?php echo htmlspecialchars($task['document_name']); ?></a>
                 ( <a href="/index.php?url=task/listByDocument/<?php echo $task['document_id']; ?>#task<?php echo $task['id'];?>">View all tasks for this doc</a> )<br>
                <strong>Task:</strong> <?php echo htmlspecialchars($task['description']); ?><br>
                Status: <?php echo htmlspecialchars($task['status']); ?><br>
                Due Date: <?php echo htmlspecialchars($task['due_date'] ?? 'N/A'); ?><br>
                <?php if ($task['status'] !== 'completed'): ?>
                    <a href="/index.php?url=task/updateStatus/<?php echo $task['id']; ?>/completed/<?php echo $_SESSION['user_id']; ?>/mytasks">Mark as Completed</a> |
                <?php else: ?>
                    <a href="/index.php?url=task/updateStatus/<?php echo $task['id']; ?>/pending/<?php echo $_SESSION['user_id']; ?>/mytasks">Mark as Pending</a> |
                <?php endif; ?>
                 <?php if ($task['status'] === 'pending'): ?>
                    <a href="/index.php?url=task/updateStatus/<?php echo $task['id']; ?>/in_progress/<?php echo $_SESSION['user_id']; ?>/mytasks">Mark In Progress</a> |
                <?php endif; ?>
                 <a href="/index.php?url=comment/showByEntity/task/<?php echo $task['id']; ?>">Comments</a>
            </li>
            <br>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You have no tasks assigned to you.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
