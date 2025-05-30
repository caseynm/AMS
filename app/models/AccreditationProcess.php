<?php
require_once __DIR__ . '/../../config/database.php';

class AccreditationProcess {
    private $pdo;

    public function __construct() {
        global $pdo; // Use the global PDO connection
        $this->pdo = $pdo;
    }

    // Create a new accreditation process
    public function createProcess($title, $description, $start_date, $end_date, $created_by_user_id, $status = 'pending') {
        $sql = "INSERT INTO accreditation_processes (title, description, start_date, end_date, created_by_user_id, status)
                VALUES (:title, :description, :start_date, :end_date, :created_by_user_id, :status)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->bindParam(':created_by_user_id', $created_by_user_id, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("CreateProcess error: " . $e->getMessage());
            return false;
        }
    }

    // Get an accreditation process by its ID
    public function getProcessById($id) {
        $sql = "SELECT ap.*, u.name as created_by_name
                FROM accreditation_processes ap
                LEFT JOIN users u ON ap.created_by_user_id = u.id
                WHERE ap.id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetProcessById error: " . $e->getMessage());
            return false;
        }
    }

    // Get all accreditation processes
    public function getAllProcesses() {
        $sql = "SELECT ap.id, ap.title, ap.status, ap.start_date, ap.end_date, u.name as created_by_name
                FROM accreditation_processes ap
                LEFT JOIN users u ON ap.created_by_user_id = u.id
                ORDER BY ap.created_at DESC";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetAllProcesses error: " . $e->getMessage());
            return [];
        }
    }

    // Update the status of an accreditation process
    public function updateProcessStatus($id, $status) {
        $sql = "UPDATE accreditation_processes SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("UpdateProcessStatus error: " . $e->getMessage());
            return false;
        }
    }

    // (Optional) Update entire process details
    public function updateProcess($id, $title, $description, $start_date, $end_date, $status) {
        $sql = "UPDATE accreditation_processes
                SET title = :title, description = :description, start_date = :start_date, end_date = :end_date, status = :status, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("UpdateProcess error: " . $e->getMessage());
            return false;
        }
    }

    // (Optional) Delete a process
    public function deleteProcess($id) {
        // Consider implications: what happens to related documents, tasks etc.?
        // Foreign key constraints (ON DELETE CASCADE/SET NULL) in DB schema will handle some of this.
        $sql = "DELETE FROM accreditation_processes WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("DeleteProcess error: " . $e->getMessage());
            // Check for foreign key constraint violation if applicable
            if ($e->getCode() == '23000') { // Integrity constraint violation
                 error_log("DeleteProcess error: Cannot delete process due to related records.");
                 return false; // Or throw a specific exception
            }
            return false;
        }
    }
}
?>
