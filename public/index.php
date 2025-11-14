<?php
/**
 * RMS Application Entry Point
 * Handles installation check, initialization, and routing
 */

// Enable error display for debugging PHP 8.3 issues
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define application root
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');

// Load PHP 8.3 compatibility layer
require_once(CONFIG_PATH . '/php83_compat.php');

// Check if installed
if (!file_exists(STORAGE_PATH . '/.install.lock')) {
    header('Location: /installer/');
    exit;
}

// Load environment variables
if (file_exists(ROOT_PATH . '/.env')) {
    $env = parse_ini_file(ROOT_PATH . '/.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

// Load configuration
require_once(CONFIG_PATH . '/app.php');
require_once(CONFIG_PATH . '/database.php');
require_once(CONFIG_PATH . '/schema.php');
require_once(CONFIG_PATH . '/routes.php');

// Load core classes
require_once(APP_PATH . '/Database.php');
require_once(APP_PATH . '/Router.php');

// Load models
require_once(APP_PATH . '/models/Event.php');
require_once(APP_PATH . '/models/Athlete.php');
require_once(APP_PATH . '/models/Result.php');
require_once(APP_PATH . '/models/TeamRanking.php');

// Load controllers
require_once(APP_PATH . '/controllers/EventsController.php');
require_once(APP_PATH . '/controllers/AthletesController.php');
require_once(APP_PATH . '/controllers/ResultsController.php');
require_once(APP_PATH . '/controllers/RankingsController.php');
require_once(APP_PATH . '/controllers/DashboardController.php');

// Load services
require_once(APP_PATH . '/services/ReportService.php');
require_once(APP_PATH . '/services/BackupService.php');

// Load helpers
require_once(APP_PATH . '/helpers/ViewHelper.php');

// Initialize database
$database = new Database();

// Initialize database schema if needed
$dbVersion = $database->getRow("SELECT value FROM settings WHERE name = 'db_version'");
if (!$dbVersion) {
    initializeDatabase($database);
}

// Create router instance and register routes
$router = new Router($database);
registerRoutes($router);

// Dispatch request
try {
    $response = $router->dispatch();
    
    // Handle response
    if (isset($response['error'])) {
        http_response_code($response['status'] ?? 500);
        echo json_encode(['error' => $response['error']]);
    } elseif (isset($response['redirect'])) {
        header('Location: ' . $response['redirect']);
        if (isset($response['message'])) {
            $_SESSION['message'] = $response['message'];
        }
    } elseif (isset($response['json'])) {
        header('Content-Type: application/json');
        echo json_encode($response['data']);
    } elseif (isset($response['view'])) {
        // Render view
        include(APP_PATH . '/views/' . $response['view'] . '.php');
    } else {
        http_response_code(200);
        echo "OK";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Application Error: ' . $e->getMessage()]);
}

