<?php
/**
 * Team Ranking Model
 * Manages team standings and rankings
 */

class TeamRanking {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Get rankings for event
     */
    public function getEventRankings($eventId, $category = 'overall') {
        $sql = "SELECT tr.*, t.name as team_name, t.code as team_code
                FROM team_rankings tr
                JOIN teams t ON tr.team_id = t.id
                WHERE tr.event_id = ? AND tr.category = ?
                ORDER BY tr.rank_position ASC";
        return $this->db->getResults($sql, [$eventId, $category]);
    }
    
    /**
     * Get team ranking
     */
    public function getTeamRanking($eventId, $teamId, $category = 'overall') {
        $sql = "SELECT * FROM team_rankings 
                WHERE event_id = ? AND team_id = ? AND category = ?";
        return $this->db->getRow($sql, [$eventId, $teamId, $category]);
    }
    
    /**
     * Calculate and update rankings
     */
    public function calculateRankings($eventId, $category = 'overall') {
        // Get all teams in event
        $sql = "SELECT DISTINCT a.team_id
                FROM results r
                JOIN event_categories ec ON r.event_category_id = ec.id
                JOIN athletes a ON r.athlete_id = a.id
                WHERE ec.event_id = ? AND r.status = 'completed'";
        
        $teams = $this->db->getResults($sql, [$eventId]);
        
        $rankings = [];
        foreach ($teams as $team) {
            if (!$team['team_id']) continue;
            
            // Calculate total points
            $pointsSql = "SELECT COALESCE(SUM(r.points), 0) as total_points
                         FROM results r
                         JOIN event_categories ec ON r.event_category_id = ec.id
                         JOIN athletes a ON r.athlete_id = a.id
                         WHERE ec.event_id = ? AND a.team_id = ? AND r.status = 'completed'";
            
            $points = $this->db->getRow($pointsSql, [$eventId, $team['team_id']]);
            
            $rankings[] = [
                'team_id' => $team['team_id'],
                'points' => $points['total_points'] ?? 0
            ];
        }
        
        // Sort by points descending
        usort($rankings, function($a, $b) {
            return $b['points'] - $a['points'];
        });
        
        // Update rankings
        $position = 1;
        foreach ($rankings as $ranking) {
            $existing = $this->getTeamRanking($eventId, $ranking['team_id'], $category);
            
            if ($existing) {
                $this->db->update('team_rankings', [
                    'total_points' => $ranking['points'],
                    'rank_position' => $position,
                    'updated_at' => date('Y-m-d H:i:s')
                ], ['id' => $existing['id']]);
            } else {
                $this->db->insert('team_rankings', [
                    'event_id' => $eventId,
                    'team_id' => $ranking['team_id'],
                    'category' => $category,
                    'total_points' => $ranking['points'],
                    'rank_position' => $position,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            $position++;
        }
        
        return count($rankings);
    }
    
    /**
     * Get top teams
     */
    public function getTopTeams($eventId, $limit = 10) {
        $sql = "SELECT tr.*, t.name as team_name, t.code as team_code
                FROM team_rankings tr
                JOIN teams t ON tr.team_id = t.id
                WHERE tr.event_id = ? AND tr.category = 'overall'
                ORDER BY tr.rank_position ASC
                LIMIT ?";
        return $this->db->getResults($sql, [$eventId, $limit]);
    }
    
    /**
     * Get team history across events
     */
    public function getTeamHistory($teamId, $limit = 5) {
        $sql = "SELECT tr.*, e.name as event_name, e.event_date
                FROM team_rankings tr
                JOIN events e ON tr.event_id = e.id
                WHERE tr.team_id = ? AND tr.category = 'overall'
                ORDER BY e.event_date DESC
                LIMIT ?";
        return $this->db->getResults($sql, [$teamId, $limit]);
    }
}
