<?php
/**
 * Report Service
 * Generates reports in various formats
 */

class ReportService {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Generate event results report (CSV/Excel format)
     */
    public function generateEventResults($eventId, $format = 'csv') {
        $results = $this->db->getResults(
            "SELECT a.name, a.bib_number, t.name as team_name, ec.name as category_name,
                    r.position, r.performance_time, r.points, r.status
             FROM results r
             JOIN event_categories ec ON r.event_category_id = ec.id
             JOIN athletes a ON r.athlete_id = a.id
             LEFT JOIN teams t ON a.team_id = t.id
             WHERE ec.event_id = ?
             ORDER BY ec.id, r.position ASC",
            [$eventId]
        );
        
        if ($format === 'csv') {
            return $this->arrayToCSV($results);
        }
        
        return $results;
    }
    
    /**
     * Generate team standings report
     */
    public function generateTeamStandings($eventId) {
        $standings = $this->db->getResults(
            "SELECT t.name, t.code, tr.total_points, tr.rank_position,
                    COUNT(DISTINCT a.id) as athlete_count
             FROM team_rankings tr
             JOIN teams t ON tr.team_id = t.id
             LEFT JOIN athletes a ON t.id = a.team_id
             WHERE tr.event_id = ?
             ORDER BY tr.rank_position ASC",
            [$eventId]
        );
        
        return $standings;
    }
    
    /**
     * Generate athlete performance report
     */
    public function generateAthletePerformance($athleteId) {
        $athlete = $this->db->getRow("SELECT * FROM athletes WHERE id = ?", [$athleteId]);
        
        $results = $this->db->getResults(
            "SELECT e.name as event_name, e.event_date, ec.name as category_name,
                    r.position, r.performance_time, r.points, r.status
             FROM results r
             JOIN event_categories ec ON r.event_category_id = ec.id
             JOIN events e ON ec.event_id = e.id
             WHERE r.athlete_id = ?
             ORDER BY e.event_date DESC",
            [$athleteId]
        );
        
        $totalPoints = array_sum(array_column($results, 'points'));
        $totalRaces = count($results);
        $positions = array_filter(array_column($results, 'position'), function($p) { return !is_null($p); });
        $avgPosition = count($positions) > 0 ? array_sum($positions) / count($positions) : 0;
        
        return [
            'athlete' => $athlete,
            'results' => $results,
            'stats' => [
                'total_races' => $totalRaces,
                'total_points' => $totalPoints,
                'avg_position' => round($avgPosition, 2),
                'best_position' => min($positions) ?? null
            ]
        ];
    }
    
    /**
     * Generate summary report
     */
    public function generateSummary() {
        $events = $this->db->getRow(
            "SELECT COUNT(*) as total FROM events"
        )['total'];
        
        $athletes = $this->db->getRow(
            "SELECT COUNT(*) as total FROM athletes WHERE is_active = 1"
        )['total'];
        
        $teams = $this->db->getRow(
            "SELECT COUNT(*) as total FROM teams"
        )['total'];
        
        $results = $this->db->getRow(
            "SELECT COUNT(*) as total FROM results WHERE status = 'completed'"
        )['total'];
        
        return [
            'total_events' => $events,
            'total_athletes' => $athletes,
            'total_teams' => $teams,
            'total_results' => $results,
            'generated_at' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Convert array to CSV format
     */
    private function arrayToCSV($data) {
        if (empty($data)) {
            return '';
        }
        
        $csv = '';
        $headers = array_keys($data[0]);
        
        // Add headers
        $csv .= implode(',', array_map(function($h) { return '"' . $h . '"'; }, $headers)) . "\n";
        
        // Add data
        foreach ($data as $row) {
            $csv .= implode(',', array_map(function($v) { 
                return '"' . addslashes($v ?? '') . '"'; 
            }, $row)) . "\n";
        }
        
        return $csv;
    }
    
    /**
     * Export to file
     */
    public function exportFile($data, $filename, $format = 'csv') {
        $filepath = ROOT_PATH . '/storage/' . $filename;
        
        if ($format === 'csv') {
            file_put_contents($filepath, $data);
        }
        
        return $filepath;
    }
}
