<?php
/**
 * Import & Registration Service
 * Utilities for file import and registration processing
 */

class FileImportService {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Generate sample templates
     */
    public function generateTemplate($type) {
        $templates = [
            'athletes' => [
                'headers' => ['name', 'bib_number', 'gender', 'dob', 'district', 'team_id'],
                'description' => 'Athlete import template',
                'example' => ['John Kipchoge', '101', 'M', '2000-01-15', 'Eldoret', '1']
            ],
            'teams' => [
                'headers' => ['name', 'code', 'region_id', 'type', 'contact_person', 'contact_email'],
                'description' => 'Team/Club import template',
                'example' => ['Elite Running Club', 'ERC01', '1', 'club', 'John Doe', 'john@club.com']
            ],
            'registrations' => [
                'headers' => ['athlete_name', 'athlete_email', 'athlete_phone', 'athlete_dob', 'club_id', 'region_id', 'category_id', 'bib_number'],
                'description' => 'Event registration import',
                'example' => ['Jane Smith', 'jane@example.com', '0712345678', '1995-05-20', '1', '1', '1', '201']
            ]
        ];
        
        return $templates[$type] ?? $templates['athletes'];
    }
    
    /**
     * Validate import data
     */
    public function validateData($data, $type) {
        $errors = [];
        $warnings = [];
        
        if (empty($data)) {
            $errors[] = 'File is empty';
            return ['valid' => false, 'errors' => $errors];
        }
        
        foreach ($data as $rowNum => $row) {
            switch ($type) {
                case 'athletes':
                    if (empty($row['name'])) {
                        $errors[] = "Row " . ($rowNum + 2) . ": Missing athlete name";
                    }
                    break;
                    
                case 'teams':
                    if (empty($row['name'])) {
                        $errors[] = "Row " . ($rowNum + 2) . ": Missing team name";
                    }
                    break;
                    
                case 'registrations':
                    if (empty($row['athlete_name'])) {
                        $errors[] = "Row " . ($rowNum + 2) . ": Missing athlete name";
                    }
                    if (empty($row['athlete_email'])) {
                        $errors[] = "Row " . ($rowNum + 2) . ": Missing athlete email";
                    }
                    break;
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'count' => count($data)
        ];
    }
    
    /**
     * Download template as CSV
     */
    public function downloadTemplate($type) {
        $template = $this->generateTemplate($type);
        
        $csv = '';
        // Headers with descriptions
        $csv .= "# " . $template['description'] . "\n";
        $csv .= "# Headers: " . implode(', ', $template['headers']) . "\n";
        $csv .= implode(',', $template['headers']) . "\n";
        $csv .= implode(',', $template['example']) . "\n";
        
        return $csv;
    }
    
    /**
     * Get Google Sheets download link
     */
    public function getGoogleSheetsTemplate($type) {
        $templates = [
            'athletes' => 'https://docs.google.com/spreadsheets/d/TEMPLATE_ID_ATHLETES/export?format=csv',
            'teams' => 'https://docs.google.com/spreadsheets/d/TEMPLATE_ID_TEAMS/export?format=csv',
            'registrations' => 'https://docs.google.com/spreadsheets/d/TEMPLATE_ID_REGISTRATIONS/export?format=csv'
        ];
        
        return $templates[$type] ?? '';
    }
    
    /**
     * Create sample data for testing
     */
    public function createSampleData($type) {
        $samples = [
            'athletes' => [
                ['name' => 'John Kipchoge', 'bib_number' => '101', 'gender' => 'M', 'dob' => '2000-01-15', 'district' => 'Eldoret', 'team_id' => '1'],
                ['name' => 'Jane Smith', 'bib_number' => '102', 'gender' => 'F', 'dob' => '2001-03-22', 'district' => 'Nairobi', 'team_id' => '2'],
                ['name' => 'Peter Johnson', 'bib_number' => '103', 'gender' => 'M', 'dob' => '1999-07-10', 'district' => 'Kisumu', 'team_id' => '1']
            ],
            'teams' => [
                ['name' => 'Elite Running', 'code' => 'ELR', 'region_id' => '1', 'type' => 'club', 'contact_person' => 'John Doe', 'contact_email' => 'john@elite.com'],
                ['name' => 'Sprint Stars', 'code' => 'SPT', 'region_id' => '2', 'type' => 'school', 'contact_person' => 'Mary Jones', 'contact_email' => 'mary@sprint.com']
            ]
        ];
        
        return $samples[$type] ?? [];
    }
    
    /**
     * Merge registrations into athletes
     */
    public function mergeRegistrationsToAthletes($eventId) {
        $registrations = $this->db->getResults(
            "SELECT * FROM registrations WHERE event_id = ? AND status = 'approved'",
            [$eventId]
        );
        
        $merged = 0;
        $athlete = new Athlete($this->db);
        
        foreach ($registrations as $reg) {
            // Check if athlete already exists
            $existing = $this->db->getRow(
                "SELECT id FROM athletes WHERE name = ? AND team_id = ?",
                [$reg['athlete_name'], $reg['club_id']]
            );
            
            if (!$existing) {
                $athleteData = [
                    'name' => $reg['athlete_name'],
                    'bib_number' => $reg['bib_number'],
                    'gender' => 'M',
                    'date_of_birth' => $reg['athlete_dob'],
                    'team_id' => $reg['club_id'],
                    'region_id' => $reg['region_id']
                ];
                
                if ($athlete->create($athleteData)) {
                    $merged++;
                }
            }
        }
        
        return $merged;
    }
}
