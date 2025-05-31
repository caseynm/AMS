<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/CommentFeedback.php';
require_once __DIR__ . '/../models/AccreditationProcess.php';
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../models/Task.php';

class CommentController extends BaseController {
    private $commentFeedbackModel;
    private $accreditationProcessModel;
    private $documentModel;
    private $taskModel;

    public function __construct() {
        // parent::__construct();
        $this->commentFeedbackModel = new CommentFeedback();
        $this->accreditationProcessModel = new AccreditationProcess();
        $this->documentModel = new Document();
        $this->taskModel = new Task();
    }

    private function getEntityDetails($entity_type, $entity_id) {
        $details = ['name' => 'N/A', 'back_link_url' => 'home/index', 'type_display' => ucfirst($entity_type)];

        if ($entity_type === 'process') {
            $process = $this->accreditationProcessModel->getProcessById($entity_id);
            if ($process) {
                $details['name'] = $process['title'];
                $details['back_link_url'] = "accreditation/show/" . $entity_id;
            } else { return null; } // Entity not found
        } elseif ($entity_type === 'document') {
            $document = $this->documentModel->getDocumentById($entity_id);
            if ($document) {
                $details['name'] = $document['name'];
                $details['back_link_url'] = "accreditation/show/" . $document['accreditation_process_id'] . "#doc" . $entity_id;
                 // Link to the process page where this document is listed
            } else { return null; }
        } elseif ($entity_type === 'task') {
            $task = $this->taskModel->getTaskById($entity_id);
            if ($task) {
                $details['name'] = $task['description'];
                $details['back_link_url'] = "task/listByDocument/" . $task['document_id'] . "#task" . $entity_id;
            } else { return null; }
        } else {
            return null; // Invalid entity type
        }
        return $details;
    }

    public function showByEntity($entity_type, $entity_id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }

        $entityDetails = $this->getEntityDetails($entity_type, $entity_id);

        if (!$entityDetails) {
            $this->redirect('home/index&error=Invalid_entity_type_or_ID_for_comments');
            return;
        }

        $comments = $this->commentFeedbackModel->getCommentsByEntity($entity_type, $entity_id);

        $this->renderView('comments/show_by_entity', [
            'comments' => $comments,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'entity_type_display' => $entityDetails['type_display'],
            'entity_name_display' => $entityDetails['name'],
            'back_link' => "/index.php?url=" . $entityDetails['back_link_url'], // Full path for view
            'pageTitle' => 'Comments for ' . $entityDetails['type_display'] . ': ' . htmlspecialchars($entityDetails['name'])
        ]);
    }

    public function create($entity_type, $entity_id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }

        $entityDetails = $this->getEntityDetails($entity_type, $entity_id);
        if (!$entityDetails) {
            $this->redirect('home/index&error=Invalid_entity_for_creating_comment');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $comment_text = trim($_POST['comment_text'] ?? '');
            $user_id = $_SESSION['user_id'];

            if (empty($comment_text)) {
                $this->redirect("comment/showByEntity/" . $entity_type . "/" . $entity_id . "&error=Comment_text_cannot_be_empty");
                return;
            }

            $commentId = $this->commentFeedbackModel->addComment($entity_type, $entity_id, $user_id, $comment_text);

            if ($commentId) {
                $this->redirect("comment/showByEntity/" . $entity_type . "/" . $entity_id . "&success=Comment_added_successfully");
            } else {
                $this->redirect("comment/showByEntity/" . $entity_type . "/" . $entity_id . "&error=Could_not_add_comment_DB_error");
            }
        } else {
            $this->redirect("comment/showByEntity/" . $entity_type . "/" . $entity_id);
        }
    }

    public function delete($comment_id, $entity_type_for_redirect, $entity_id_for_redirect) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }

        if (!filter_var($comment_id, FILTER_VALIDATE_INT) || $comment_id <= 0) {
             $this->redirect("comment/showByEntity/" . $entity_type_for_redirect . "/" . $entity_id_for_redirect . "&error=Invalid_comment_ID_for_deletion");
             return;
        }

        // Fetch comment to check ownership if user is not superuser
        $comment = $this->commentFeedbackModel->getCommentById($comment_id);
        if (!$comment) {
             $this->redirect("comment/showByEntity/" . $entity_type_for_redirect . "/" . $entity_id_for_redirect . "&error=Comment_not_found_cannot_delete");
             return;
        }

        // Authorization check
        if (!($this->isSuperUser() || $_SESSION['user_id'] == $comment['user_id'])) {
            $this->redirect("comment/showByEntity/" . $entity_type_for_redirect . "/" . $entity_id_for_redirect . "&error=Not_authorized_to_delete_this_comment");
            return;
        }

        $success = $this->commentFeedbackModel->deleteComment($comment_id, $_SESSION['user_id'], $_SESSION['user_role']);

        if ($success) {
            $this->redirect("comment/showByEntity/" . $entity_type_for_redirect . "/" . $entity_id_for_redirect . "&success=Comment_deleted_successfully");
        } else {
            // The model logs specific errors, this is a general fallback.
            $this->redirect("comment/showByEntity/" . $entity_type_for_redirect . "/" . $entity_id_for_redirect . "&error=Could_not_delete_comment_or_not_authorized");
        }
    }
}
?>
