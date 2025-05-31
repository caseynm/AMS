<?php
// app/controllers/DocumentController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Document.php'; // Now for FilledDocuments
require_once __DIR__ . '/../models/DocumentTemplate.php';
require_once __DIR__ . '/../models/AccreditationProcess.php';

class DocumentController extends BaseController {
    private $documentModel; // Represents FilledDocument model
    private $templateModel;
    private $processModel;

    public function __construct() {
        // parent::__construct(); // Removed as BaseController has no constructor
        $this->documentModel = new Document();
        $this->templateModel = new DocumentTemplate();
        $this->processModel = new AccreditationProcess();
    }

    // Step 1: User selects a template for a given process
    // URL: document/selectTemplate/{process_id}
    public function selectTemplate($process_id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }
        $process = $this->processModel->getProcessById($process_id);
        if (!$process) {
            $this->redirect('accreditation/index&error=Process_not_found_ID_' . $process_id);
            return;
        }
        $templates = $this->templateModel->getAllTemplates();
        $this->renderView('documents/select_template', [
            'templates' => $templates,
            'process' => $process,
            'pageTitle' => 'Select Document Template for ' . htmlspecialchars($process['title'])
        ]);
    }

    // Step 2: Display the form based on selected template
    // URL: document/fill/{process_id}/{template_id}
    public function fill($process_id, $template_id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }
        $process = $this->processModel->getProcessById($process_id);
        $template = $this->templateModel->getTemplateById($template_id);

        if (!$process || !$template) {
            $this->redirect('accreditation/index&error=Process_or_Template_not_found');
            return;
        }

        $fields_definition = json_decode($template['fields_definition'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Log the error with more context if possible
            error_log("Error decoding fields_definition for template ID {$template_id}: " . json_last_error_msg());
            $this->redirect('document/selectTemplate/' . $process_id . '&error=Invalid_template_definition_cannot_render_form_TemplateID_'.$template_id);
            return;
        }
        // Ensure $fields_definition['fields'] exists and is an array before passing
        $fields = isset($fields_definition['fields']) && is_array($fields_definition['fields']) ? $fields_definition['fields'] : [];


        $this->renderView('documents/fill_form', [
            'process' => $process,
            'template' => $template,
            'fields' => $fields, // Decoded fields for easier rendering
            'pageTitle' => 'Fill: ' . htmlspecialchars($template['name'])
        ]);
    }

    // Step 3: Save the filled form data
    // URL: document/save/{process_id}/{template_id} (Changed from saveFilledForm for brevity)
    public function save($process_id, $template_id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }
        $process = $this->processModel->getProcessById($process_id);
        $template = $this->templateModel->getTemplateById($template_id);
        if (!$process || !$template) {
            $this->redirect('accreditation/index&error=Process_or_Template_not_found_cannot_save');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['filled_document_title'] ?? $template['name'] . ' Instance ' . date('Y-m-d'));
            $user_id = $this->getCurrentUserId();
            $status = 'draft'; // Default status

            $form_data_array = [];
            $fields_definition_decoded = json_decode($template['fields_definition'], true);

            if ($fields_definition_decoded && isset($fields_definition_decoded['fields']) && is_array($fields_definition_decoded['fields'])) {
                foreach ($fields_definition_decoded['fields'] as $field) {
                    if (isset($field['name'])) {
                        // Handle different field types, e.g., checkboxes array
                        if (isset($_POST[$field['name']])) {
                            $form_data_array[$field['name']] = $_POST[$field['name']];
                        } elseif ($field['type'] === 'checkbox') {
                             // If checkbox is not in POST, it means it was unchecked
                            $form_data_array[$field['name']] = null; // Or false, or specific "unchecked" value
                        } else {
                            $form_data_array[$field['name']] = null;
                        }
                    }
                }
            }
            $form_data_json = json_encode($form_data_array);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->redirect('document/fill/' . $process_id . '/' . $template_id . '&error=Error_processing_form_data_json_encode_failed');
                return;
            }

            $filledDocId = $this->documentModel->createFilledDocument(
                $process_id, $template_id, $user_id, $title, $form_data_json, $status
            );

            if ($filledDocId) {
                $this->redirect('accreditation/show/' . $process_id . '&success=Document_created_successfully_ID_' . $filledDocId . '#doc' . $filledDocId);
            } else {
                $this->redirect('document/fill/' . $process_id . '/' . $template_id . '&error=Could_not_save_filled_document_DB_error');
            }
        } else {
            $this->redirect('document/fill/' . $process_id . '/' . $template_id);
        }
    }

    // View a single filled document (read-only)
    // URL: document/view/{id}
    public function view($id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }
        $filledDocument = $this->documentModel->getFilledDocumentById($id);
        if (!$filledDocument) {
            $this->redirect('home/index&error=Filled_document_not_found_ID_' . $id);
            return;
        }
        $fields = json_decode($filledDocument['fields_definition'], true);
        $form_data = json_decode($filledDocument['form_data'], true);

        if (json_last_error() !== JSON_ERROR_NONE && (!$fields || !$form_data)) {
            error_log("Error decoding JSON for document ID {$id}. Fields error: ".json_last_error_msg()." Data error: ".json_last_error_msg());
            $this->redirect('home/index&error=Error_decoding_document_data_or_template_for_ID_' . $id);
            return;
        }
        $fields_actual = isset($fields['fields']) && is_array($fields['fields']) ? $fields['fields'] : [];


        $this->renderView('documents/view_filled_document', [
            'filledDocument' => $filledDocument,
            'fields' => $fields_actual,
            'form_data' => $form_data,
            'pageTitle' => 'View: ' . htmlspecialchars($filledDocument['name'])
        ]);
    }

    // Show form to edit an existing filled document
    // URL: document/edit/{id}
    public function edit($id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }
        $filledDocument = $this->documentModel->getFilledDocumentById($id);
        if (!$filledDocument) {
            $this->redirect('home/index&error=Filled_document_not_found_ID_' . $id);
            return;
        }

        if ($filledDocument['user_id'] != $this->getCurrentUserId() && !$this->isSuperUser()) {
            $this->redirect('accreditation/show/' . $filledDocument['accreditation_process_id'] . '&error=Not_authorized_to_edit_this_document');
            return;
        }

        $fields_definition = json_decode($filledDocument['fields_definition'], true);
        $form_data = json_decode($filledDocument['form_data'], true);
        if (json_last_error() !== JSON_ERROR_NONE && (!$fields_definition || !$form_data)) {
             error_log("Error decoding JSON for editing document ID {$id}. Fields error: ".json_last_error_msg()." Data error: ".json_last_error_msg());
            $this->redirect('home/index&error=Error_decoding_document_data_or_template_for_editing_ID_' . $id);
            return;
        }
        $fields_actual = isset($fields_definition['fields']) && is_array($fields_definition['fields']) ? $fields_definition['fields'] : [];


        $this->renderView('documents/edit_filled_form', [
            'filledDocument' => $filledDocument,
            'fields' => $fields_actual,
            'form_data' => $form_data,
            'pageTitle' => 'Edit: ' . htmlspecialchars($filledDocument['name'])
        ]);
    }

    // Handle update of an existing filled document
    // URL: document/update/{id} (Changed from updateFilledForm for consistency)
    public function update($id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }
        $existingFilledDoc = $this->documentModel->getFilledDocumentById($id);
        if (!$existingFilledDoc) {
            $this->redirect('home/index&error=Filled_document_not_found_cannot_update_ID_' . $id);
            return;
        }

        if ($existingFilledDoc['user_id'] != $this->getCurrentUserId() && !$this->isSuperUser()) {
            $this->redirect('accreditation/show/' . $existingFilledDoc['accreditation_process_id'] . '&error=Not_authorized_to_update_this_document');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['filled_document_title'] ?? $existingFilledDoc['name']);
            $status = trim($_POST['status'] ?? $existingFilledDoc['status']);

            $form_data_array = [];
            $fields_definition_decoded = json_decode($existingFilledDoc['fields_definition'], true);
            if ($fields_definition_decoded && isset($fields_definition_decoded['fields']) && is_array($fields_definition_decoded['fields'])) {
                foreach ($fields_definition_decoded['fields'] as $field) {
                    if (isset($field['name'])) {
                         if (isset($_POST[$field['name']])) {
                            $form_data_array[$field['name']] = $_POST[$field['name']];
                        } elseif ($field['type'] === 'checkbox') {
                            $form_data_array[$field['name']] = null;
                        } else {
                            // Keep existing data if not submitted and not a checkbox? Or set to null?
                            // For safety, let's assume if not in POST, it's not being updated, unless checkbox.
                            // This might need refinement based on how partial updates are handled.
                            // For now, if a field is in definition but not POST, it's set to null (except if it's a checkbox logic)
                            // A better approach might be to merge with existing $form_data
                            $current_form_data_decoded = json_decode($existingFilledDoc['form_data'], true);
                            if(isset($current_form_data_decoded[$field['name']])) {
                                $form_data_array[$field['name']] = $current_form_data_decoded[$field['name']];
                            } else {
                                $form_data_array[$field['name']] = null;
                            }
                        }
                    }
                }
            }
            $form_data_json = json_encode($form_data_array);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->redirect('document/edit/' . $id . '&error=Error_processing_form_data_for_update');
                return;
            }

            $success = $this->documentModel->updateFilledDocument($id, $title, $form_data_json, $status);
            if ($success) {
                $this->redirect('document/view/' . $id . '&success=Document_updated_successfully');
            } else {
                $this->redirect('document/edit/' . $id . '&error=Could_not_update_document_DB_error');
            }
        } else {
            $this->redirect('document/edit/' . $id);
        }
    }

    // Delete a filled document
    // URL: document/delete/{id}/{process_id_for_redirect}
    public function delete($id, $process_id_for_redirect = null) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }
        $filledDocument = $this->documentModel->getFilledDocumentById($id);
        if (!$filledDocument) {
            $redirect_path = $process_id_for_redirect ? 'accreditation/show/' . $process_id_for_redirect : 'home/index';
            $this->redirect($redirect_path . '&error=Document_not_found_cannot_delete_ID_' . $id);
            return;
        }

        if ($filledDocument['user_id'] != $this->getCurrentUserId() && !$this->isSuperUser()) {
            $this->redirect('accreditation/show/' . $filledDocument['accreditation_process_id'] . '&error=Not_authorized_to_delete_this_document');
            return;
        }

        $actual_process_id = $process_id_for_redirect ?? $filledDocument['accreditation_process_id'];
        $redirect_base = 'accreditation/show/' . $actual_process_id;


        $success = $this->documentModel->deleteFilledDocument($id);
        if ($success) {
            $this->redirect($redirect_base . '&success=Document_deleted_successfully_ID_' . $id);
        } else {
            $this->redirect($redirect_base . '&error=Could_not_delete_document_ID_' . $id . "_DB_error" . "#doc".$id);
        }
    }

    // This method is not directly used for a page, but AccreditationController::show() uses DocumentModel directly.
    // If a separate page listing documents for a process is needed, this can be used.
    // public function listByProcess($process_id) { ... }

    public function exportAsJson($id) {
        if (!$this->isLoggedIn()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Authentication required.']);
            exit;
        }

        $filledDocument = $this->documentModel->getFilledDocumentById($id);

        if (!$filledDocument) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Document not found.']);
            exit;
        }

        // Optional: More specific authorization check
        // if ($filledDocument['user_id'] != $this->getCurrentUserId() && !$this->isSuperUser()) {
        //     http_response_code(403);
        //     echo json_encode(['success' => false, 'message' => 'Not authorized to export this document.']);
        //     exit;
        // }

        $documentTitle = $filledDocument['name'] ?? 'document';
        // Sanitize filename
        $filename = preg_replace('/[^a-zA-Z0-9_.-]+/', '_', str_replace(' ', '_', $documentTitle)) . '.json';
        if (empty(trim(pathinfo($filename, PATHINFO_FILENAME), '_.'))) { // Handle cases where title is all special chars
            $filename = 'exported_document_' . $id . '.json';
        }


        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Assuming $filledDocument['form_data'] is the raw JSON string from the database
        $jsonDataToExport = $filledDocument['form_data'];

        $decoded_form_data = json_decode($jsonDataToExport);
        if (json_last_error() === JSON_ERROR_NONE) {
           echo json_encode($decoded_form_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
           // Fallback: output the raw string if it wasn't valid JSON (should ideally not happen)
           // or if it's already a minified JSON string and decoding failed for some reason (less likely)
           // To ensure a download still happens with valid JSON structure, even if content is problematic:
           error_log("Error decoding form_data for export on document ID {$id}, serving raw or error.");
           // If raw data is not guaranteed to be safe or valid JSON, provide an error JSON.
           // For now, let's assume raw data is at least a string.
           // If it's absolutely critical to always provide valid JSON, then:
           // echo json_encode(["error" => "Could not parse document data for export.", "raw_data" => $jsonDataToExport]);
           // However, for direct export, providing the raw data might be intended if it's already JSON.
           // Given it's stored as JSON, this path is unlikely unless data corruption.
           echo $jsonDataToExport;
        }
        exit;
    }
}
?>
