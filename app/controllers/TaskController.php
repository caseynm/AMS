<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../models/User.php';

class TaskController extends BaseController {
    private $taskModel;
    private $documentModel;
    private $userModel;

    public function __construct() {
        // parent::__construct();
        $this->taskModel = new Task();
        $this->documentModel = new Document();
        $this->userModel = new User();
    }

    // canUpdateTask is already in BaseController, but could be overridden if needed
    // For this controller, it's fine as is, checking superuser or assigned user.
    private function isTaskAssignedToCurrentUser($task_id) {
        if (!$this->isLoggedIn()) return false;
        $assignedUsers = $this->taskModel->getAssignedUsersForTask($task_id);
        foreach ($assignedUsers as $user) {
            if ($user['id'] == $this->getCurrentUserId()) {
                return true;
            }
        }
        return false;
    }


    // List tasks for a specific document
    // Route: task/listByDocument/{document_id}
    public function listByDocument($document_id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }

        $document = $this->documentModel->getDocumentById($document_id);
        if (!$document) {
            $this->redirect('accreditation/index&error=Document_not_found_ID_' . $document_id);
            return;
        }

        $tasks_raw = $this->taskModel->getTasksByDocumentId($document_id);
        $tasks_with_details = [];
        foreach ($tasks_raw as $task) {
            $task['assigned_users'] = $this->taskModel->getAssignedUsersForTask($task['id']);
            $task['is_assigned_to_current_user'] = $this->isTaskAssignedToCurrentUser($task['id']);
            $tasks_with_details[] = $task;
        }

