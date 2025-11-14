<?php
/**
 * Events Controller
 * Handles event management
 */

class EventsController {
    private $event;
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
        $this->event = new Event($database);
    }
    
    /**
     * List all events
     */
    public function index() {
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $events = $this->event->getAll($limit, $offset);
        $total = $this->db->getRow("SELECT COUNT(*) as count FROM events")['count'];
        $pages = ceil($total / $limit);
        
        return [
            'view' => 'events/index',
            'data' => [
                'events' => $events,
                'page' => $page,
                'pages' => $pages,
                'total' => $total
            ]
        ];
    }
    
    /**
     * Show event details
     */
    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $event = $this->event->getById($id);
        if (!$event) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $categories = $this->event->getCategories($id);
        
        return [
            'view' => 'events/show',
            'data' => [
                'event' => $event,
                'categories' => $categories
            ]
        ];
    }
    
    /**
     * Show create form
     */
    public function create() {
        return [
            'view' => 'events/form',
            'data' => ['event' => null, 'action' => 'create']
        ];
    }
    
    /**
     * Store new event
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'type' => $_POST['type'] ?? 'cross_country',
            'location' => $_POST['location'] ?? '',
            'event_date' => $_POST['event_date'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];
        
        if (!$data['name']) {
            return ['error' => 'Event name is required', 'status' => 400];
        }
        
        if ($this->event->create($data)) {
            return ['redirect' => '/events', 'message' => 'Event created successfully'];
        }
        
        return ['error' => 'Failed to create event', 'status' => 500];
    }
    
    /**
     * Show edit form
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $event = $this->event->getById($id);
        if (!$event) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        return [
            'view' => 'events/form',
            'data' => ['event' => $event, 'action' => 'edit']
        ];
    }
    
    /**
     * Update event
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'type' => $_POST['type'] ?? '',
            'location' => $_POST['location'] ?? '',
            'event_date' => $_POST['event_date'] ?? '',
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'upcoming'
        ];
        
        if ($this->event->update($id, $data)) {
            return ['redirect' => '/events/' . $id, 'message' => 'Event updated successfully'];
        }
        
        return ['error' => 'Failed to update event', 'status' => 500];
    }
    
    /**
     * Delete event
     */
    public function delete() {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        if ($this->event->delete($id)) {
            return ['redirect' => '/events', 'message' => 'Event deleted successfully'];
        }
        
        return ['error' => 'Failed to delete event', 'status' => 500];
    }
    
    /**
     * Add category to event
     */
    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $eventId = $_POST['event_id'] ?? null;
        if (!$eventId) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'gender' => $_POST['gender'] ?? 'mixed',
            'distance' => $_POST['distance'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];
        
        if (!$data['name']) {
            return ['error' => 'Category name is required', 'status' => 400];
        }
        
        if ($this->event->createCategory($eventId, $data)) {
            return ['redirect' => '/events/' . $eventId, 'message' => 'Category added successfully'];
        }
        
        return ['error' => 'Failed to add category', 'status' => 500];
    }
}
