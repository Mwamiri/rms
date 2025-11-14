<?php
/**
 * Dashboard Controller
 * Main admin dashboard
 */

class DashboardController {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Show dashboard
     */
    public function index() {
        $stats = [
            'total_events' => $this->getEventStats(),
            'total_athletes' => $this->getAthleteStats(),
            'total_teams' => $this->getTeamStats(),
            'recent_results' => $this->getRecentResults(5),
            'upcoming_events' => $this->getUpcomingEvents(5),
            'top_athletes' => $this->getTopAthletes(5),
            'top_teams' => $this->getTopTeams(5)
        ];
        
        return [
            'view' => 'dashboard/index',
            'data' => $stats
        ];
    }
    
    /**
     * Get event statistics
     */
    private function getEventStats() {
        $total = $this->db->getRow("SELECT COUNT(*) as count FROM events")['count'];
        $upcoming = $this->db->getRow(
            "SELECT COUNT(*) as count FROM events WHERE status = 'upcoming' AND event_date >= NOW()"
        )['count'];
        $completed = $this->db->getRow(
            "SELECT COUNT(*) as count FROM events WHERE status = 'completed'"
        )['count'];
        
        return [
            'total' => $total,
            'upcoming' => $upcoming,
            'completed' => $completed
        ];
    }
    
    /**
     * Get athlete statistics
     */
    private function getAthleteStats() {
        $total = $this->db->getRow("SELECT COUNT(*) as count FROM athletes WHERE is_active = 1")['count'];
        $male = $this->db->getRow("SELECT COUNT(*) as count FROM athletes WHERE gender = 'M' AND is_active = 1")['count'];
        $female = $this->db->getRow("SELECT COUNT(*) as count FROM athletes WHERE gender = 'F' AND is_active = 1")['count'];
        
        return [
            'total' => $total,
            'male' => $male,
            'female' => $female
        ];
    }
    
    /**
     * Get team statistics
     */
    private function getTeamStats() {
        return $this->db->getRow("SELECT COUNT(*) as count FROM teams")['count'];
    }
    
    /**
     * Get recent results
     */
    private function getRecentResults($limit = 5) {
        return $this->db->getResults(
            "SELECT r.*, a.name as athlete_name, ec.name as category_name, e.name as event_name
             FROM results r
             JOIN athletes a ON r.athlete_id = a.id
             JOIN event_categories ec ON r.event_category_id = ec.id
             JOIN events e ON ec.event_id = e.id
             ORDER BY r.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }
    
    /**
     * Get upcoming events
     */
    private function getUpcomingEvents($limit = 5) {
        return $this->db->getResults(
            "SELECT * FROM events 
             WHERE status = 'upcoming' AND event_date >= NOW()
             ORDER BY event_date ASC
             LIMIT ?",
            [$limit]
        );
    }
    
    /**
     * Get top athletes
     */
    private function getTopAthletes($limit = 5) {
        return $this->db->getResults(
            "SELECT a.*, COUNT(r.id) as races, SUM(r.points) as total_points
             FROM athletes a
             LEFT JOIN results r ON a.id = r.athlete_id
             WHERE a.is_active = 1
             GROUP BY a.id
             ORDER BY total_points DESC
             LIMIT ?",
            [$limit]
        );
    }
    
    /**
     * Get top teams
     */
    private function getTopTeams($limit = 5) {
        return $this->db->getResults(
            "SELECT t.*, COALESCE(SUM(tr.total_points), 0) as total_points
             FROM teams t
             LEFT JOIN team_rankings tr ON t.id = tr.team_id
             GROUP BY t.id
             ORDER BY total_points DESC
             LIMIT ?",
            [$limit]
        );
    }
    
    /**
     * Get system info
     */
    public function info() {
        $info = [
            'php_version' => phpversion(),
            'mysql_version' => $this->db->getRow("SELECT VERSION() as version")['version'],
            'server_os' => php_uname(),
            'memory_limit' => ini_get('memory_limit'),
            'max_upload' => ini_get('upload_max_filesize'),
            'timezone' => date_default_timezone_get()
        ];
        
        return [
            'view' => 'dashboard/info',
            'data' => $info
        ];
    }
}
