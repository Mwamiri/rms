<?php
/**
 * Application Configuration
 * WordPress-style app configuration
 */

return [
    'name' => 'Athletics Federation Management System',
    'version' => '1.0.0',
    'timezone' => 'UTC',
    'debug' => false,
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'key' => $_ENV['APP_KEY'] ?? 'base64:changeme',
    
    // Features
    'features' => [
        'events' => true,
        'athletes' => true,
        'results' => true,
        'reports' => true,
        'backups' => true,
        'rankings' => true,
    ],
    
    // Report settings
    'reports' => [
        'export_formats' => ['excel', 'pdf'],
        'storage_path' => storage_path('reports'),
    ],
    
    // Backup settings
    'backups' => [
        'enabled' => true,
        'frequency' => 'daily', // daily, weekly, monthly
        'retention' => 10, // keep last 10 backups
        'storage_path' => storage_path('backups'),
    ],
];
