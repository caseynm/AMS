<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/AccreditationProcess.php'; // For fetching processes for the dashboard

class HomeController extends BaseController {

    private $accreditationProcessModel;

    public function __construct() {
        parent::__construct(); // Call BaseController constructor if any setup is needed there
        $this->accreditationProcessModel = new AccreditationProcess();
    }

    public function index() {
        if (!$this->isLoggedIn()) {
            // Redirect to login page if not logged in
            // Pass a 'return_to' parameter if you want to redirect back after login
            $this->redirect('user/showLoginForm&message=login_required');
            return;
        }

        // Fetch all accreditation processes to display on the dashboard
        // This might be refined later to show processes relevant to the user, etc.
        $processes = $this->accreditationProcessModel->getAllProcesses();

        $data = [
            'pageTitle' => 'Dashboard',
            'processes' => $processes
            // 'user_name' is already in $_SESSION, accessible in header.php
        ];

        $this->renderView('home', $data);
    }
}
?>
