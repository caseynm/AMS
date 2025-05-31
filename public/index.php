<?php
require_once __DIR__ . '/../app/config/config.php';
session_start();

// Basic routing logic (will be expanded)
// Example: http://localhost/index.php?url=controller/action/param
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home/index';
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

// Define controller, method, and params
$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'HomeController';
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';
$params = array_slice($urlParts, 2);

$controllerFile = '../app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controllerInstance = new $controllerName();
        if (method_exists($controllerInstance, $methodName)) {
            // Call the method, passing params if any
            call_user_func_array([$controllerInstance, $methodName], $params);
        } else {
            echo "Method {$methodName} not found in controller {$controllerName}.";
            // Later, redirect to a 404 page
        }
    } else {
        echo "Controller class {$controllerName} not found.";
        // Later, redirect to a 404 page
    }
} else {
    echo "Controller file {$controllerFile} not found. Defaulting to HomeController.";
    // Fallback to a default controller/action if the requested one doesn't exist
    // For now, let's try to load a HomeController as a default example
    $defaultControllerFile = '../app/controllers/HomeController.php';
    if (file_exists($defaultControllerFile)) {
        require_once $defaultControllerFile;
        if(class_exists('HomeController')) {
            $homeController = new HomeController();
            if(method_exists($homeController, 'index')) {
                $homeController->index();
            } else {
                 echo "Default method 'index' not found in HomeController.";
            }
        } else {
            echo "Default HomeController class not found.";
        }
    } else {
        echo "Default HomeController file not found. Cannot proceed.";
        // Later, redirect to a proper 404 page or site error page
    }
}
?>
