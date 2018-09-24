<?php

/**
 * User class
 * @author Trisha Milan <tshmilan@gmail.com>
 */

class User
{
    public $name;
    public $surname;
    public $email;
    /**
     * 
     * @param string $name Name
     * @param string $surname Surname
     * @param string $email Email
     */
    public function __construct($name, $surname, $email) 
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
    }
    /**
     * Check if email is valid
     * @param string $email
     * @return boolean Returns true if email is valid and false if not
     */
    public function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    /**
     * Fixes user name, surname and email casing
     */
    public function fixUserCaps()
    {
        // capitalize first character of each word in a string for people with second names
        $this->name = ucwords(strtolower($this->name));
        $this->surname = ucfirst(strtolower($this->surname));
        $this->email = strtolower($this->email);
    }
    /**
     * Removes trailing white space in user name, surname and email
     */
    public function trimWhiteSpace()
    {
        $this->name = trim($this->name);
        $this->surname = trim($this->surname);
        $this->email = trim($this->email);
    }
    /**
     * Insert user data to database
     * @param object $DatabaseQuery
     * @return object MySQL result object
     */
    public function save($DatabaseQuery)
    {
        $this->fixUserCaps();
        $email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->trimWhiteSpace();
        // if it passes the validation then insert it to users table
        if ($this->isValidEmail($email)) {
            return $DatabaseQuery->insertUser($this);
        } else {
            return "Error: Invalid email address format $email \n";
        }
    }
    /**
     * Displays the user data to be inserted in the database for the --dry_run command
     */
    public function displayValidUserFormat()
    {
        $this->fixUserCaps();
        $email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->trimWhiteSpace();
        $mask = "| %-10s| %-10s| %-25s|\n";
        
        printf($mask, $this->name, $this->surname, $this->email);

        if (!$this->isValidEmail($email)) {
           echo "Error: Invalid email address format $email \n";
        } 
    }
}