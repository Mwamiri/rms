<?php
/**
 * Backup Service
 * Manages database backups and restoration
 */

class BackupService {
    private $db;
    private $storagePath;
    
    public function __construct($database) {
        $this->db = $database;
        $this->storagePath = ROOT_PATH . '/storage/backups';
    }
    
    /**
     * Create database backup
     */
    public function backup() {
        $timestamp = date('Y-m-d_H-i-s');
        $filename = 'rms_backup_' . $timestamp . '.sql';
        $filepath = $this->storagePath . '/' . $filename;
        
        // Get database name
        $config = require(ROOT_PATH . '/config/database.php');
        $dbName = $config['DB_NAME'];
        
        // Create backup tables list
        $tables = $this->getTables();
        $backup = '';
        
        // Add header
        $backup .= "-- RMS Database Backup\n";
        $backup .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $backup .= "-- Database: " . $dbName . "\n\n";
        
        foreach ($tables as $table) {
            $backup .= $this->getTableDump($table['name']);
        }
        
        // Save backup file
        if (file_put_contents($filepath, $backup)) {
            // Update backup log
            $this->db->insert('backup_logs', [
                'filename' => $filename,
                'filepath' => $filepath,
                'size' => filesize($filepath),
                'status' => 'completed',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            return $filename;
        }
        
        return false;
    }
    
    /**
     * Get database tables
     */
    private function getTables() {
        $result = $this->db->getResults("SHOW TABLES");
        return $result;
    }
    
    /**
     * Get table dump SQL
     */
    private function getTableDump($table) {
        $sql = '';
        
        // Get create table statement
        $createResult = $this->db->getRow("SHOW CREATE TABLE " . $table);
        $createStatement = $createResult['Create Table'] ?? '';
        
        $sql .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
        $sql .= $createStatement . ";\n\n";
        
        // Get data
        $rows = $this->db->getResults("SELECT * FROM " . $table);
        
        foreach ($rows as $row) {
            $values = array_map(function($v) {
                if ($v === null) {
                    return 'NULL';
                }
                return "'" . addslashes($v) . "'";
            }, $row);
            
            $sql .= "INSERT INTO `" . $table . "` VALUES (" . implode(',', $values) . ");\n";
        }
        
        $sql .= "\n";
        
        return $sql;
    }
    
    /**
     * List backups
     */
    public function listBackups() {
        return $this->db->getResults(
            "SELECT * FROM backup_logs ORDER BY created_at DESC LIMIT 50"
        );
    }
    
    /**
     * Delete backup
     */
    public function deleteBackup($filename) {
        $backup = $this->db->getRow(
            "SELECT * FROM backup_logs WHERE filename = ?",
            [$filename]
        );
        
        if (!$backup) {
            return false;
        }
        
        if (file_exists($backup['filepath'])) {
            unlink($backup['filepath']);
        }
        
        $this->db->delete('backup_logs', ['id' => $backup['id']]);
        
        return true;
    }
    
    /**
     * Get backup file
     */
    public function getBackup($filename) {
        return $this->db->getRow(
            "SELECT * FROM backup_logs WHERE filename = ?",
            [$filename]
        );
    }
    
    /**
     * Restore from backup (creates backup before restore)
     */
    public function restore($filename) {
        $backup = $this->getBackup($filename);
        if (!$backup) {
            return false;
        }
        
        // Create backup before restore
        $this->backup();
        
        // Read and execute SQL file
        $sql = file_get_contents($backup['filepath']);
        $statements = array_filter(explode(';', $sql));
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $this->db->query($statement);
            }
        }
        
        return true;
    }
    
    /**
     * Auto backup (called periodically)
     */
    public function autoBackup() {
        $lastBackup = $this->db->getRow(
            "SELECT created_at FROM backup_logs WHERE status = 'completed' ORDER BY created_at DESC LIMIT 1"
        );
        
        // Only backup if last backup was more than 24 hours ago
        if (!$lastBackup || strtotime($lastBackup['created_at']) < strtotime('-24 hours')) {
            return $this->backup();
        }
        
        return false;
    }
    
    /**
     * Clean old backups (keep last 10)
     */
    public function cleanOldBackups() {
        $backups = $this->db->getResults(
            "SELECT * FROM backup_logs ORDER BY created_at DESC OFFSET 10"
        );
        
        $deleted = 0;
        foreach ($backups as $backup) {
            if ($this->deleteBackup($backup['filename'])) {
                $deleted++;
            }
        }
        
        return $deleted;
    }
}
