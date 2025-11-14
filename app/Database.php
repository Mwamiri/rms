<?php
/**
 * Database Connection Class
 * Simple MySQL wrapper (no PDO dependency)
 */

class Database {
    private $connection;
    private $config;
    
    public function __construct($config) {
        $this->config = $config;
        $this->connect();
    }
    
    /**
     * Connect to database
     */
    private function connect() {
        try {
            $this->connection = new mysqli(
                $this->config['host'],
                $this->config['username'],
                $this->config['password'],
                $this->config['database'],
                $this->config['port']
            );
            
            if ($this->connection->connect_error) {
                throw new Exception('Database connection failed: ' . $this->connection->connect_error);
            }
            
            $this->connection->set_charset($this->config['charset']);
        } catch (Exception $e) {
            die('Database Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Execute query
     */
    public function query($sql, $params = []) {
        if (!empty($params)) {
            $sql = $this->bindParams($sql, $params);
        }
        
        $result = $this->connection->query($sql);
        
        if ($this->connection->error) {
            throw new Exception('Database Error: ' . $this->connection->error);
        }
        
        return $result;
    }
    
    /**
     * Get single row
     */
    public function getRow($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Get all rows
     */
    public function getResults($sql, $params = []) {
        $result = $this->query($sql, $params);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }
    
    /**
     * Insert record
     */
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $this->query($sql, array_values($data));
        
        return $this->connection->insert_id;
    }
    
    /**
     * Update record
     */
    public function update($table, $data, $where) {
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
        }
        $set = implode(', ', $set);
        
        $whereClause = [];
        foreach ($where as $column => $value) {
            $whereClause[] = "$column = ?";
        }
        $where = implode(' AND ', $whereClause);
        
        $sql = "UPDATE $table SET $set WHERE $where";
        $params = array_merge(array_values($data), array_values($where));
        
        return $this->query($sql, $params);
    }
    
    /**
     * Delete record
     */
    public function delete($table, $where) {
        $whereClause = [];
        foreach ($where as $column => $value) {
            $whereClause[] = "$column = ?";
        }
        $where = implode(' AND ', $whereClause);
        
        $sql = "DELETE FROM $table WHERE $where";
        return $this->query($sql, array_values($where));
    }
    
    /**
     * Bind parameters safely
     */
    private function bindParams($sql, $params) {
        foreach ($params as $param) {
            $param = $this->connection->real_escape_string($param);
            $sql = preg_replace('/\?/', "'$param'", $sql, 1);
        }
        return $sql;
    }
    
    /**
     * Get connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Close connection
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
