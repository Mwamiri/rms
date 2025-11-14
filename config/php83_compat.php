<?php
/**
 * PHP 8.3 Compatibility Layer
 * Handles deprecated functions and compatibility issues
 */

// Set error reporting for PHP 8.3
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);

// Disable deprecated warnings in production
if (!defined('DEVELOPMENT_MODE')) {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', STORAGE_PATH . '/logs/php_errors.log');
}

// Compatibility for older PHP functions
if (!function_exists('mysql_get_client_info')) {
    function mysql_get_client_info() {
        return 'MySQL Client via PDO';
    }
}

// Autoloader for missing classes
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/models/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    $file = APP_PATH . '/controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    $file = APP_PATH . '/services/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
});

// PHP 8.3 specific fixes
if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
    // Handle null coalescing for older code
    function safe_get($array, $key, $default = null) {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}