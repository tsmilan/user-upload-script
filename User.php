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

        if ($this->isValidEmail($email)) {
        $DatabaseQuery->insertUser($this);
        } else {
        echo("$email is not a valid email address");
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