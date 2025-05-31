<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <?php if (isset($data['entity_type_display']) && isset($data['entity_name_display']) && isset($data['entity_type']) && isset($data['entity_id']) && isset($data['back_link'])): ?>
        <h3 class="text-2xl font-bold text-black mb-6">Comments/Feedback for <?php echo htmlspecialchars($data['entity_type_display']); ?>: <span class="font-semibold">"<?php echo htmlspecialchars($data['entity_name_display']); ?>"</span></h3>

        <div class="bg-white p-6 rounded-lg shadow-md border border-black mb-6">
            <h4 class="text-xl font-semibold text-black mb-3">Add a New Comment:</h4>
            <form id="ajax-comment-form" action="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/create/<?php echo $data['entity_type']; ?>/<?php echo $data['entity_id']; ?>" method="POST">
                <div>
                    <textarea name="comment_text" rows="3" class="mt-1 block w-full px-3 py-2 bg-white border border-black rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black" placeholder="Write your comment..." required></textarea>
                </div>
                <button type="submit" class="mt-2 bg-black text-white px-3 py-1.5 rounded-md text-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"><i class="fas fa-paper-plane mr-2"></i>Submit Comment</button>
            </form>
        </div>

        <h4 class="text-xl font-semibold text-black mb-4">Existing Comments:</h4>
        <div id="comments-list-container" class="space-y-4">
            <?php if (isset($data['comments']) && !empty($data['comments'])): ?>
                <?php foreach ($data['comments'] as $comment): ?>
                    <div class="bg-white p-4 rounded-lg shadow border border-black">
                        <p class="font-semibold text-black text-sm"><?php echo htmlspecialchars($comment['user_name'] ?? 'Anonymous'); ?></p>
                        <p class="text-xs text-gray-500 mb-1"><?php echo htmlspecialchars(date("M d, Y H:i", strtotime($comment['created_at']))); ?></p>
                        <p class="text-black text-sm whitespace-pre-wrap"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                        <?php if (isset($_SESSION['user_id']) && ( (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser') || $_SESSION['user_id'] == $comment['user_id'])): ?>
                            <div class="mt-2 text-right">
                                <a href="#" data-href="<?php echo htmlspecialchars($APP_BASE_URL); ?>comment/delete/<?php echo $comment['id']; ?>/<?php echo $data['entity_type']; ?>/<?php echo $data['entity_id']; ?>" data-message="Are you sure you want to delete this comment?" class="delete-confirm-link text-red-600 hover:text-red-800 underline text-xs ml-2"><i class="fas fa-times-circle mr-1"></i> Delete</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600 text-sm">No comments yet for this <?php echo htmlspecialchars($data['entity_type_display']); ?>.</p>
            <?php endif; ?>
        </div>

        <p class="mt-8"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?><?php echo htmlspecialchars($data['back_link']); // Controller provides path like 'controller/action/param' ?>" class="text-black hover:underline"><i class="fas fa-arrow-left mr-2"></i>Back to <?php echo htmlspecialchars($data['entity_type_display']); ?></a></p>
    <?php else: ?>
        <p class="text-red-600 text-center">Error: Entity information for comments is missing.</p>
        <p class="text-center mt-2"><a href="<?php echo htmlspecialchars($APP_BASE_URL); ?>home/index" class="text-black hover:underline">Return to Dashboard</a></p>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
