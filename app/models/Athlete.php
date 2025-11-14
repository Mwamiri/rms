<?php
/**
 * Athlete Model
 * Manages athlete data and operations
 */

class Athlete {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Create athlete
     */
    public function create($data) {
        return $this->db->insert('athletes', [
            'bib_number' => $data['bib_number'] ?? '',
            'name' => $data['name'],
            'gender' => $data['gender'] ?? 'M',
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'region_id' => $data['region_id'] ?? null,
            'team_id' => $data['team_id'] ?? null,
            'school_id' => $data['school_id'] ?? null,
            'district' => $data['district'] ?? '',
            'class' => $data['class'] ?? '',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get all athletes
     */
    public function getAll($limit = 1000, $offset = 0) {
        $sql = "SELECT * FROM athletes WHERE is_active = 1 ORDER BY name ASC LIMIT ? OFFSET ?";
        return $this->db->getResults($sql, [$limit, $offset]);
    }
    
    /**
     * Get athlete by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM athletes WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }
    
    /**
     * Get athletes by team
     */
    public function getByTeam($teamId) {
        $sql = "SELECT * FROM athletes WHERE team_id = ? AND is_active = 1 ORDER BY name ASC";
        return $this->db->getResults($sql, [$teamId]);
    }
    
    /**
     * Get athletes by gender
     */
    public function getByGender($gender) {
        $sql = "SELECT * FROM athletes WHERE gender = ? AND is_active = 1 ORDER BY name ASC";
        return $this->db->getResults($sql, [$gender]);
    }
    
    /**
     * Search athletes
     */
    public function search($query) {
        $query = '%' . $query . '%';
        $sql = "SELECT * FROM athletes WHERE (name LIKE ? OR bib_number LIKE ?) AND is_active = 1 ORDER BY name ASC LIMIT 50";
        return $this->db->getResults($sql, [$query, $query]);
    }
    
    /**
     * Update athlete
     */
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('athletes', $data, ['id' => $id]);
    }
    
    /**
     * Deactivate athlete
     */
    public function deactivate($id) {
        return $this->update($id, ['is_active' => 0]);
    }
    
    /**
     * Import athletes from array
     */
    public function importBatch($athletes) {
        $count = 0;
        foreach ($athletes as $athlete) {
            if ($this->create($athlete)) {
                $count++;
            }
        }
        return $count;
    }
    
    /**
     * Count total athletes
     */
    public function count() {
        $result = $this->db->getRow("SELECT COUNT(*) as total FROM athletes WHERE is_active = 1");
        return $result['total'] ?? 0;
    }
}
