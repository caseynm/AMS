<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php if (isset($data['document'])): ?>
    <h2>Tasks for Document: <?php echo htmlspecialchars($data['document']['name']); ?></h2>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
        <p><a href="/index.php?url=task/showCreateForm/<?php echo $data['document']['id']; ?>">Create New Task for this Document</a></p>
    <?php endif; ?>

    <?php if (isset($data['tasks']) && !empty($data['tasks'])): ?>
        <ul>
            <?php foreach ($data['tasks'] as $task): ?>
                <li id="task<?php echo $task['id'];?>">
                    <strong><?php echo htmlspecialchars($task['description']); ?></strong><br>
                    Status: <?php echo htmlspecialchars($task['status']); ?><br>
                    Due Date: <?php echo htmlspecialchars($task['due_date'] ?? 'N/A'); ?><br>
                    Created by: <?php echo htmlspecialchars($task['created_by_name'] ?? 'N/A'); ?><br>
                    Assigned to:
                    <?php if (!empty($task['assigned_users'])): ?>
                        <?php
                        $user_names = [];
                        foreach ($task['assigned_users'] as $assigned_user) {
                            $user_names[] = htmlspecialchars($assigned_user['name']);
                        }
                        echo implode(', ', $user_names);
                        ?>
                    <?php else: ?>
                        <em>Not assigned</em>
                    <?php endif; ?>
                    <br>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser'): ?>
                        <a href="/index.php?url=task/showEditForm/<?php echo $task['id']; ?>">Edit</a> |
                        <a href="/index.php?url=task/showAssignForm/<?php echo $task['id']; ?>">Assign</a> |
                        <a href="/index.php?url=task/delete/<?php echo $task['id']; ?>/<?php echo $data['document']['id']; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a> |
                    <?php endif; ?>

                    <?php
                    $canUpdate = (isset($task['is_assigned_to_current_user']) && $task['is_assigned_to_current_user']) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser');
                    ?>
                    <?php if ($canUpdate): ?>
                        <?php if ($task['status'] !== 'completed'): ?>
                            <a href="/index.php?url=task/updateStatus/<?php echo $task['id']; ?>/completed/<?php echo $data['document']['id']; ?>/document">Mark Completed</a> |
                        <?php endif; ?>
                        <?php if ($task['status'] === 'completed' || $task['status'] === 'overdue'): ?>
                            <a href="/index.php?url=task/updateStatus/<?php echo $task['id']; ?>/pending/<?php echo $data['document']['id']; ?>/document">Mark Pending</a> |
                        <?php endif; ?>
                         <?php if ($task['status'] === 'pending'): ?>
                            <a href="/index.php?url=task/updateStatus/<?php echo $task['id']; ?>/in_progress/<?php echo $data['document']['id']; ?>/document">Mark In Progress</a> |
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="/index.php?url=comment/showByEntity/task/<?php echo $task['id']; ?>">Comments</a>
                </li>
                <br>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tasks found for this document.</p>
    <?php endif; ?>

    <p><a href="/index.php?url=accreditation/show/<?php echo $data['document']['accreditation_process_id']; ?>">Back to Process Details</a></p>

<?php else: ?>
    <p>Document information not found.</p>
    <p><a href="/index.php?url=accreditation/index">Go to Accreditation Processes</a></p>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
