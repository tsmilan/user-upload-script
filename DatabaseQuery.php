<?php

/**
 * This class contains database query helper methods
 * 
 * @author Trisha Milan <tshmilan@gmail.com>
 */

class DatabaseQuery
{
    private $mysql;
    /**
     * 
     * @param array $config Database configuration array
     */
    public function __construct($config){
        $instance=Database::getInstance($config);
        $this->mysql = $instance->getConnection();
    }
    /**
     * Builds a query string to create users table if it does not exist yet.
     * @param array $tableFields Table field names for the table to be created
     * @return string MySQL CREATE table query
     */
    private function buildCreateTableQuery($tableFields)
    {
        $sql  = "CREATE TABLE IF NOT EXISTS users (";
        $sql .= "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
        foreach ($tableFields as $field) {
            $sql .= "$field VARCHAR(50) NOT NULL,";
        }
        $sql .= "UNIQUE KEY UNIQUE_EMAIL (email)";
        $sql .= ")";

        return $sql;
    }
    /**
     * Create users table if it does not exist yet.
     * @param type $tableFields Table field names for the table to be created
     * @return string Returns the success or error message in the query
     */
    public function createTable($tableFields)
    {
        $sql = $this->buildCreateTableQuery($tableFields);

        if ($this->mysql->query($sql) === TRUE) {
            return "Users table was created successfully!";
        } else {
            return "Error creating table: " . $this->mysql->error;
        }
    }
}
