<?php

class BaseController {

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function isSuperUser() {
        return $this->isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'superuser';
    }

    protected function redirect($url_path_with_query = '') {
        $path_segment = '';
        if (empty($url_path_with_query) || $url_path_with_query === 'home/index' || $url_path_with_query === '/home/index') {
            // For home/index or empty, redirect to the base path itself.
            // If APP_BASE_URL is just '/', this results in '/'.
            // If APP_BASE_URL is '/AMS/', this results in '/AMS/'.
            $path_segment = '';
        } else {
            $path_segment = $url_path_with_query;
        }

        // Ensure query string is correctly formatted (first '&' becomes '?')
        // if $path_segment itself contains what should be a query string.
        if (strpos($path_segment, '?') === false && strpos($path_segment, '&') !== false) {
             // Split path from query string like params
            $parts = explode('&', $path_segment, 2);
            $path_only = $parts[0];
            $query_string = $parts[1] ?? '';
            if (!empty($query_string)) {
                $path_segment = $path_only . '?' . $query_string;
            } else {
                $path_segment = $path_only;
            }
        }

        $final_url = '';
        // rtrim APP_BASE_URL to prevent double slashes if it ends with one
        $trimmed_base_path = rtrim(APP_BASE_URL, '/');

        if (empty($path_segment)) {
            $final_url = $trimmed_base_path . '/'; // Ensure trailing slash for base
            if ($final_url === '//') $final_url = '/'; // Handle if APP_BASE_URL was just '/'
        } else {
            // ltrim path_segment to prevent double slashes if it starts with one
            $final_url = $trimmed_base_path . '/' . ltrim($path_segment, '/');
        }

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

        // Add APP_BASE_URL to be available in views
        // Added after sanitization loop to avoid conflict with protected keys,
        // assuming APP_BASE_URL itself is a safe, defined constant.
        $sanitized_data['APP_BASE_URL'] = APP_BASE_URL; // Use APP_BASE_URL here

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

    protected function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}
?>