        $this->renderView('tasks/list_by_document', [
            'document' => $document,
            'tasks' => $tasks_with_details,
            'pageTitle' => 'Tasks for ' . htmlspecialchars($document['name'])
        ]);
    }

    // Show form to create a new task for a document (Superuser only)
    // Route: task/showCreateForm/{document_id}
    public function showCreateForm($document_id) {
        if (!$this->isSuperUser()) {
            $this->redirect('document/listByProcess/' . $document_id . '&error=Access_denied_Superuser_only'); // Redirect to doc's process if not superuser
            return;
        }
        $document = $this->documentModel->getDocumentById($document_id);
        if (!$document) {
            $this->redirect('accreditation/index&error=Document_not_found_ID_' . $document_id . '_cannot_create_task');
            return;
        }

        $data = [
            'document_id' => $document_id,
            'document_name' => $document['name'],
            'pageTitle' => 'Create Task for ' . htmlspecialchars($document['name'])
        ];
        if(isset($_GET['description'])) $data['description'] = $_GET['description'];
        if(isset($_GET['due_date'])) $data['due_date'] = $_GET['due_date'];
        if(isset($_GET['status'])) $data['status'] = $_GET['status'];


        $this->renderView('tasks/create', $data);
    }

    // Handle creation of a new task (Superuser only)
    // Route: task/create/{document_id}
    public function create($document_id) {
        if (!$this->isSuperUser()) {
            $this->redirect('user/showLoginForm&error=Access_denied_Superuser_only');
            return;
        }
        $document = $this->documentModel->getDocumentById($document_id);
        if (!$document) {
            $this->redirect('accreditation/index&error=Document_not_found_ID_' . $document_id . '_cannot_create_task');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $description = trim($_POST['description'] ?? '');
            $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
            $status = trim($_POST['status'] ?? 'pending');
            $created_by_user_id = $_SESSION['user_id'];

            if (empty($description)) {
                $query_params = http_build_query([
                    'error' => 'Task_description_is_required',
                    'due_date' => $due_date,
                    'status' => $status
                ]);
                $this->redirect('task/showCreateForm/' . $document_id . '&' . $query_params);
                return;
            }

            $taskId = $this->taskModel->createTask($document_id, $description, $due_date, $created_by_user_id, $status);

            if ($taskId) {
                 $this->redirect("task/listByDocument/" . $document_id . "&success=Task_created_successfully_ID_" . $taskId . "#task".$taskId);
            } else {
                $query_params = http_build_query([
                    'error' => 'Could_not_create_task_DB_error',
                    'description' => $description,
                    'due_date' => $due_date,
                    'status' => $status
                ]);
                $this->redirect('task/showCreateForm/' . $document_id . '&' . $query_params);
            }
        } else {
            $this->redirect("task/showCreateForm/" . $document_id);
        }
    }

    // Show form to edit a task (Superuser only)
    // Route: task/showEditForm/{task_id}
    public function showEditForm($task_id) {
        if (!$this->isSuperUser()) {
            // Need document_id to redirect back to task list. Fetch task first.
            $task = $this->taskModel->getTaskById($task_id);
            $redirect_url = $task ? 'task/listByDocument/' . $task['document_id'] : 'home/index';
            $this->redirect($redirect_url . '&error=Access_denied_Superuser_only');
            return;
        }
        $task = $this->taskModel->getTaskById($task_id);
        if (!$task) {
            $this->redirect('home/index&error=Task_not_found_ID_' . $task_id);
            return;
        }
        $this->renderView('tasks/edit', ['task' => $task, 'pageTitle' => 'Edit Task']);
    }

    // Handle update of a task (Superuser only)
    // Route: task/update/{task_id}
    public function update($task_id) {
        if (!$this->isSuperUser()) {
            $task = $this->taskModel->getTaskById($task_id);
            $redirect_url = $task ? 'task/listByDocument/' . $task['document_id'] : 'home/index';
            $this->redirect($redirect_url . '&error=Access_denied_Superuser_only');
            return;
        }
        $task = $this->taskModel->getTaskById($task_id);
        if (!$task) {
            $this->redirect('home/index&error=Task_not_found_cannot_update_ID_' . $task_id);
            return;
        }
        $document_id = $task['document_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $description = trim($_POST['description'] ?? '');
            $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
            $status = trim($_POST['status'] ?? 'pending');

            if (empty($description)) {
                $this->redirect("task/showEditForm/" . $task_id . "&error=Task_description_is_required");
                return;
            }
            $success = $this->taskModel->updateTaskDetails($task_id, $description, $due_date, $status);
            if ($success) {
                $this->redirect("task/listByDocument/" . $document_id . "&success=Task_updated_successfully" . "#task".$task_id);
            } else {
                $this->redirect("task/showEditForm/" . $task_id . "&error=Could_not_update_task_DB_error");
            }
        } else {
            $this->redirect("task/showEditForm/" . $task_id);
        }
    }


    // Show form to assign a task to users (Superuser only)
    // Route: task/showAssignForm/{task_id}
    public function showAssignForm($task_id) {
        if (!$this->isSuperUser()) {
            $task = $this->taskModel->getTaskById($task_id);
            $redirect_url = $task ? 'task/listByDocument/' . $task['document_id'] : 'home/index';
            $this->redirect($redirect_url . '&error=Access_denied_Superuser_only');
            return;
        }
        $task = $this->taskModel->getTaskById($task_id);
        if (!$task) {
            $this->redirect('home/index&error=Task_not_found_ID_' . $task_id);
            return;
        }
        $allUsers = $this->userModel->getAllUsers();
        $assignedUserObjects = $this->taskModel->getAssignedUsersForTask($task_id);
        $assignedUserIds = array_map(function($u) { return $u['id']; }, $assignedUserObjects);

        $this->renderView('tasks/assign', [
            'task' => $task,
            'allUsers' => $allUsers,
            'assignedUserIds' => $assignedUserIds,
            'pageTitle' => 'Assign Task: ' . htmlspecialchars($task['description'])
        ]);
    }

    // Handle assigning a task to users (Superuser only)
    // Route: task/assign/{task_id}
    public function assign($task_id) {
        if (!$this->isSuperUser()) {
            $task = $this->taskModel->getTaskById($task_id);
            $redirect_url = $task ? 'task/listByDocument/' . $task['document_id'] : 'home/index';
            $this->redirect($redirect_url . '&error=Access_denied_Superuser_only');
            return;
        }
        $task = $this->taskModel->getTaskById($task_id);
        if (!$task) {
            $this->redirect('home/index&error=Task_not_found_cannot_assign_ID_' . $task_id);
            return;
        }
        $document_id = $task['document_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_ids = $_POST['user_ids'] ?? [];
            $success = $this->taskModel->assignTaskToMultipleUsers($task_id, $user_ids);

            if ($success) {
                $this->redirect("task/listByDocument/" . $document_id . "&success=Task_assignment_updated" . "#task".$task_id);
            } else {
                $this->redirect("task/showAssignForm/" . $task_id . "&error=Could_not_update_task_assignments_DB_error");
            }
        } else {
            $this->redirect("task/showAssignForm/" . $task_id);
        }
    }

    // Update task status (e.g., mark as complete by assigned user or superuser)
    // Route: task/updateStatus/{task_id}/{new_status}/{context_id_for_redirect}/{redirect_context}
    public function updateStatus($task_id, $new_status, $context_id, $redirect_context = 'document') {
        $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if (!$this->isLoggedIn()) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                http_response_code(401); // Unauthorized
                echo json_encode(['success' => false, 'message' => 'Login required.']);
                exit;
            }
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }

        $task = $this->taskModel->getTaskById($task_id);
        if (!$task) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                http_response_code(404); // Not Found
                echo json_encode(['success' => false, 'message' => 'Task not found.']);
                exit;
            }
            $this->redirect('home/index&error=Task_not_found_ID_' . $task_id);
            return;
        }

        if (!($this->isTaskAssignedToCurrentUser($task_id) || $this->isSuperUser())) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                http_response_code(403); // Forbidden
                echo json_encode(['success' => false, 'message' => 'Not authorized to update this task.']);
                exit;
            }
            $redirect_url = ($redirect_context === 'document') ? 'task/listByDocument/' . $context_id : 'task/myTasks';
            $this->redirect($redirect_url . '&error=Not_authorized_to_update_task_status');
            return;
        }

        $allowed_statuses = ['pending', 'in_progress', 'completed', 'overdue'];
        if (!in_array($new_status, $allowed_statuses)) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                http_response_code(400); // Bad Request
                echo json_encode(['success' => false, 'message' => 'Invalid task status provided.']);
                exit;
            }
            $redirect_url = ($redirect_context === 'document') ? 'task/listByDocument/' . $context_id : 'task/myTasks';
            $this->redirect($redirect_url . '&error=Invalid_task_status_' . $new_status);
            return;
        }

        $success = $this->taskModel->updateTaskStatus($task_id, $new_status);
        $message = $success ? "Task status updated to " . htmlspecialchars($new_status) . "." : "Could not update task status.";

        if ($is_ajax) {
            header('Content-Type: application/json');
            if ($success) {
                echo json_encode(['success' => true, 'message' => $message, 'new_status' => $new_status, 'task_id' => (int)$task_id]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['success' => false, 'message' => $message, 'task_id' => (int)$task_id]);
            }
            exit;
        } else {
            // Non-AJAX: Redirect as before
            $redirect_url_base = ($redirect_context === 'document') ? "task/listByDocument/" . $context_id : "task/myTasks";
            if ($success) {
                $this->redirect($redirect_url_base . "&success=" . urlencode($message) . "#task".$task_id);
            } else {
                $this->redirect($redirect_url_base . "&error=" . urlencode($message) . "#task".$task_id);
            }
        }
    }

    public function myTasks() {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }
        $user_id = $_SESSION['user_id'];
        $tasks = $this->taskModel->getTasksByUserId($user_id);
        $this->renderView('tasks/my_tasks', ['tasks' => $tasks, 'pageTitle' => 'My Tasks']);
    }

    public function delete($task_id, $document_id_for_redirect) {
        if (!$this->isSuperUser()) {
            $this->redirect('task/listByDocument/' . $document_id_for_redirect . '&error=Access_denied_Superuser_only' . "#task".$task_id);
            return;
        }

        $task = $this->taskModel->getTaskById($task_id);
        if (!$task) {
             $this->redirect('task/listByDocument/' . $document_id_for_redirect . '&error=Task_not_found_cannot_delete_ID_' . $task_id);
            return;
        }
        // Use document_id from the task itself for robustness
        $doc_id_redirect = $task['document_id'] ?? $document_id_for_redirect;

        $success = $this->taskModel->deleteTask($task_id);

        if ($success) {
            $this->redirect("task/listByDocument/" . $doc_id_redirect . "&success=Task_deleted_successfully_ID_" . $task_id);
        } else {
            $this->redirect("task/listByDocument/" . $doc_id_redirect . "&error=Could_not_delete_task_ID_" . $task_id . "_DB_error_or_dependencies" . "#task".$task_id);
        }
    }
}
?>
