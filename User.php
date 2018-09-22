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

    public function __construct($name, $surname, $email) 
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
    }
    /**
     * Check if email is valid
     * @param type $email
     * @return boolean Returns true if email is valid and false if not
     */
    public function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function fixUserCaps()
    {
        // capitalize first character of each word in a string for people with second names
        $this->name = ucwords(strtolower($this->name));
        $this->surname = ucfirst(strtolower($this->surname));
        $this->email = strtolower($this->email);
    }

    public function trimWhiteSpace()
    {
        $this->name = trim($this->name);
        $this->surname = trim($this->surname);
        $this->email = trim($this->email);
    }

    public function save()
    {
        $this->fixUserCaps();
        $email = filter_var($this->email, FILTER_SANITIZE_EMAIL);

        $DatabaseQuery = new DatabaseQuery($config);
        
        $this->trimWhiteSpace();
        // if it passes the validation then insert it to users table
        if ($this->isValidEmail($email)) {
            return $DatabaseQuery->insertUser($this);
        } else {
            return "Error: Invalid email address format $email \n";
        }
    }

    public function displayValidUserFormat()
    {
        $this->fixUserCaps();
        $email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->trimWhiteSpace();
        $mask = "| %-10s| %-10s| %-25s|\n";
        
        printf($mask, $this->name, $this->surname, $this->email);

        if (!$this->isValidEmail($email)) {
           echo "\nError: Invalid email address format $email \n";
        } 
        
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getEmail()
    {
        return $this->email;
    }
}