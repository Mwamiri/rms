<?php
/**
 * Registration Controller
 * Handles public event registrations
 */

class RegistrationController {
    private $registration;
    private $event;
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
        $this->registration = new Registration($database);
        $this->event = new Event($database);
    }
    
    /**
     * Show public registration form (public endpoint - no auth needed)
     */
    public function form() {
        $eventId = $_GET['event_id'] ?? null;
        if (!$eventId) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $event = $this->event->getById($eventId);
        if (!$event) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $categories = $this->event->getCategories($eventId);
        $clubs = $this->db->getResults("SELECT id, name FROM teams ORDER BY name ASC");
        $regions = $this->db->getResults("SELECT id, name FROM regions ORDER BY name ASC");
        
        return [
            'view' => 'registrations/public_form',
            'data' => [
                'event' => $event,
                'categories' => $categories,
                'clubs' => $clubs,
                'regions' => $regions
            ]
        ];
    }
    
    /**
     * Submit registration (public endpoint)
     */
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $eventId = $_POST['event_id'] ?? null;
        if (!$eventId) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        // Validate required fields
        $required = ['athlete_name', 'athlete_email', 'athlete_phone'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return ['error' => ucfirst(str_replace('_', ' ', $field)) . ' is required', 'status' => 400];
            }
        }
        
        // Validate email
        if (!filter_var($_POST['athlete_email'], FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Invalid email address', 'status' => 400];
        }
        
        // Create registration
        $data = [
            'event_id' => $eventId,
            'event_category_id' => $_POST['event_category_id'] ?? null,
            'athlete_name' => trim($_POST['athlete_name']),
            'athlete_email' => trim($_POST['athlete_email']),
            'athlete_phone' => trim($_POST['athlete_phone']),
            'athlete_dob' => $_POST['athlete_dob'] ?? null,
            'club_id' => $_POST['club_id'] ?? null,
            'region_id' => $_POST['region_id'] ?? null,
            'bib_number' => $_POST['bib_number'] ?? '',
            'notes' => $_POST['notes'] ?? ''
        ];
        
        if ($this->registration->create($data)) {
            // Send confirmation email (optional)
            // $this->sendConfirmationEmail($data);
            
            return [
                'redirect' => '/registrations/confirm',
                'message' => 'Registration submitted successfully. Await approval.'
            ];
        }
        
        return ['error' => 'Failed to submit registration', 'status' => 500];
    }
    
    /**
     * Show confirmation page
     */
    public function confirm() {
        return [
            'view' => 'registrations/confirm',
            'data' => []
        ];
    }
    
    /**
     * Admin: List registrations for event
     */
    public function list() {
        $eventId = $_GET['event_id'] ?? null;
        if (!$eventId) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $event = $this->event->getById($eventId);
        if (!$event) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $status = $_GET['status'] ?? null;
        $registrations = $this->registration->getByEvent($eventId, $status);
        $counts = $this->registration->countByStatus($eventId);
        
        return [
            'view' => 'registrations/list',
            'data' => [
                'event' => $event,
                'registrations' => $registrations,
                'counts' => $counts,
                'currentStatus' => $status
            ]
        ];
    }
    
    /**
     * Admin: View registration details
     */
    public function view() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return ['error' => 'Registration not found', 'status' => 404];
        }
        
        $registration = $this->registration->getById($id);
        if (!$registration) {
            return ['error' => 'Registration not found', 'status' => 404];
        }
        
        return [
            'view' => 'registrations/view',
            'data' => [
                'registration' => $registration
            ]
        ];
    }
    
    /**
     * Admin: Approve registration
     */
    public function approve() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return ['error' => 'Registration not found', 'status' => 404];
        }
        
        if ($this->registration->approve($id)) {
            return ['redirect' => '/registrations/view?id=' . $id, 'message' => 'Registration approved and athlete created'];
        }
        
        return ['error' => 'Failed to approve registration', 'status' => 500];
    }
    
    /**
     * Admin: Reject registration
     */
    public function reject() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $id = $_POST['id'] ?? null;
        $reason = $_POST['reason'] ?? '';
        
        if (!$id) {
            return ['error' => 'Registration not found', 'status' => 404];
        }
        
        if ($this->registration->reject($id, $reason)) {
            return ['redirect' => '/registrations/view?id=' . $id, 'message' => 'Registration rejected'];
        }
        
        return ['error' => 'Failed to reject registration', 'status' => 500];
    }
    
    /**
     * Bulk approve registrations
     */
    public function bulkApprove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $ids = $_POST['ids'] ?? [];
        if (empty($ids)) {
            return ['error' => 'No registrations selected', 'status' => 400];
        }
        
        $count = 0;
        foreach ($ids as $id) {
            if ($this->registration->approve($id)) {
                $count++;
            }
        }
        
        return [
            'redirect' => $_POST['return_to'] ?? '/registrations',
            'message' => $count . ' registrations approved'
        ];
    }
    
    /**
     * Export registrations
     */
    public function export() {
        $eventId = $_GET['event_id'] ?? null;
        if (!$eventId) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $registrations = $this->registration->getByEvent($eventId);
        
        // Create CSV
        $csv = "ID,Name,Email,Phone,DOB,Club,Region,Status,Date\n";
        foreach ($registrations as $reg) {
            $csv .= implode(',', [
                $reg['id'],
                '"' . $reg['athlete_name'] . '"',
                $reg['athlete_email'],
                $reg['athlete_phone'],
                $reg['athlete_dob'],
                $reg['club_id'],
                $reg['region_id'],
                $reg['status'],
                $reg['created_at']
            ]) . "\n";
        }
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="registrations_' . $eventId . '.csv"');
        
        return ['output' => $csv];
    }
}
