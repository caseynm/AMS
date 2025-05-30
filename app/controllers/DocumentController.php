<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../models/AccreditationProcess.php'; // To verify process exists and get its title

class DocumentController extends BaseController {
    private $documentModel;
    private $accreditationProcessModel;

    public function __construct() {
        parent::__construct();
        $this->documentModel = new Document();
        $this->accreditationProcessModel = new AccreditationProcess();
    }

    // Route: document/listByProcess/{process_id} - THIS IS NOT USED.
    // The document list is shown as part of AccreditationController::show()
    // However, if a direct link to a document list page is ever needed, this could be revived.
    // For now, it's better to keep document listing integrated in the process show page.
    // public function listByProcess($process_id) { ... }


    // Show form to add a new document to a process (Superuser only)
    // Route: document/showCreateForm/{process_id}
    public function showCreateForm($process_id) {
        if (!$this->isSuperUser()) {
            $this->redirect('user/showLoginForm&error=auth_required_superuser');
            return;
        }
        $process = $this->accreditationProcessModel->getProcessById($process_id);
        if (!$process) {
            $this->redirect('accreditation/index&error=Process_not_found_ID_' . $process_id);
            return;
        }

        $data = [
            'process_id' => $process_id,
            'process_title' => $process['title'],
            'pageTitle' => 'Add Document to ' . htmlspecialchars($process['title'])
        ];
        if(isset($_GET['name'])) $data['name'] = $_GET['name'];
        if(isset($_GET['onedrive_url'])) $data['onedrive_url'] = $_GET['onedrive_url'];
        if(isset($_GET['status'])) $data['status'] = $_GET['status'];

        $this->renderView('documents/create', $data);
    }

    // Handle creation of a new document (Superuser only)
    // Route: document/create/{process_id}
    public function create($process_id) {
        if (!$this->isSuperUser()) {
            $this->redirect('user/showLoginForm&error=auth_required_superuser');
            return;
        }
        $process = $this->accreditationProcessModel->getProcessById($process_id);
        if (!$process) {
            $this->redirect('accreditation/index&error=Process_not_found_ID_' . $process_id . '_cannot_create_doc');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $onedrive_url = trim($_POST['onedrive_url'] ?? '');
            $status = trim($_POST['status'] ?? 'pending');
            $uploaded_by_user_id = $_SESSION['user_id'];

            $errors = [];
            if (empty($name)) $errors['name'] = "Document_name_is_required";
            if (!empty($onedrive_url) && !filter_var($onedrive_url, FILTER_VALIDATE_URL)) {
                 $errors['url'] = "Invalid_OneDrive_URL_format";
            }

            if (!empty($errors)) {
                $error_message = implode(',_', array_values($errors));
                 $query_params = http_build_query([
                    'error' => $error_message,
                    'name' => $name,
                    'onedrive_url' => $onedrive_url,
                    'status' => $status
                ]);
                $this->redirect('document/showCreateForm/' . $process_id . '&' . $query_params);
                return;
            }

            $docId = $this->documentModel->addDocument($process_id, $name, $onedrive_url, $uploaded_by_user_id, $status);

            if ($docId) {
                $this->redirect("accreditation/show/" . $process_id . "&success=Document_added_successfully_ID_" . $docId . "#doc".$docId);
            } else {
                 $query_params = http_build_query([
                    'error' => 'Could_not_add_document_DB_error',
                    'name' => $name,
                    'onedrive_url' => $onedrive_url,
                    'status' => $status
                ]);
                $this->redirect('document/showCreateForm/' . $process_id . '&' . $query_params);
            }
        } else {
             $this->redirect("document/showCreateForm/" . $process_id);
        }
    }

    // Show form to edit an existing document (Superuser only)
    // Route: document/showEditForm/{document_id}
    public function showEditForm($document_id) {
        if (!$this->isSuperUser()) {
            $this->redirect('user/showLoginForm&error=auth_required_superuser');
            return;
        }
        $document = $this->documentModel->getDocumentById($document_id);
        if (!$document) {
            // Try to find which process page to redirect to, if possible.
            // This might require a more complex lookup or just a generic redirect.
            $this->redirect('accreditation/index&error=Document_not_found_ID_' . $document_id);
            return;
        }

        $this->renderView('documents/edit', ['document' => $document, 'pageTitle' => 'Edit Document: ' . htmlspecialchars($document['name'])]);
    }

    // Handle update of an existing document (Superuser only)
    // Route: document/update/{document_id}
    public function update($document_id) {
        if (!$this->isSuperUser()) {
            $this->redirect('user/showLoginForm&error=auth_required_superuser');
            return;
        }

        $document = $this->documentModel->getDocumentById($document_id);
        if (!$document) {
            $this->redirect('accreditation/index&error=Document_not_found_cannot_update_ID_' . $document_id);
            return;
        }
        $process_id = $document['accreditation_process_id'];


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $onedrive_url = trim($_POST['onedrive_url'] ?? '');
            $status = trim($_POST['status'] ?? 'pending');

            $errors = [];
            if (empty($name)) $errors['name'] = "Document_name_is_required";
             if (!empty($onedrive_url) && !filter_var($onedrive_url, FILTER_VALIDATE_URL)) {
                 $errors['url'] = "Invalid_OneDrive_URL_format";
            }

            if (!empty($errors)) {
                $error_message = implode(',_', array_values($errors));
                $this->redirect('document/showEditForm/' . $document_id . '&error=' . $error_message);
                return;
            }

            $success = $this->documentModel->updateDocument($document_id, $name, $onedrive_url, $status);

            if ($success) {
                $this->redirect("accreditation/show/" . $process_id . "&success=Document_updated_successfully" . "#doc".$document_id);
            } else {
                $this->redirect('document/showEditForm/' . $document_id . '&error=Could_not_update_document_DB_error');
            }
        } else {
            $this->redirect("document/showEditForm/" . $document_id);
        }
    }

    // Handle deletion of a document (Superuser only)
    // Route: document/delete/{document_id}/{process_id_for_redirect}
    public function delete($document_id, $process_id_for_redirect) {
        if (!$this->isSuperUser()) {
            $this->redirect('user/showLoginForm&error=auth_required_superuser');
            return;
        }

        $document = $this->documentModel->getDocumentById($document_id);
        if (!$document) {
            $this->redirect('accreditation/show/' . $process_id_for_redirect . '&error=Document_not_found_cannot_delete_ID_' . $document_id);
            return;
        }
        // Ensure the redirect ID is valid, fallback to what was passed if doc lookup somehow fails.
        $actual_process_id = $document['accreditation_process_id'] ?? $process_id_for_redirect;

        $success = $this->documentModel->deleteDocument($document_id);

        if ($success) {
            $this->redirect("accreditation/show/" . $actual_process_id . "&success=Document_deleted_successfully_ID_" . $document_id);
        } else {
            $this->redirect("accreditation/show/" . $actual_process_id . "&error=Could_not_delete_document_ID_" . $document_id . "_Check_dependencies_or_DB_error" . "#doc".$document_id);
        }
    }
}
?>
