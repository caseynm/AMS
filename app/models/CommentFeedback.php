<?php
require_once __DIR__ . '/../../config/database.php';

class CommentFeedback {
    private $pdo;

    public function __construct() {
        global $pdo; // Use the global PDO connection
        $this->pdo = $pdo;
    }

    // Add a new comment or feedback to an entity
    // $entity_type can be 'process', 'document', or 'task'
    // $entity_id is the ID of the specific process, document, or task
    public function addComment($entity_type, $entity_id, $user_id, $comment_text) {
        $allowed_entity_types = ['process', 'document', 'task'];
        if (!in_array($entity_type, $allowed_entity_types)) {
            error_log("AddComment error: Invalid entity_type provided: " . $entity_type);
            return false;
        }

        $sql = "INSERT INTO comments_feedback (entity_type, entity_id, user_id, comment_text)
                VALUES (:entity_type, :entity_id, :user_id, :comment_text)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':entity_type', $entity_type);
            $stmt->bindParam(':entity_id', $entity_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':comment_text', $comment_text);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Check for foreign key constraint violation on user_id, entity_id (if such constraints were possible/added via triggers)
            if ($e->getCode() == '23000') {
                 error_log("AddComment error: Foreign key constraint violation. User ID {$user_id} or Entity {$entity_type}:{$entity_id} may not exist. " . $e->getMessage());
            } else {
                error_log("AddComment error: " . $e->getMessage());
            }
            return false;
        }
    }

    // Get all comments for a specific entity
    public function getCommentsByEntity($entity_type, $entity_id) {
        $allowed_entity_types = ['process', 'document', 'task'];
        if (!in_array($entity_type, $allowed_entity_types)) {
            error_log("GetCommentsByEntity error: Invalid entity_type provided: " . $entity_type);
            return [];
        }

        $sql = "SELECT cf.*, u.name as user_name
                FROM comments_feedback cf
                LEFT JOIN users u ON cf.user_id = u.id -- Use LEFT JOIN in case user is deleted and user_id is SET NULL
                WHERE cf.entity_type = :entity_type AND cf.entity_id = :entity_id
                ORDER BY cf.created_at ASC"; // Show oldest comments first, or DESC for newest
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':entity_type', $entity_type);
            $stmt->bindParam(':entity_id', $entity_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetCommentsByEntity error for {$entity_type} {$entity_id}: " . $e->getMessage());
            return [];
        }
    }

    // (Optional) Delete a comment - could be restricted to comment owner or superuser
    public function deleteComment($comment_id, $requesting_user_id, $requesting_user_role) {
        $comment = $this->getCommentById($comment_id);
        if (!$comment) {
            error_log("DeleteComment error: Comment ID {$comment_id} not found.");
            return false;
        }

        // Allow deletion if user is superuser or the owner of the comment
        if ($requesting_user_role === 'superuser' || $comment['user_id'] == $requesting_user_id) {
            $sql = "DELETE FROM comments_feedback WHERE id = :id";
            try {
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id', $comment_id, PDO::PARAM_INT);
                return $stmt->execute();
            } catch (PDOException $e) {
                error_log("DeleteComment error for comment ID {$comment_id}: " . $e->getMessage());
                return false;
            }
        }
        error_log("DeleteComment error: User ID {$requesting_user_id} (Role: {$requesting_user_role}) not authorized to delete comment ID {$comment_id} owned by User ID {$comment['user_id']}.");
        return false; // Not authorized
    }

    // Helper function to get a single comment by its ID (used for deletion check)
    public function getCommentById($comment_id) {
        $sql = "SELECT * FROM comments_feedback WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $comment_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetCommentById error for comment ID {$comment_id}: " . $e->getMessage());
            return false;
        }
    }
}
?>
