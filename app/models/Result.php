<?php
/**
 * Result Model
 * Manages race results and points calculation
 */

class Result {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Record result
     */
    public function create($data) {
        $points = $this->calculatePoints($data['position'] ?? 0);
        
        return $this->db->insert('results', [
            'event_category_id' => $data['event_category_id'],
            'athlete_id' => $data['athlete_id'],
            'position' => $data['position'] ?? null,
            'chest_number' => $data['chest_number'] ?? '',
            'performance_time' => $data['performance_time'] ?? '',
            'performance_distance' => $data['performance_distance'] ?? null,
            'points' => $points,
            'status' => $data['status'] ?? 'completed',
            'remarks' => $data['remarks'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Calculate points from position
     */
    private function calculatePoints($position) {
        $pointsTable = [
            1 => 10, 2 => 8, 3 => 6, 4 => 5, 5 => 4,
            6 => 3, 7 => 2, 8 => 1
        ];
        return $pointsTable[$position] ?? 0;
    }
    
    /**
     * Get results for category
     */
    public function getByCategory($categoryId, $limit = 100) {
        $sql = "SELECT r.*, a.name as athlete_name, a.bib_number, t.name as team_name
                FROM results r
                JOIN athletes a ON r.athlete_id = a.id
                LEFT JOIN teams t ON a.team_id = t.id
                WHERE r.event_category_id = ?
                ORDER BY r.position ASC
                LIMIT ?";
        return $this->db->getResults($sql, [$categoryId, $limit]);
    }
    
    /**
     * Get athlete results
     */
    public function getByAthlete($athleteId) {
        $sql = "SELECT r.*, ec.name as category_name, e.name as event_name
                FROM results r
                JOIN event_categories ec ON r.event_category_id = ec.id
                JOIN events e ON ec.event_id = e.id
                WHERE r.athlete_id = ?
                ORDER BY e.event_date DESC";
        return $this->db->getResults($sql, [$athleteId]);
    }
    
    /**
     * Update result
     */
    public function update($id, $data) {
        if (isset($data['position'])) {
            $data['points'] = $this->calculatePoints($data['position']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('results', $data, ['id' => $id]);
    }
    
    /**
     * Get result by ID
     */
    public function getById($id) {
        $sql = "SELECT r.*, a.name as athlete_name, ec.name as category_name
                FROM results r
                JOIN athletes a ON r.athlete_id = a.id
                JOIN event_categories ec ON r.event_category_id = ec.id
                WHERE r.id = ?";
        return $this->db->getRow($sql, [$id]);
    }
    
    /**
     * Delete result
     */
    public function delete($id) {
        return $this->db->delete('results', ['id' => $id]);
    }
    
    /**
     * Get team total points for event
     */
    public function getTeamPoints($eventId, $teamId) {
        $sql = "SELECT COALESCE(SUM(r.points), 0) as total_points
                FROM results r
                JOIN event_categories ec ON r.event_category_id = ec.id
                JOIN athletes a ON r.athlete_id = a.id
                WHERE ec.event_id = ? AND a.team_id = ? AND r.status = 'completed'";
        $result = $this->db->getRow($sql, [$eventId, $teamId]);
        return $result['total_points'] ?? 0;
    }
    
    /**
     * Get DNF (Did Not Finish) results
     */
    public function getDNF($categoryId) {
        $sql = "SELECT r.*, a.name as athlete_name
                FROM results r
                JOIN athletes a ON r.athlete_id = a.id
                WHERE r.event_category_id = ? AND r.status IN ('dnf', 'dq', 'retired')
                ORDER BY a.name ASC";
        return $this->db->getResults($sql, [$categoryId]);
    }
}
