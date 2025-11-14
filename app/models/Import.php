<?php
/**
 * Import Model
 * Manages file imports from Excel/CSV/Google Sheets
 */

class Import {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Create import record
     */
    public function create($data) {
        return $this->db->insert('imports', [
            'type' => $data['type'],                 // 'athletes', 'teams', 'results'
            'filename' => $data['filename'],
            'filepath' => $data['filepath'],
            'original_filename' => $data['original_filename'],
            'file_size' => $data['file_size'],
            'row_count' => $data['row_count'] ?? 0,
            'success_count' => 0,
            'error_count' => 0,
            'status' => 'pending',
            'notes' => $data['notes'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get all imports
     */
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM imports ORDER BY created_at DESC LIMIT ? OFFSET ?";
        return $this->db->getResults($sql, [$limit, $offset]);
    }
    
    /**
     * Get import by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM imports WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }
    
    /**
     * Update import status
     */
    public function updateStatus($id, $status, $successCount, $errorCount) {
        return $this->db->update('imports', [
            'status' => $status,
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
    }
    
    /**
     * Get import logs
     */
    public function getLogs($importId, $limit = 100) {
        $sql = "SELECT * FROM import_logs WHERE import_id = ? ORDER BY created_at DESC LIMIT ?";
        return $this->db->getResults($sql, [$importId, $limit]);
    }
    
    /**
     * Add import log
     */
    public function addLog($importId, $row, $status, $message) {
        return $this->db->insert('import_logs', [
            'import_id' => $importId,
            'row_number' => $row,
            'status' => $status,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Delete import
     */
    public function delete($id) {
        return $this->db->delete('imports', ['id' => $id]);
    }
}
