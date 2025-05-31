<?php

class BaseController {

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function isSuperUser() {
        return $this->isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser';
    }

    protected function redirect($url_path_with_query = '') {
        // Ensure the URL starts with index.php?url= or is just index.php for home
        $base_url = "index.php";
        if (!empty($url_path_with_query)) {
            if (strpos($url_path_with_query, 'index.php?url=') !== 0 && strpos($url_path_with_query, '?url=') !== 0) {
                 // Check if it's just a path like 'user/login' or 'user/login&error=...'
                if (strpos($url_path_with_query, '&') !== false || strpos($url_path_with_query, '=') !== false && strpos($url_path_with_query, '?') === false) {
                    // Contains query params but no initial ?url=
                    // e.g. user/login&error=1
                    $parts = explode('&', $url_path_with_query, 2);
                    $path = $parts[0];
                    $query = $parts[1] ?? '';
                    $url_path_with_query = "?url=" . $path . ($query ? '&' . $query : '');

                } else if (strpos($url_path_with_query, '?') === false) {
                     // Just a path like 'user/login'
                    $url_path_with_query = "?url=" . $url_path_with_query;
                }
                 // If it was like ?url=user/login, it's fine
            }
        } else {
            // Default to home/index if no path is provided
            $url_path_with_query = "?url=home/index";
        }

        // Prepend / if index.php is at root, otherwise adjust if in subdirectory
        // Assuming index.php is at the web root for these links.
        // If AMS is in a subfolder like /ams/, this needs to be /ams/index.php?...
        // For now, keeping it simple as /index.php...
        // $final_url = "/" . ltrim($base_url . $url_path_with_query, '/');
        // Prepend BASE_PATH, ensuring no double slashes
        $path_part = ltrim($base_url . $url_path_with_query, '/');
        $final_url = rtrim(BASE_PATH, '/') . '/' . $path_part;


        header("Location: " . $final_url);
        exit;
    }

    protected function renderView($view, $data = []) {
        // Make sure session variables are available to the view if needed,
        // though they are global so accessible anyway.
        // For example, $_SESSION['user_name'] for header.

        // Sanitize data before extracting to prevent accidental variable overwrites or injections
        // This is a basic example; more robust sanitization might be needed depending on data sources.
        $sanitized_data = [];
        foreach ($data as $key => $value) {
            // Prevent overwriting critical variables like 'view' or 'this'
            if (in_array($key, ['view', 'this', 'GLOBALS', '_SESSION', '_POST', '_GET', '_COOKIE', '_FILES', '_ENV', '_REQUEST', 'sanitized_data'])) {
                // Potentially log this attempt or handle it more strictly
                continue;
            }
            $sanitized_data[$key] = $value; // Further sanitization (e.g. htmlspecialchars) should happen IN THE VIEW for output
        }

        // Add BASE_PATH to be available in views
        // Added after sanitization loop to avoid conflict with protected keys,
        // assuming BASE_PATH itself is a safe, defined constant.
        $sanitized_data['BASE_PATH'] = BASE_PATH;

        extract($sanitized_data);

        // Construct path to view file
        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once __DIR__ . '/../views/layouts/header.php';
            require_once $viewPath;
            require_once __DIR__ . '/../views/layouts/footer.php';
        } else {
            // Handle view not found error - display an error page or message
            error_log("Error: View file not found at path: " . $viewPath);
            // For a user-friendly error:
            $this->renderView('errors/not_found', ['error_message' => "The requested view '{$view}' could not be found."]);
            // Or simply:
            // die("Error: View '{$view}' not found. Path: {$viewPath}");
        }
    }

    // Helper to easily get current logged in user's ID
    protected function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    // Helper to get current user's role
    protected function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
}
?>
