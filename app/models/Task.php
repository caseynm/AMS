<?php
require_once __DIR__ . '/../../config/database.php';

class Task {
    private $pdo;

    public function __construct() {
        global $pdo; // Use the global PDO connection
        $this->pdo = $pdo;
    }

    // Create a new task for a document
    public function createTask($document_id, $description, $due_date, $created_by_user_id, $status = 'pending') {
        $sql = "INSERT INTO tasks (document_id, description, due_date, created_by_user_id, status)
                VALUES (:document_id, :description, :due_date, :created_by_user_id, :status)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':document_id', $document_id, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':due_date', $due_date);
            $stmt->bindParam(':created_by_user_id', $created_by_user_id, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("CreateTask error: " . $e->getMessage());
            return false;
        }
    }

    // Get a task by its ID
    public function getTaskById($id) {
        $sql = "SELECT t.*, u.name as created_by_name, d.name as document_name, d.accreditation_process_id
                FROM tasks t
                LEFT JOIN users u ON t.created_by_user_id = u.id
                LEFT JOIN documents d ON t.document_id = d.id
                WHERE t.id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetTaskById error: " . $e->getMessage());
            return false;
        }
    }

    // Get all tasks for a specific document
    public function getTasksByDocumentId($document_id) {
        $sql = "SELECT t.*, u.name as created_by_name
                FROM tasks t
                LEFT JOIN users u ON t.created_by_user_id = u.id
                WHERE t.document_id = :document_id
                ORDER BY t.created_at DESC";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':document_id', $document_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetTasksByDocumentId error: " . $e->getMessage());
            return [];
        }
    }

    // Update an existing task's status
    public function updateTaskStatus($id, $status) {
        $sql = "UPDATE tasks SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("UpdateTaskStatus error: " . $e->getMessage());
            return false;
        }
    }

    // Update an existing task's details (description, due_date, status)
    public function updateTaskDetails($id, $description, $due_date, $status) {
        $sql = "UPDATE tasks SET description = :description, due_date = :due_date, status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':due_date', $due_date);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("UpdateTaskDetails error: " . $e->getMessage());
            return false;
        }
    }


    // Delete a task
    public function deleteTask($id) {
        // Related task_assignments will be deleted by DB constraint (ON DELETE CASCADE)
        // if not, uncomment the explicit call to removeAllAssignmentsForTask
        $sql = "DELETE FROM tasks WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            // $this->removeAllAssignmentsForTask($id); // Called to be sure, or if ON DELETE CASCADE is not set on task_assignments.task_id
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("DeleteTask error: " . $e->getMessage());
             // Check for foreign key constraint violation if applicable (e.g. comments related to this task if not cascaded)
            if ($e->getCode() == '23000') {
                 error_log("DeleteTask error: Cannot delete task due to related records (e.g. comments).");
                 return false;
            }
            return false;
        }
    }

    // ---- Task Assignment Methods ----

    // Assign a task to a user
    public function assignTaskToUser($task_id, $user_id) {
        $sql = "INSERT INTO task_assignments (task_id, user_id) VALUES (:task_id, :user_id)
                ON DUPLICATE KEY UPDATE task_id = :task_id, user_id = :user_id"; // Handles if already assigned
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("AssignTaskToUser error: " . $e->getMessage());
            return false;
        }
    }

    // Assign a task to multiple users
    public function assignTaskToMultipleUsers($task_id, $user_ids_array) {
        // This function assumes you want to REPLACE existing assignments with the new set.
        // So, first remove all, then add new ones.
        $this->pdo->beginTransaction();
        try {
            $this->removeAllAssignmentsForTask($task_id); // Clear existing assignments

            if (!empty($user_ids_array)) { // Only proceed if there are users to assign
                $sql = "INSERT INTO task_assignments (task_id, user_id) VALUES (:task_id, :user_id)";
                // ON DUPLICATE KEY UPDATE is not strictly needed here if removeAllAssignmentsForTask works,
                // but kept for safety / alternative logic.
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);

                foreach ($user_ids_array as $user_id) {
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    if (!$stmt->execute()) {
                        // If execute fails, it might be due to duplicate if ON DUPLICATE KEY was removed
                        // or other integrity constraint (e.g. user_id not in users table)
                        throw new PDOException("Failed to assign task to user ID: $user_id for task ID: $task_id");
                    }
                }
            }
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("AssignTaskToMultipleUsers error for task $task_id: " . $e->getMessage());
            return false;
        }
    }


    // Remove a specific user assignment from a task
    public function removeUserFromTask($task_id, $user_id) {
        $sql = "DELETE FROM task_assignments WHERE task_id = :task_id AND user_id = :user_id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("RemoveUserFromTask error: " . $e->getMessage());
            return false;
        }
    }

    // Remove all assignments for a specific task (used when deleting a task or re-assigning)
    public function removeAllAssignmentsForTask($task_id) {
        $sql = "DELETE FROM task_assignments WHERE task_id = :task_id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("RemoveAllAssignmentsForTask error: " . $e->getMessage());
            return false;
        }
    }

    // Get all tasks assigned to a specific user
    public function getTasksByUserId($user_id) {
        $sql = "SELECT t.*, d.name as document_name, d.accreditation_process_id, ap.title as process_title
                FROM tasks t
                JOIN task_assignments ta ON t.id = ta.task_id
                JOIN documents d ON t.document_id = d.id
                JOIN accreditation_processes ap ON d.accreditation_process_id = ap.id
                WHERE ta.user_id = :user_id
                ORDER BY t.due_date ASC, t.created_at DESC";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetTasksByUserId error: " . $e->getMessage());
            return [];
        }
    }

    // Get all users assigned to a specific task
    public function getAssignedUsersForTask($task_id) {
        $sql = "SELECT u.id, u.name, u.email
                FROM users u
                JOIN task_assignments ta ON u.id = ta.user_id
                WHERE ta.task_id = :task_id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetAssignedUsersForTask for task_id $task_id error: " . $e->getMessage());
            return [];
        }
    }
}
?>
