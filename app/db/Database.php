<?php

/**
 * This class establishes a database connection
 * 
 * @author Trisha Milan <tshmilan@gmail.com>
 */

class Database
{
    private $hostname;
    private $username;
    private $password;
    private $dbname;
    private $port;
    private $socket;
    private $mysql;
    
    /**
     * Maps the properties to the configuration array and establishes
     * a database connection
     * 
     * @param array $config Database configuration array
     */
    public function __construct($config)
    {
        $this->hostname = $config['hostname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->dbname = DB_NAME;
        $this->port = PORT;
        $this->socket = SOCKET;
 
        $this->connect();
    }
    /**
     * Connect to database using MySQLi.
     * 
     * @return object Returns MySQL object
     */
    private function connect()
    {
        try {
            $this->mysql = new mysqli(
                $this->hostname,
                $this->username,
                $this->password,
                $this->dbname,
                $this->port, 
                $this->socket
            );
            return $this->mysql;
        } catch(Exception $error) {
            return $error;
        }
    }
    /**
     * Get the current database connection
     * 
     * @return object MySQL connection object
     */
    public function getConnection()
    {
        return $this->mysql;
    }
}