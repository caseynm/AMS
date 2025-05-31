<?php
require_once __DIR__ . '/../../config/database.php';

class DocumentTemplate {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function createTemplate($name, $description, $fields_definition, $created_by_user_id) {
        // Basic validation for fields_definition as JSON
        if (json_decode($fields_definition) === null && json_last_error() !== JSON_ERROR_NONE) {
            error_log("CreateTemplate error: fields_definition is not valid JSON.");
            return false; // Or throw an exception
        }
        $sql = "INSERT INTO document_templates (name, description, fields_definition, created_by_user_id)
                VALUES (:name, :description, :fields_definition, :created_by_user_id)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':fields_definition', $fields_definition); // Stored as JSON string
            $stmt->bindParam(':created_by_user_id', $created_by_user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("CreateTemplate DB error: " . $e->getMessage());
            return false;
        }
    }

    public function getTemplateById($id) {
        $sql = "SELECT dt.*, u.name as created_by_username
                FROM document_templates dt
                LEFT JOIN users u ON dt.created_by_user_id = u.id
                WHERE dt.id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $template = $stmt->fetch(PDO::FETCH_ASSOC);
            // Optionally decode fields_definition here if always needed as array/object
            // if ($template && isset($template['fields_definition'])) {
            //     $template['fields_definition_decoded'] = json_decode($template['fields_definition'], true);
            // }
            return $template;
        } catch (PDOException $e) {
            error_log("GetTemplateById error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllTemplates() {
        $sql = "SELECT dt.id, dt.name, dt.description, u.name as created_by_username, dt.updated_at
                FROM document_templates dt
                LEFT JOIN users u ON dt.created_by_user_id = u.id
                ORDER BY dt.name ASC";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetAllTemplates error: " . $e->getMessage());
            return [];
        }
    }

    public function updateTemplate($id, $name, $description, $fields_definition) {
        if (json_decode($fields_definition) === null && json_last_error() !== JSON_ERROR_NONE) {
            error_log("UpdateTemplate error: fields_definition is not valid JSON.");
            return false;
        }
        $sql = "UPDATE document_templates
                SET name = :name, description = :description, fields_definition = :fields_definition, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':fields_definition', $fields_definition);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("UpdateTemplate DB error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteTemplate($id) {
        // Check if template is used by any filled_documents (documents table)
        // Due to ON DELETE RESTRICT, DB will prevent this if used.
        // This check is for a more graceful error message.
        $checkSql = "SELECT COUNT(*) FROM documents WHERE document_template_id = :template_id";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->bindParam(':template_id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        if ($checkStmt->fetchColumn() > 0) {
            error_log("DeleteTemplate error: Template ID {$id} is in use and cannot be deleted.");
            return ['success' => false, 'message' => 'Template is in use and cannot be deleted.'];
        }

        $sql = "DELETE FROM document_templates WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Template deleted.'];
        } catch (PDOException $e) {
            error_log("DeleteTemplate DB error: " . $e->getMessage());
             // This might catch the RESTRICT constraint error from DB if the above check somehow missed.
            return ['success' => false, 'message' => 'Could not delete template. It might be in use or a database error occurred. Error: ' . $e->getMessage()];
        }
    }
}
?>
