<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php if (isset($data['entity_type_display']) && isset($data['entity_name_display']) && isset($data['entity_type']) && isset($data['entity_id']) && isset($data['back_link'])): ?>
    <h3 class="text-2xl font-semibold text-neon-purple mb-4">Comments/Feedback for <?php echo htmlspecialchars($data['entity_type_display']); ?>: <span class="text-gray-200">"<?php echo htmlspecialchars($data['entity_name_display']); ?>"</span></h3>

    <div id="comments-list-container" class="space-y-4 mb-6">
        <?php if (isset($data['comments']) && !empty($data['comments'])): ?>
            <?php foreach ($data['comments'] as $comment): ?>
                <div class="bg-brand-dark p-4 rounded-lg shadow">
                    <p class="text-gray-300"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                    <p class="text-xs text-gray-500 mt-2">
                        By: <strong><?php echo htmlspecialchars($comment['user_name'] ?? 'Anonymous'); ?></strong>
                        on <em><?php echo htmlspecialchars(date("M d, Y H:i", strtotime($comment['created_at']))); ?></em>
                        <?php if (isset($_SESSION['user_id']) && ( (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser') || $_SESSION['user_id'] == $comment['user_id'])): ?>
                            <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/delete/<?php echo $comment['id']; ?>/<?php echo $data['entity_type']; ?>/<?php echo $data['entity_id']; ?>" data-message="Are you sure you want to delete this comment?" class="delete-confirm-link text-red-400 hover:text-red-600 ml-2"><i class="fas fa-times-circle mr-1"></i> Delete</a>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-400">No comments yet for this <?php echo htmlspecialchars($data['entity_type_display']); ?>.</p>
        <?php endif; ?>
    </div>

    <h4 class="text-xl font-semibold text-gray-200 mt-6 mb-3">Add a New Comment:</h4>
    <form id="ajax-comment-form" action="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/create/<?php echo $data['entity_type']; ?>/<?php echo $data['entity_id']; ?>" method="POST" class="bg-brand-gray p-4 rounded-lg shadow">
        <div>
            <textarea name="comment_text" rows="4" required class="w-full p-3 bg-brand-dark border border-gray-600 text-gray-200 rounded focus:border-neon-purple focus:ring-1 focus:ring-neon-purple outline-none transition-colors" placeholder="Write your comment..."></textarea>
        </div>
        <button type="submit" class="mt-3 w-full md:w-auto bg-neon-purple text-white hover:bg-purple-700 font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out transform hover:scale-105"><i class="fas fa-paper-plane mr-2"></i>Submit Comment</button>
    </form>
    <p class="mt-6"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?><?php echo htmlspecialchars($data['back_link']); // Controller provides path like 'controller/action/param' ?>" class="text-neon-purple hover:text-purple-400"><i class="fas fa-arrow-left mr-2"></i>Back to <?php echo htmlspecialchars($data['entity_type_display']); ?></a></p>
<?php else: ?>
    <p class="text-red-400">Error: Entity information for comments is missing.</p>
    <p><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>home/index" class="text-neon-purple hover:text-purple-400">Return to Dashboard</a></p>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
