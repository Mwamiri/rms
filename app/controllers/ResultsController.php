<?php
/**
 * Results Controller
 * Handles race results recording and management
 */

class ResultsController {
    private $result;
    private $event;
    private $athlete;
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
        $this->result = new Result($database);
        $this->event = new Event($database);
        $this->athlete = new Athlete($database);
    }
    
    /**
     * Record results for a category
     */
    public function record() {
        $categoryId = $_GET['category_id'] ?? null;
        if (!$categoryId) {
            return ['error' => 'Category not found', 'status' => 404];
        }
        
        // Get category and event details
        $category = $this->db->getRow(
            "SELECT ec.*, e.name as event_name FROM event_categories ec 
             JOIN events e ON ec.event_id = e.id WHERE ec.id = ?",
            [$categoryId]
        );
        
        if (!$category) {
            return ['error' => 'Category not found', 'status' => 404];
        }
        
        $athletes = $this->athlete->getAll(1000);
        
        return [
            'view' => 'results/record',
            'data' => [
                'category' => $category,
                'athletes' => $athletes
            ]
        ];
    }
    
    /**
     * Store result
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $data = [
            'event_category_id' => $_POST['event_category_id'] ?? null,
            'athlete_id' => $_POST['athlete_id'] ?? null,
            'position' => $_POST['position'] ?? null,
            'chest_number' => $_POST['chest_number'] ?? '',
            'performance_time' => $_POST['performance_time'] ?? '',
            'performance_distance' => $_POST['performance_distance'] ?? null,
            'status' => $_POST['status'] ?? 'completed',
            'remarks' => $_POST['remarks'] ?? ''
        ];
        
        if (!$data['event_category_id'] || !$data['athlete_id']) {
            return ['error' => 'Missing required fields', 'status' => 400];
        }
        
        if ($this->result->create($data)) {
            return ['redirect' => '/results/record?category_id=' . $data['event_category_id'], 
                   'message' => 'Result recorded successfully'];
        }
        
        return ['error' => 'Failed to record result', 'status' => 500];
    }
    
    /**
     * List results for category
     */
    public function list() {
        $categoryId = $_GET['category_id'] ?? null;
        if (!$categoryId) {
            return ['error' => 'Category not found', 'status' => 404];
        }
        
        $category = $this->db->getRow(
            "SELECT ec.*, e.name as event_name FROM event_categories ec 
             JOIN events e ON ec.event_id = e.id WHERE ec.id = ?",
            [$categoryId]
        );
        
        if (!$category) {
            return ['error' => 'Category not found', 'status' => 404];
        }
        
        $results = $this->result->getByCategory($categoryId);
        $dnf = $this->result->getDNF($categoryId);
        
        return [
            'view' => 'results/list',
            'data' => [
                'category' => $category,
                'results' => $results,
                'dnf' => $dnf
            ]
        ];
    }
    
    /**
     * Show result details
     */
    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return ['error' => 'Result not found', 'status' => 404];
        }
        
        $result = $this->result->getById($id);
        if (!$result) {
            return ['error' => 'Result not found', 'status' => 404];
        }
        
        return [
            'view' => 'results/show',
            'data' => ['result' => $result]
        ];
    }
    
    /**
     * Edit result
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return ['error' => 'Result not found', 'status' => 404];
        }
        
        $result = $this->result->getById($id);
        if (!$result) {
            return ['error' => 'Result not found', 'status' => 404];
        }
        
        return [
            'view' => 'results/edit',
            'data' => ['result' => $result]
        ];
    }
    
    /**
     * Update result
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return ['error' => 'Result not found', 'status' => 404];
        }
        
        $data = [
            'position' => $_POST['position'] ?? null,
            'chest_number' => $_POST['chest_number'] ?? '',
            'performance_time' => $_POST['performance_time'] ?? '',
            'performance_distance' => $_POST['performance_distance'] ?? null,
            'status' => $_POST['status'] ?? 'completed',
            'remarks' => $_POST['remarks'] ?? ''
        ];
        
        if ($this->result->update($id, $data)) {
            return ['redirect' => '/results/' . $id, 'message' => 'Result updated successfully'];
        }
        
        return ['error' => 'Failed to update result', 'status' => 500];
    }
    
    /**
     * Delete result
     */
    public function delete() {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return ['error' => 'Result not found', 'status' => 404];
        }
        
        if ($this->result->delete($id)) {
            return ['redirect' => '/results', 'message' => 'Result deleted successfully'];
        }
        
        return ['error' => 'Failed to delete result', 'status' => 500];
    }
}
