<?php
/**
 * Registration Model
 * Manages event registrations from clubs/individuals
 */

class Registration {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Create registration
     */
    public function create($data) {
        return $this->db->insert('registrations', [
            'event_id' => $data['event_id'],
            'event_category_id' => $data['event_category_id'] ?? null,
            'athlete_name' => $data['athlete_name'],
            'athlete_email' => $data['athlete_email'],
            'athlete_phone' => $data['athlete_phone'] ?? '',
            'athlete_dob' => $data['athlete_dob'] ?? null,
            'club_id' => $data['club_id'] ?? null,
            'region_id' => $data['region_id'] ?? null,
            'bib_number' => $data['bib_number'] ?? '',
            'status' => 'pending',
            'notes' => $data['notes'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get registrations for event
     */
    public function getByEvent($eventId, $status = null) {
        $sql = "SELECT * FROM registrations WHERE event_id = ?";
        $params = [$eventId];
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC";
        return $this->db->getResults($sql, $params);
    }
    
    /**
     * Get registration by ID
     */
    public function getById($id) {
        $sql = "SELECT r.*, e.name as event_name, ec.name as category_name 
                FROM registrations r
                JOIN events e ON r.event_id = e.id
                LEFT JOIN event_categories ec ON r.event_category_id = ec.id
                WHERE r.id = ?";
        return $this->db->getRow($sql, [$id]);
    }
    
    /**
     * Get registrations by club
     */
    public function getByClub($clubId) {
        $sql = "SELECT * FROM registrations WHERE club_id = ? AND status = 'approved' ORDER BY created_at DESC";
        return $this->db->getResults($sql, [$clubId]);
    }
    
    /**
     * Update registration status
     */
    public function updateStatus($id, $status) {
        return $this->db->update('registrations', [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
    }
    
    /**
     * Approve registration (convert to athlete)
     */
    public function approve($id) {
        $reg = $this->getById($id);
        if (!$reg) return false;
        
        // Create athlete
        $athlete = new Athlete($this->db);
        $athleteData = [
            'name' => $reg['athlete_name'],
            'bib_number' => $reg['bib_number'],
            'gender' => 'M',  // Default, can be updated
            'date_of_birth' => $reg['athlete_dob'],
            'team_id' => $reg['club_id'],
            'region_id' => $reg['region_id']
        ];
        
        if ($athlete->create($athleteData)) {
            return $this->updateStatus($id, 'approved');
        }
        
        return false;
    }
    
    /**
     * Reject registration
     */
    public function reject($id, $reason = '') {
        return $this->db->update('registrations', [
            'status' => 'rejected',
            'notes' => $reason,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
    }
    
    /**
     * Count registrations by status
     */
    public function countByStatus($eventId) {
        $sql = "SELECT status, COUNT(*) as count FROM registrations WHERE event_id = ? GROUP BY status";
        $results = $this->db->getResults($sql, [$eventId]);
        
        $counts = ['pending' => 0, 'approved' => 0, 'rejected' => 0];
        foreach ($results as $row) {
            $counts[$row['status']] = $row['count'];
        }
        
        return $counts;
    }
}
