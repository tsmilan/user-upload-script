<?php

/**
 * This class establishes a database connection using singleton pattern
 * 
 * @author Trisha Milan <tshmilan@gmail.com>
 */

class Database {

    private $hostname;
    private $username;
    private $password;
    private $dbname;
    private $port;
    private $socket;
    private $mysql;
    
    private static $instance = null;

    /**
     * Maps the properties to the configuration array and establishes
     * a database connection
     * 
     * @param array $config Database configuration array
     */
    private function __construct($config)
    {
        $this->hostname = $config['hostname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->dbname = $config['dbname'];
        $this->port = $config['port'];
        $this->socket = $config['socket'];
 
        $this->connect();
    }

    /**
     * Connect to database using MySQLi.
     * 
     * @param array $config Database credentials
     * @return object Returns an instance of database connection
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
            error_log($error);
        }
    }
    
    /**
     * Returns an existing instance of database connection or creates a new one
     * if it does not exist yet.
     * 
     * @param array $config Database configuration array
     * @return object An instance of Database connection
     */
    public static function getInstance($config)
    {
        if (!self::$instance)
        {
          self::$instance = new Database($config);
        }

        return self::$instance;
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