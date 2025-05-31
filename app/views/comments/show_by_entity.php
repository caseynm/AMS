<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php if (isset($data['entity_type_display']) && isset($data['entity_name_display']) && isset($data['entity_type']) && isset($data['entity_id']) && isset($data['back_link'])): ?>
    <h3>Comments/Feedback for <?php echo htmlspecialchars($data['entity_type_display']); ?>: "<?php echo htmlspecialchars($data['entity_name_display']); ?>"</h3>

    <?php if (isset($data['comments']) && !empty($data['comments'])): ?>
        <ul id="comments-list">
            <?php foreach ($data['comments'] as $comment): ?>
                <li>
                    <strong><?php echo htmlspecialchars($comment['user_name'] ?? 'Anonymous'); ?></strong>
                    <em>(<?php echo htmlspecialchars(date("M d, Y H:i", strtotime($comment['created_at']))); ?>)</em>:
                    <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                    <?php if (isset($_SESSION['user_id']) && ( (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser') || $_SESSION['user_id'] == $comment['user_id'])): ?>
                        <a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=comment/delete/<?php echo $comment['id']; ?>/<?php echo $data['entity_type']; ?>/<?php echo $data['entity_id']; ?>" onclick="return confirm('Are you sure you want to delete this comment?');">Delete Comment</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No comments yet for this <?php echo htmlspecialchars($data['entity_type_display']); ?>.</p>
    <?php endif; ?>

    <h4>Add a New Comment:</h4>
    <form action="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=comment/create/<?php echo $data['entity_type']; ?>/<?php echo $data['entity_id']; ?>" method="POST">
        <div>
            <textarea name="comment_text" rows="4" style="width:80%;" required></textarea>
        </div>
        <button type="submit">Submit Comment</button>
    </form>
    <br>
    <p><a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=<?php echo htmlspecialchars($data['back_link']); ?>">Back to <?php echo htmlspecialchars($data['entity_type_display']); ?></a></p>
<?php else: ?>
    <p>Error: Entity information for comments is missing.</p>
    <p><a href="<?php echo htmlspecialchars($BASE_PATH); ?>index.php?url=home/index">Return to Dashboard</a></p>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
