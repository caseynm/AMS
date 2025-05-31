<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';

class UserController extends BaseController {
    private $userModel;

    public function __construct() {
        // parent::__construct();
        $this->userModel = new User();
    }

    public function showRegistrationForm() {
        // Pass any necessary data for the form, e.g., if superuser is registering another user
        $data = ['pageTitle' => 'Register'];
        if (isset($_GET['name'])) $data['name'] = $_GET['name'];
        if (isset($_GET['email'])) $data['email'] = $_GET['email'];
        if (isset($_GET['role'])) $data['role'] = $_GET['role'];

        $this->renderView('auth/register', $data);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Role can only be set by a logged-in superuser, otherwise defaults to 'regular'
            $role = 'regular';
            if ($this->isSuperUser() && isset($_POST['role'])) {
                $role = $_POST['role'];
            } elseif (!isset($_SESSION['user_id']) && isset($_POST['role']) && $_POST['role'] === 'superuser'){
                // Prevent non-logged-in user from self-assigning superuser, even if field is manipulated
                $role = 'regular';
            }


            $errors = [];
            if (empty($name)) $errors['name'] = "Name is required.";
            if (empty($email)) $errors['email'] = "Email is required.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email_format'] = "Invalid email format.";
            if (empty($password)) $errors['password'] = "Password is required.";
            if (strlen($password) < 6 && !empty($password)) $errors['password_length'] = "Password must be at least 6 characters.";


            if ($this->userModel->getUserByEmail($email)) {
                $errors['email_exists'] = "User with this email already exists.";
            }

            if (!empty($errors)) {
                $error_message = implode(', ', array_values($errors));
                // Preserve input values for the form by passing them back via GET parameters
                $query_params = http_build_query([
                    'error' => $error_message,
                    'name' => $name,
                    'email' => $email,
                    'role' => $role // if superuser was trying to set a role
                ]);
                $this->redirect('user/showRegistrationForm&' . $query_params);
                return;
            }

            $userId = $this->userModel->createUser($name, $email, $password, $role);

            if ($userId) {
                if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'superuser') {
                    // Superuser created a user, redirect to user list or new user's profile
                    $this->redirect('user/listUsers&success=User_created_ID_' . $userId);
                } else {
                    // Normal user registration, redirect to login
                    $this->redirect('user/showLoginForm&success=Registration_successful_Please_login');
                }
            } else {
                $this->redirect('user/showRegistrationForm&error=Registration_failed_Please_try_again&name='.$name.'&email='.$email.'&role='.$role);
            }
        } else {
            $this->showRegistrationForm(); // Show form if not POST
        }
    }

    public function showLoginForm() {
        $data = ['pageTitle' => 'Login'];
        if(isset($_GET['email'])) $data['email'] = $_GET['email']; // Pre-fill email if redirected
        $this->renderView('auth/login', $data);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $error = '';

            if (empty($email) || empty($password)) {
                $error = 'Email_and_password_are_required';
            } else {
                $user = $this->userModel->verifyPassword($email, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    $this->redirect('home/index&success=Login_successful'); // Redirect to dashboard
                    return;
                } else {
                    $error = 'Invalid_email_or_password';
                }
            }
            $this->redirect('user/showLoginForm&error=' . $error . '&email=' . urlencode($email));
        } else {
            $this->showLoginForm(); // Show form if not POST
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        // Ensure session is truly ended before redirecting
        if (session_status() == PHP_SESSION_ACTIVE) { session_destroy(); }
        $this->redirect('user/showLoginForm&success=Logged_out_successfully');
    }

    public function profile() {
        if (!$this->isLoggedIn()) {
            $this->redirect('user/showLoginForm&error=Login_required_to_view_profile');
            return;
        }
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if (!$user) {
            // This case should ideally not happen if session is valid
            // but good for robustness
            $this->redirect('user/showLoginForm&error=User_not_found_Please_login_again');
            return;
        }
        $this->renderView('users/profile', ['user' => $user, 'pageTitle' => 'My Profile']);
    }

    public function listUsers() {
        if (!$this->isSuperUser()) {
            $this->redirect('home/index&error=Access_denied_Superuser_only');
            return;
        }
        $users = $this->userModel->getAllUsers();
        $this->renderView('users/list', ['users' => $users, 'pageTitle' => 'User List']);
    }
}
?>
