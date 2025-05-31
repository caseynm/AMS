<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/DocumentTemplate.php';

class DocumentTemplateController extends BaseController {
    private $templateModel;

    public function __construct() {
        // No parent::__construct() as BaseController has no constructor
        $this->templateModel = new DocumentTemplate();
    }

    // List all templates
    public function index() {
        if (!$this->isSuperUser()) {
            $this->redirect('home/index&error=Access_denied_Superuser_only');
            return;
        }
        $templates = $this->templateModel->getAllTemplates();
        // Pass data to a view (view to be created later)
        $this->renderView('document_templates/index', ['templates' => $templates, 'pageTitle' => 'Document Templates']);
    }

    // Show form to create a new template
    public function showCreateForm() {
        if (!$this->isSuperUser()) {
            $this->redirect('home/index&error=Access_denied_Superuser_only');
            return;
        }
        // Pass any necessary data for the form (e.g. for pre-filling on error)
        $data = ['pageTitle' => 'Create Document Template'];
        if(isset($_GET['name'])) $data['name'] = $_GET['name'];
        if(isset($_GET['description'])) $data['description'] = $_GET['description'];
        if(isset($_GET['fields_definition'])) $data['fields_definition'] = $_GET['fields_definition'];

        $this->renderView('document_templates/create', $data);
    }

    // Handle creation of a new template
    public function create() {
        if (!$this->isSuperUser()) {
            $this->redirect('home/index&error=Access_denied_Superuser_only_cannot_create');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $fields_definition = trim($_POST['fields_definition'] ?? ''); // Expecting JSON string
            $created_by_user_id = $this->getCurrentUserId();

            $errors = [];
            if (empty($name)) $errors['name'] = 'Template_name_is_required.';
            if (empty($fields_definition)) $errors['fields'] = 'Fields_definition_is_required.';
            if (json_decode($fields_definition) === null && json_last_error() !== JSON_ERROR_NONE) {
                $errors['json'] = 'Fields_definition_is_not_valid_JSON_format.';
            }

            if (!empty($errors)) {
                $error_message = implode('_', array_values($errors));
                $query_params = http_build_query([
                    'error' => $error_message,
                    'name' => $name,
                    'description' => $description,
                    'fields_definition' => $fields_definition // Pass back the invalid JSON for correction
                ]);
                $this->redirect('documenttemplate/showCreateForm&' . $query_params);
                return;
            }

            $templateId = $this->templateModel->createTemplate($name, $description, $fields_definition, $created_by_user_id);

            if ($templateId) {
                $this->redirect('documenttemplate/index&success=Template_created_successfully_ID_' . $templateId);
            } else {
                // Error message from model if JSON was invalid, or DB error
                $query_params = http_build_query([
                    'error' => 'Could_not_create_template_Check_JSON_or_DB_error',
                    'name' => $name,
                    'description' => $description,
                    'fields_definition' => $fields_definition
                ]);
                $this->redirect('documenttemplate/showCreateForm&' . $query_params);
            }
        } else {
            $this->redirect('documenttemplate/showCreateForm');
        }
    }

    // Show form to edit an existing template
    public function showEditForm($id) {
        if (!$this->isSuperUser()) {
            $this->redirect('home/index&error=Access_denied_Superuser_only');
            return;
        }
        $template = $this->templateModel->getTemplateById($id);
        if (!$template) {
            $this->redirect('documenttemplate/index&error=Template_not_found_ID_' . $id);
            return;
        }
        $this->renderView('document_templates/edit', ['template' => $template, 'pageTitle' => 'Edit Template: ' . htmlspecialchars($template['name'])]);
    }

    // Handle update of an existing template
    public function update($id) {
        if (!$this->isSuperUser()) {
            $this->redirect('home/index&error=Access_denied_Superuser_only_cannot_update');
            return;
        }
        $existingTemplate = $this->templateModel->getTemplateById($id);
        if (!$existingTemplate) {
                $this->redirect('documenttemplate/index&error=Template_not_found_cannot_update_ID_' . $id);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $fields_definition = trim($_POST['fields_definition'] ?? '');

            $errors = [];
            if (empty($name)) $errors['name'] = 'Template_name_is_required.';
            if (empty($fields_definition)) $errors['fields'] = 'Fields_definition_is_required.';
            if (json_decode($fields_definition) === null && json_last_error() !== JSON_ERROR_NONE) {
                $errors['json'] = 'Fields_definition_is_not_valid_JSON_format.';
            }

            if (!empty($errors)) {
                $error_message = implode('_', array_values($errors));
                // Pass back current values for editing
                // $_POST['id'] = $id; // ensure id is there for view if it needs it. Not needed for redirect.
                $this->redirect('documenttemplate/showEditForm/' . $id . '&error=' . $error_message . '&name=' . urlencode($name) . '&description=' . urlencode($description) . '&fields_definition=' . urlencode($fields_definition));
                return;
            }

            $success = $this->templateModel->updateTemplate($id, $name, $description, $fields_definition);

            if ($success) {
                $this->redirect('documenttemplate/index&success=Template_updated_successfully_ID_' . $id);
            } else {
                $this->redirect('documenttemplate/showEditForm/' . $id . '&error=Could_not_update_template_Check_JSON_or_DB_error' . '&name=' . urlencode($name) . '&description=' . urlencode($description) . '&fields_definition=' . urlencode($fields_definition));
            }
        } else {
            $this->redirect('documenttemplate/showEditForm/' . $id);
        }
    }

    // Handle deletion of a template
    public function delete($id) {
        if (!$this->isSuperUser()) {
            $this->redirect('home/index&error=Access_denied_Superuser_only_cannot_delete');
            return;
        }
        $template = $this->templateModel->getTemplateById($id);
        if (!$template) {
            $this->redirect('documenttemplate/index&error=Template_not_found_cannot_delete_ID_' . $id);
            return;
        }

        // Model's deleteTemplate method returns an array ['success' => bool, 'message' => string]
        $result = $this->templateModel->deleteTemplate($id);

        if ($result['success']) {
            $this->redirect('documenttemplate/index&success=' . urlencode($result['message']));
        } else {
            $this->redirect('documenttemplate/index&error=' . urlencode($result['message']));
        }
    }
}
?>
