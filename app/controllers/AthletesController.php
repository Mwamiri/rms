<?php
/**
 * Athletes Controller
 * Handles athlete management
 */

class AthletesController {
    private $athlete;
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
        $this->athlete = new Athlete($database);
    }
    
    /**
     * List all athletes
     */
    public function index() {
        $page = $_GET['page'] ?? 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $athletes = $this->athlete->getAll($limit, $offset);
        $total = $this->athlete->count();
        $pages = ceil($total / $limit);
        
        return [
            'view' => 'athletes/index',
            'data' => [
                'athletes' => $athletes,
                'page' => $page,
                'pages' => $pages,
                'total' => $total
            ]
        ];
    }
    
    /**
     * Show athlete details
     */
    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return ['error' => 'Athlete not found', 'status' => 404];
        }
        
        $athlete = $this->athlete->getById($id);
        if (!$athlete) {
            return ['error' => 'Athlete not found', 'status' => 404];
        }
        
        // Get athlete's results
        $result = new Result($this->db);
        $results = $result->getByAthlete($id);
        
        return [
            'view' => 'athletes/show',
            'data' => [
                'athlete' => $athlete,
                'results' => $results
            ]
        ];
    }
    
    /**
     * Show create form
     */
    public function create() {
        return [
            'view' => 'athletes/form',
            'data' => ['athlete' => null, 'action' => 'create']
        ];
    }
    
    /**
     * Store new athlete
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'bib_number' => $_POST['bib_number'] ?? '',
            'gender' => $_POST['gender'] ?? 'M',
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'district' => $_POST['district'] ?? '',
            'team_id' => $_POST['team_id'] ?? null,
            'school_id' => $_POST['school_id'] ?? null
        ];
        
        if (!$data['name']) {
            return ['error' => 'Athlete name is required', 'status' => 400];
        }
        
        if ($this->athlete->create($data)) {
            return ['redirect' => '/athletes', 'message' => 'Athlete created successfully'];
        }
        
        return ['error' => 'Failed to create athlete', 'status' => 500];
    }
    
    /**
     * Show edit form
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return ['error' => 'Athlete not found', 'status' => 404];
        }
        
        $athlete = $this->athlete->getById($id);
        if (!$athlete) {
            return ['error' => 'Athlete not found', 'status' => 404];
        }
        
        return [
            'view' => 'athletes/form',
            'data' => ['athlete' => $athlete, 'action' => 'edit']
        ];
    }
    
    /**
     * Update athlete
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return ['error' => 'Athlete not found', 'status' => 404];
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'bib_number' => $_POST['bib_number'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'district' => $_POST['district'] ?? '',
            'team_id' => $_POST['team_id'] ?? null
        ];
        
        if ($this->athlete->update($id, $data)) {
            return ['redirect' => '/athletes/' . $id, 'message' => 'Athlete updated successfully'];
        }
        
        return ['error' => 'Failed to update athlete', 'status' => 500];
    }
    
    /**
     * Search athletes
     */
    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            return ['error' => 'Search query too short', 'status' => 400];
        }
        
        $athletes = $this->athlete->search($query);
        
        return [
            'json' => true,
            'data' => $athletes
        ];
    }
    
    /**
     * Import athletes from CSV
     */
    public function import() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['view' => 'athletes/import', 'data' => []];
        }
        
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
            return ['error' => 'No file uploaded', 'status' => 400];
        }
        
        $file = $_FILES['file']['tmp_name'];
        $athletes = [];
        
        // Parse CSV
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ',');
            
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $athletes[] = [
                    'name' => trim($row[0] ?? ''),
                    'bib_number' => trim($row[1] ?? ''),
                    'gender' => trim($row[2] ?? 'M'),
                    'district' => trim($row[3] ?? ''),
                    'team_id' => empty($row[4]) ? null : intval($row[4])
                ];
            }
            fclose($handle);
        }
        
        $imported = $this->athlete->importBatch($athletes);
        
        return [
            'redirect' => '/athletes',
            'message' => 'Imported ' . $imported . ' athletes'
        ];
    }
}
