<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/AccreditationProcess.php';
require_once __DIR__ . '/../models/Document.php'; // For listing documents on the show page

class AccreditationController extends BaseController {
    private $accreditationProcessModel;
    private $documentModel;

    public function __construct() {
        // parent::__construct();
        $this->accreditationProcessModel = new AccreditationProcess();
        $this->documentModel = new Document(); // Initialize DocumentModel
    }

    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }

        $processes = $this->accreditationProcessModel->getAllProcesses();
        $this->renderView('accreditations/index', ['processes' => $processes, 'pageTitle' => 'Accreditation Processes']);
    }

    public function show($id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required');
            return;
        }

        $process = $this->accreditationProcessModel->getProcessById($id);
        if (!$process) {
            $this->redirect('accreditation/index&error=Process_not_found_ID_' . $id);
            return;
        }

        // Fetch documents related to this process
        $documents = $this->documentModel->getFilledDocumentsByProcessId($id);

        $this->renderView('accreditations/show', [
            'process' => $process,
            'documents' => $documents, // Pass documents to the view
            'pageTitle' => 'Process: ' . htmlspecialchars($process['title'])
        ]);
    }

    public function showCreateForm() {
        if (!$this->isSuperUser()) {
            $this->redirect('accreditation/index&error=Access_denied_Superuser_only');
            return;
        }
        $data = ['pageTitle' => 'Create Process'];
        if(isset($_GET['title'])) $data['title'] = $_GET['title'];
        if(isset($_GET['description'])) $data['description'] = $_GET['description'];
        if(isset($_GET['start_date'])) $data['start_date'] = $_GET['start_date'];
        if(isset($_GET['end_date'])) $data['end_date'] = $_GET['end_date'];
        if(isset($_GET['status'])) $data['status'] = $_GET['status'];
        $this->renderView('accreditations/create', $data);
    }

    public function create() {
        if (!$this->isSuperUser()) {
            $this->redirect('accreditation/index&error=Access_denied_Superuser_only');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
            $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
            $status = trim($_POST['status'] ?? 'pending');
            $created_by_user_id = $_SESSION['user_id'];

            if (empty($title)) {
                $query_params = http_build_query([
                    'error' => 'Title_is_required',
                    'description' => $description,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'status' => $status
                ]);
                $this->redirect('accreditation/showCreateForm&' . $query_params);
                return;
            }

            $processId = $this->accreditationProcessModel->createProcess($title, $description, $start_date, $end_date, $created_by_user_id, $status);

            if ($processId) {
                $this->redirect('accreditation/show/' . $processId . '&success=Process_created_successfully');
            } else {
                 $query_params = http_build_query([
                    'error' => 'Could_not_create_process_DB_error',
                    'title' => $title,
                    'description' => $description,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'status' => $status
                ]);
                $this->redirect('accreditation/showCreateForm&' . $query_params);
            }
        } else {
             $this->redirect('accreditation/showCreateForm');
        }
    }

    public function showEditForm($id) {
        if (!$this->isSuperUser()) {
            $this->redirect('accreditation/index&error=Access_denied_Superuser_only');
            return;
        }
        $process = $this->accreditationProcessModel->getProcessById($id);
        if (!$process) {
            $this->redirect('accreditation/index&error=Process_not_found_ID_' . $id);
            return;
        }
        $this->renderView('accreditations/edit', ['process' => $process, 'pageTitle' => 'Edit Process: ' . htmlspecialchars($process['title'])]);
    }

    public function update($id) {
        if (!$this->isSuperUser()) {
            $this->redirect('accreditation/index&error=Access_denied_Superuser_only');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
            $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
            $status = trim($_POST['status'] ?? 'pending');

            if (empty($title)) {
                $this->redirect('accreditation/showEditForm/' . $id . '&error=Title_is_required');
                return;
            }

            $success = $this->accreditationProcessModel->updateProcess($id, $title, $description, $start_date, $end_date, $status);

            if ($success) {
                $this->redirect('accreditation/show/' . $id . '&success=Process_updated_successfully');
            } else {
                $this->redirect('accreditation/showEditForm/' . $id . '&error=Could_not_update_process_DB_error');
            }
        } else {
            $this->redirect('accreditation/showEditForm/' . $id);
        }
    }

    public function delete($id) {
        if (!$this->isSuperUser()) {
            $this->redirect('accreditation/index&error=Access_denied_Superuser_only');
            return;
        }

        $process = $this->accreditationProcessModel->getProcessById($id);
        if (!$process) {
            $this->redirect('accreditation/index&error=Process_not_found_cannot_delete');
            return;
        }

        $success = $this->accreditationProcessModel->deleteProcess($id);
        if ($success) {
            $this->redirect('accreditation/index&success=Process_deleted_successfully_ID_' . $id);
        } else {
            $this->redirect('accreditation/index&error=Could_not_delete_process_ID_' . $id . '_It_may_have_related_records_or_DB_error');
        }
    }
}
?>
