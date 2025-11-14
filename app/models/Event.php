<?php
/**
 * Event Model
 * Manages event data and operations
 */

class Event {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Create event
     */
    public function create($data) {
        return $this->db->insert('events', [
            'organization_id' => $data['organization_id'] ?? 1,
            'name' => $data['name'],
            'type' => $data['type'] ?? 'cross_country',
            'location' => $data['location'] ?? '',
            'event_date' => $data['event_date'],
            'description' => $data['description'] ?? '',
            'status' => 'upcoming',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get all events
     */
    public function getAll($limit = 100, $offset = 0) {
        $sql = "SELECT * FROM events ORDER BY event_date DESC LIMIT ? OFFSET ?";
        return $this->db->getResults($sql, [$limit, $offset]);
    }
    
    /**
     * Get upcoming events
     */
    public function getUpcoming($limit = 5) {
        $sql = "SELECT * FROM events WHERE status = 'upcoming' AND event_date >= NOW() ORDER BY event_date ASC LIMIT ?";
        return $this->db->getResults($sql, [$limit]);
    }
    
    /**
     * Get event by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM events WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }
    
    /**
     * Update event
     */
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('events', $data, ['id' => $id]);
    }
    
    /**
     * Delete event
     */
    public function delete($id) {
        return $this->db->delete('events', ['id' => $id]);
    }
    
    /**
     * Get event categories
     */
    public function getCategories($eventId) {
        $sql = "SELECT * FROM event_categories WHERE event_id = ? ORDER BY name ASC";
        return $this->db->getResults($sql, [$eventId]);
    }
    
    /**
     * Create event category
     */
    public function createCategory($eventId, $data) {
        return $this->db->insert('event_categories', [
            'event_id' => $eventId,
            'name' => $data['name'],
            'gender' => $data['gender'] ?? 'mixed',
            'distance' => $data['distance'] ?? '',
            'description' => $data['description'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get results for event category
     */
    public function getResults($categoryId) {
        $sql = "SELECT r.*, a.name as athlete_name, a.bib_number 
                FROM results r 
                JOIN athletes a ON r.athlete_id = a.id 
                WHERE r.event_category_id = ? 
                ORDER BY r.position ASC";
        return $this->db->getResults($sql, [$categoryId]);
    }
}
