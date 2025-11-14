<?php
/**
 * Database schema initialization
 * Creates all necessary tables for the RMS application
 */

function initializeDatabase($db) {
    $tables = [
        // Events table
        "CREATE TABLE IF NOT EXISTS events (
            id INT PRIMARY KEY AUTO_INCREMENT,
            organization_id INT DEFAULT 1,
            name VARCHAR(255) NOT NULL,
            type VARCHAR(50) DEFAULT 'cross_country',
            location VARCHAR(255),
            event_date DATETIME NOT NULL,
            description TEXT,
            status VARCHAR(20) DEFAULT 'upcoming',
            created_at DATETIME,
            updated_at DATETIME
        )",
        
        // Event categories (races)
        "CREATE TABLE IF NOT EXISTS event_categories (
            id INT PRIMARY KEY AUTO_INCREMENT,
            event_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            gender VARCHAR(10) DEFAULT 'mixed',
            distance VARCHAR(50),
            description TEXT,
            created_at DATETIME,
            FOREIGN KEY (event_id) REFERENCES events(id)
        )",
        
        // Athletes table
        "CREATE TABLE IF NOT EXISTS athletes (
            id INT PRIMARY KEY AUTO_INCREMENT,
            bib_number VARCHAR(50),
            name VARCHAR(255) NOT NULL,
            gender VARCHAR(1),
            date_of_birth DATE,
            region_id INT,
            team_id INT,
            school_id INT,
            district VARCHAR(100),
            class VARCHAR(50),
            is_active TINYINT DEFAULT 1,
            created_at DATETIME,
            updated_at DATETIME
        )",
        
        // Teams table
        "CREATE TABLE IF NOT EXISTS teams (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            code VARCHAR(50),
            region_id INT,
            type VARCHAR(50) DEFAULT 'school',
            contact_person VARCHAR(255),
            contact_email VARCHAR(255),
            contact_phone VARCHAR(20),
            is_active TINYINT DEFAULT 1,
            created_at DATETIME,
            updated_at DATETIME
        )",
        
        // Results table
        "CREATE TABLE IF NOT EXISTS results (
            id INT PRIMARY KEY AUTO_INCREMENT,
            event_category_id INT NOT NULL,
            athlete_id INT NOT NULL,
            position INT,
            chest_number VARCHAR(50),
            performance_time VARCHAR(50),
            performance_distance DECIMAL(10,2),
            points INT DEFAULT 0,
            status VARCHAR(20) DEFAULT 'completed',
            remarks TEXT,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY (event_category_id) REFERENCES event_categories(id),
            FOREIGN KEY (athlete_id) REFERENCES athletes(id)
        )",
        
        // Team rankings table
        "CREATE TABLE IF NOT EXISTS team_rankings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            event_id INT NOT NULL,
            team_id INT NOT NULL,
            category VARCHAR(50) DEFAULT 'overall',
            total_points INT DEFAULT 0,
            rank_position INT,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY (event_id) REFERENCES events(id),
            FOREIGN KEY (team_id) REFERENCES teams(id)
        )",
        
        // Backup logs table
        "CREATE TABLE IF NOT EXISTS backup_logs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            filename VARCHAR(255) NOT NULL,
            filepath VARCHAR(500),
            size BIGINT,
            status VARCHAR(20) DEFAULT 'pending',
            created_at DATETIME
        )",
        
        // Settings table
        "CREATE TABLE IF NOT EXISTS settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL UNIQUE,
            value LONGTEXT,
            created_at DATETIME,
            updated_at DATETIME
        )",
        
        // Import logs table
        "CREATE TABLE IF NOT EXISTS imports (
            id INT PRIMARY KEY AUTO_INCREMENT,
            type VARCHAR(50) NOT NULL,
            filename VARCHAR(255),
            filepath VARCHAR(500),
            original_filename VARCHAR(255),
            file_size BIGINT,
            row_count INT DEFAULT 0,
            success_count INT DEFAULT 0,
            error_count INT DEFAULT 0,
            status VARCHAR(20) DEFAULT 'pending',
            notes TEXT,
            created_at DATETIME,
            updated_at DATETIME
        )",
        
        // Import error logs
        "CREATE TABLE IF NOT EXISTS import_logs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            import_id INT NOT NULL,
            row_number INT,
            status VARCHAR(20),
            message TEXT,
            created_at DATETIME,
            FOREIGN KEY (import_id) REFERENCES imports(id)
        )",
        
        // Event registrations table
        "CREATE TABLE IF NOT EXISTS registrations (
            id INT PRIMARY KEY AUTO_INCREMENT,
            event_id INT NOT NULL,
            event_category_id INT,
            athlete_name VARCHAR(255) NOT NULL,
            athlete_email VARCHAR(255),
            athlete_phone VARCHAR(20),
            athlete_dob DATE,
            club_id INT,
            region_id INT,
            bib_number VARCHAR(50),
            status VARCHAR(20) DEFAULT 'pending',
            notes TEXT,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY (event_id) REFERENCES events(id)
        )",
        
        // Regions table (optional, for organization)
        "CREATE TABLE IF NOT EXISTS regions (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            code VARCHAR(50),
            description TEXT,
            created_at DATETIME
        )"
    ];
    
    $created = 0;
    foreach ($tables as $sql) {
        try {
            $db->query($sql);
            $created++;
        } catch (Exception $e) {
            error_log("Table creation error: " . $e->getMessage());
        }
    }
    
    return $created;
}
