<?php
/**
 * Import Controller
 * Handles file uploads and data imports
 */

class ImportController {
    private $import;
    private $athlete;
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
        $this->import = new Import($database);
        $this->athlete = new Athlete($database);
    }
    
    /**
     * Show import dashboard
     */
    public function index() {
        $imports = $this->import->getAll(50, 0);
        $total = $this->db->getRow("SELECT COUNT(*) as count FROM imports")['count'];
        
        return [
            'view' => 'imports/index',
            'data' => [
                'imports' => $imports,
                'total' => $total
            ]
        ];
    }
    
    /**
     * Show import form
     */
    public function form() {
        return [
            'view' => 'imports/form',
            'data' => []
        ];
    }
    
    /**
     * Upload and process file
     */
    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
            return ['error' => 'No file uploaded', 'status' => 400];
        }
        
        $file = $_FILES['file'];
        $type = $_POST['import_type'] ?? 'athletes';
        
        // Validate file type
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['csv', 'xlsx', 'xls'])) {
            return ['error' => 'Invalid file type. Allowed: CSV, XLSX, XLS', 'status' => 400];
        }
        
        // Move uploaded file
        $uploadDir = ROOT_PATH . '/storage/uploads/';
        $filename = 'import_' . time() . '_' . uniqid() . '.' . $ext;
        $filepath = $uploadDir . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['error' => 'Failed to upload file', 'status' => 500];
        }
        
        // Parse file
        $data = $this->parseFile($filepath, $ext);
        if (empty($data)) {
            unlink($filepath);
            return ['error' => 'File is empty or cannot be read', 'status' => 400];
        }
        
        // Create import record
        $importId = $this->import->create([
            'type' => $type,
            'filename' => $filename,
            'filepath' => $filepath,
            'original_filename' => $file['name'],
            'file_size' => $file['size'],
            'row_count' => count($data)
        ]);
        
        if (!$importId) {
            unlink($filepath);
            return ['error' => 'Failed to create import record', 'status' => 500];
        }
        
        return [
            'redirect' => '/imports/preview?id=' . $importId,
            'message' => 'File uploaded successfully. Preview data before importing.'
        ];
    }
    
    /**
     * Parse file (CSV, XLS, XLSX)
     */
    private function parseFile($filepath, $ext) {
        $data = [];
        
        if ($ext === 'csv') {
            if (($handle = fopen($filepath, 'r')) !== FALSE) {
                $headers = fgetcsv($handle, 1000, ',');
                
                while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $record = [];
                    foreach ($headers as $i => $header) {
                        $record[trim($header)] = trim($row[$i] ?? '');
                    }
                    if (array_filter($record)) {  // Skip empty rows
                        $data[] = $record;
                    }
                }
                fclose($handle);
            }
        } else {
            // For XLS/XLSX, would need PHPExcel or similar
            // For now, convert to CSV first or provide instruction
            $data = $this->parseExcelAsCSV($filepath);
        }
        
        return $data;
    }
    
    /**
     * Parse Excel as CSV fallback
     */
    private function parseExcelAsCSV($filepath) {
        // Placeholder - recommend converting Excel to CSV via Google Sheets
        return [];
    }
    
    /**
     * Preview import data
     */
    public function preview() {
        $importId = $_GET['id'] ?? null;
        if (!$importId) {
            return ['error' => 'Import not found', 'status' => 404];
        }
        
        $import = $this->import->getById($importId);
        if (!$import) {
            return ['error' => 'Import not found', 'status' => 404];
        }
        
        // Parse file for preview
        $ext = strtolower(pathinfo($import['filepath'], PATHINFO_EXTENSION));
        $data = $this->parseFile($import['filepath'], $ext);
        
        return [
            'view' => 'imports/preview',
            'data' => [
                'import' => $import,
                'rows' => array_slice($data, 0, 10),  // First 10 rows
                'totalRows' => count($data),
                'columns' => count($data[0] ?? [])
            ]
        ];
    }
    
    /**
     * Process import (actual data import)
     */
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $importId = $_POST['import_id'] ?? null;
        if (!$importId) {
            return ['error' => 'Import not found', 'status' => 404];
        }
        
        $import = $this->import->getById($importId);
        if (!$import) {
            return ['error' => 'Import not found', 'status' => 404];
        }
        
        $this->import->updateStatus($importId, 'processing', 0, 0);
        
        // Parse file
        $ext = strtolower(pathinfo($import['filepath'], PATHINFO_EXTENSION));
        $data = $this->parseFile($import['filepath'], $ext);
        
        $successCount = 0;
        $errorCount = 0;
        
        // Process based on type
        if ($import['type'] === 'athletes') {
            foreach ($data as $rowNum => $row) {
                try {
                    $athleteData = [
                        'name' => $row['name'] ?? $row['athlete_name'] ?? '',
                        'bib_number' => $row['bib_number'] ?? '',
                        'gender' => $row['gender'] ?? 'M',
                        'date_of_birth' => $row['dob'] ?? $row['date_of_birth'] ?? null,
                        'district' => $row['district'] ?? '',
                        'team_id' => $row['team_id'] ?? null
                    ];
                    
                    if (!$athleteData['name']) {
                        throw new Exception('Missing athlete name');
                    }
                    
                    if ($this->athlete->create($athleteData)) {
                        $successCount++;
                        $this->import->addLog($importId, $rowNum + 2, 'success', 'Athlete imported: ' . $athleteData['name']);
                    } else {
                        throw new Exception('Failed to create athlete');
                    }
                } catch (Exception $e) {
                    $errorCount++;
                    $this->import->addLog($importId, $rowNum + 2, 'error', $e->getMessage());
                }
            }
        }
        
        $this->import->updateStatus($importId, 'completed', $successCount, $errorCount);
        
        return [
            'redirect' => '/imports/result?id=' . $importId,
            'message' => "Import completed: $successCount successful, $errorCount errors"
        ];
    }
    
    /**
     * Show import result
     */
    public function result() {
        $importId = $_GET['id'] ?? null;
        if (!$importId) {
            return ['error' => 'Import not found', 'status' => 404];
        }
        
        $import = $this->import->getById($importId);
        if (!$import) {
            return ['error' => 'Import not found', 'status' => 404];
        }
        
        $logs = $this->import->getLogs($importId);
        
        return [
            'view' => 'imports/result',
            'data' => [
                'import' => $import,
                'logs' => $logs
            ]
        ];
    }
    
    /**
     * Download template
     */
    public function template() {
        $type = $_GET['type'] ?? 'athletes';
        
        $templates = [
            'athletes' => [
                'name', 'bib_number', 'gender', 'dob', 'district', 'team_id'
            ],
            'teams' => [
                'name', 'code', 'region_id', 'contact_person', 'contact_email'
            ],
            'results' => [
                'event_category_id', 'athlete_name', 'position', 'performance_time', 'remarks'
            ]
        ];
        
        if (!isset($templates[$type])) {
            return ['error' => 'Invalid template type', 'status' => 400];
        }
        
        $headers = $templates[$type];
        $csv = implode(',', $headers) . "\n";
        
        // Add sample row
        $sample = array_fill(0, count($headers), 'example');
        $csv .= implode(',', $sample) . "\n";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="rms_' . $type . '_template.csv"');
        
        return ['output' => $csv];
    }
}
