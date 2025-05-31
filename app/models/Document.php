<?php
// app/models/Document.php
require_once __DIR__ . '/../../config/database.php';

class Document { // Manages 'filled_documents' stored in the 'documents' table
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function createFilledDocument($accreditation_process_id, $document_template_id, $user_id, $name_title, $form_data_json, $status = 'draft') {
        // Basic validation for form_data_json as JSON
        if (json_decode($form_data_json) === null && json_last_error() !== JSON_ERROR_NONE) {
            error_log("CreateFilledDocument error: form_data_json is not valid JSON.");
            return false;
        }
        $sql = "INSERT INTO documents (accreditation_process_id, document_template_id, user_id, name, form_data, status)
                VALUES (:accreditation_process_id, :document_template_id, :user_id, :name, :form_data, :status)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':accreditation_process_id', $accreditation_process_id, PDO::PARAM_INT);
            $stmt->bindParam(':document_template_id', $document_template_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name_title); // 'name' column now used as title
            $stmt->bindParam(':form_data', $form_data_json);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("CreateFilledDocument DB error: " . $e->getMessage());
            return false;
        }
    }

    public function getFilledDocumentById($id) {
        // Fetches filled document and joins with template to get template name and fields_definition
        $sql = "SELECT d.*, u.name as created_by_username, dt.name as template_name, dt.fields_definition
                FROM documents d
                JOIN users u ON d.user_id = u.id
                JOIN document_templates dt ON d.document_template_id = dt.id
                WHERE d.id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $document = $stmt->fetch(PDO::FETCH_ASSOC);
            // Optionally decode form_data and fields_definition here if always needed as array/object
            return $document;
        } catch (PDOException $e) {
            error_log("GetFilledDocumentById error: " . $e->getMessage());
            return false;
        }
    }

    public function getFilledDocumentsByProcessId($process_id) {
        $sql = "SELECT d.id, d.name, d.status, d.updated_at, u.name as created_by_username, dt.name as template_name
                FROM documents d
                JOIN users u ON d.user_id = u.id
                JOIN document_templates dt ON d.document_template_id = dt.id
                WHERE d.accreditation_process_id = :process_id
                ORDER BY d.updated_at DESC";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':process_id', $process_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetFilledDocumentsByProcessId error: " . $e->getMessage());
            return [];
        }
    }

    public function updateFilledDocument($id, $name_title, $form_data_json, $status) {
        if (json_decode($form_data_json) === null && json_last_error() !== JSON_ERROR_NONE) {
            error_log("UpdateFilledDocument error: form_data_json is not valid JSON.");
            return false;
        }
        $sql = "UPDATE documents
                SET name = :name, form_data = :form_data, status = :status, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name_title);
            $stmt->bindParam(':form_data', $form_data_json);
            $stmt->bindParam(':status', $status);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("UpdateFilledDocument DB error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteFilledDocument($id) {
        // Note: Associated tasks will be deleted by ON DELETE CASCADE from the tasks table schema.
        // Comments related to this document (if any) are not automatically deleted by DB FK,
        // this would need application logic or triggers.
        $sql = "DELETE FROM documents WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("DeleteFilledDocument DB error: " . $e->getMessage());
            return false;
        }
    }

    public function updateFilledDocumentStatus($id, $status) {
        $sql = "UPDATE documents SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("UpdateFilledDocumentStatus error: " . $e->getMessage());
            return false;
        }
    }
}
?>
