<?php
require_once __DIR__ . '/../../config/database.php';

class Document {
    private $pdo;

    public function __construct() {
        global $pdo; // Use the global PDO connection
        $this->pdo = $pdo;
    }

    // Add a new document to an accreditation process
    public function addDocument($accreditation_process_id, $name, $onedrive_url, $uploaded_by_user_id, $status = 'pending') {
        $sql = "INSERT INTO documents (accreditation_process_id, name, onedrive_url, uploaded_by_user_id, status)
                VALUES (:accreditation_process_id, :name, :onedrive_url, :uploaded_by_user_id, :status)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':accreditation_process_id', $accreditation_process_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':onedrive_url', $onedrive_url);
            $stmt->bindParam(':uploaded_by_user_id', $uploaded_by_user_id, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("AddDocument error: " . $e->getMessage());
            return false;
        }
    }

    // Get a document by its ID
    public function getDocumentById($id) {
        $sql = "SELECT d.*, u.name as uploaded_by_name
                FROM documents d
                LEFT JOIN users u ON d.uploaded_by_user_id = u.id
                WHERE d.id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetDocumentById error: " . $e->getMessage());
            return false;
        }
    }

    // Get all documents for a specific accreditation process
    public function getDocumentsByProcessId($process_id) {
        $sql = "SELECT d.id, d.name, d.onedrive_url, d.status, d.created_at, d.accreditation_process_id, u.name as uploaded_by_name
                FROM documents d
                LEFT JOIN users u ON d.uploaded_by_user_id = u.id
                WHERE d.accreditation_process_id = :process_id
                ORDER BY d.created_at DESC";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':process_id', $process_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetDocumentsByProcessId error: " . $e->getMessage());
            return [];
        }
    }

    // Update an existing document's details
    public function updateDocument($id, $name, $onedrive_url, $status) {
        $sql = "UPDATE documents
                SET name = :name, onedrive_url = :onedrive_url, status = :status, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':onedrive_url', $onedrive_url);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("UpdateDocument error: " . $e->getMessage());
            return false;
        }
    }

    // Delete a document
    public function deleteDocument($id) {
        // Related tasks might be deleted/set to null by DB constraints (ON DELETE CASCADE/SET NULL for tasks.document_id)
        $sql = "DELETE FROM documents WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("DeleteDocument error: " . $e->getMessage());
            // Check for foreign key constraint violation if applicable
            if ($e->getCode() == '23000') { // Integrity constraint violation
                 error_log("DeleteDocument error: Cannot delete document due to related records (tasks, comments).");
                 return false;
            }
            return false;
        }
    }

    // Update only the status of a document
    public function updateDocumentStatus($id, $status) {
        $sql = "UPDATE documents SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("UpdateDocumentStatus error: " . $e->getMessage());
            return false;
        }
    }
}
?>
