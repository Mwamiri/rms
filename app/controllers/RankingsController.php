<?php
/**
 * Rankings Controller
 * Handles team rankings and standings
 */

class RankingsController {
    private $ranking;
    private $result;
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
        $this->ranking = new TeamRanking($database);
        $this->result = new Result($database);
    }
    
    /**
     * Get rankings for event
     */
    public function event() {
        $eventId = $_GET['event_id'] ?? null;
        if (!$eventId) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $event = $this->db->getRow("SELECT * FROM events WHERE id = ?", [$eventId]);
        if (!$event) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $rankings = $this->ranking->getEventRankings($eventId, 'overall');
        $categories = $this->db->getResults(
            "SELECT * FROM event_categories WHERE event_id = ? ORDER BY name ASC",
            [$eventId]
        );
        
        return [
            'view' => 'rankings/event',
            'data' => [
                'event' => $event,
                'rankings' => $rankings,
                'categories' => $categories
            ]
        ];
    }
    
    /**
     * Calculate rankings for event
     */
    public function calculate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed', 'status' => 405];
        }
        
        $eventId = $_POST['event_id'] ?? null;
        if (!$eventId) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $count = $this->ranking->calculateRankings($eventId, 'overall');
        
        return [
            'redirect' => '/rankings/event?event_id=' . $eventId,
            'message' => 'Rankings calculated for ' . $count . ' teams'
        ];
    }
    
    /**
     * Show team rankings across events
     */
    public function overall() {
        $teamId = $_GET['team_id'] ?? null;
        if (!$teamId) {
            return ['error' => 'Team not found', 'status' => 404];
        }
        
        $team = $this->db->getRow("SELECT * FROM teams WHERE id = ?", [$teamId]);
        if (!$team) {
            return ['error' => 'Team not found', 'status' => 404];
        }
        
        $history = $this->ranking->getTeamHistory($teamId);
        
        return [
            'view' => 'rankings/team',
            'data' => [
                'team' => $team,
                'history' => $history
            ]
        ];
    }
    
    /**
     * Get JSON rankings for AJAX
     */
    public function json() {
        $eventId = $_GET['event_id'] ?? null;
        if (!$eventId) {
            return ['error' => 'Event not found', 'status' => 404];
        }
        
        $rankings = $this->ranking->getEventRankings($eventId, 'overall');
        
        return [
            'json' => true,
            'data' => $rankings
        ];
    }
    
    /**
     * Get category rankings
     */
    public function category() {
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
        
        return [
            'view' => 'rankings/category',
            'data' => [
                'category' => $category,
                'results' => $results
            ]
        ];
    }
    
    /**
     * Top performers
     */
    public function top() {
        $limit = $_GET['limit'] ?? 10;
        
        $topAthletes = $this->db->getResults(
            "SELECT a.*, COUNT(r.id) as total_races, SUM(r.points) as total_points
             FROM athletes a
             LEFT JOIN results r ON a.id = r.athlete_id
             WHERE a.is_active = 1
             GROUP BY a.id
             ORDER BY total_points DESC
             LIMIT ?",
            [$limit]
        );
        
        $topTeams = $this->db->getResults(
            "SELECT t.*, COUNT(DISTINCT e.id) as races_participated, SUM(tr.total_points) as total_points
             FROM teams t
             LEFT JOIN team_rankings tr ON t.id = tr.team_id
             LEFT JOIN events e ON tr.event_id = e.id
             GROUP BY t.id
             ORDER BY total_points DESC
             LIMIT ?",
            [$limit]
        );
        
        return [
            'view' => 'rankings/top',
            'data' => [
                'topAthletes' => $topAthletes,
                'topTeams' => $topTeams
            ]
        ];
    }
}
