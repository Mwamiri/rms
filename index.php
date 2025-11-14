<?php
/**
 * RMS - Root Entry Point
 * Redirects to public/index.php
 * This allows accessing the system without /public/ in the URL
 */

// Enable error display for debugging PHP 8.3 issues
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Error reporting for debugging
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Define base path
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Route to public index.php
require PUBLIC_PATH . '/index.php';
