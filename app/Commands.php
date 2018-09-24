<?php

/**
 * This class contains command line script directives
 * 
 * @author Trisha Milan <tshmilan@gmail.com>
 */

class Commands
{
    /**
     * Displays help commands
     * @return string Help commands
     */
    private function displayHelp() 
    {
        $message  = "Usage: php user_upload.php [args...]\n";
        $message .= "   --file [csv file name] – The name of the CSV to be parsed\n";
        $message .= "   --create_table – Create users table\n";
        $message .= "   --dry_run – To be used with the --file directive to run the script\n\twithout altering the database \n";
        $message .= "   -u – MySQL username\n";
        $message .= "   -p – MySQL password\n";
        $message .= "   -h – MySQL host\n";
        $message .= "   --help – This help\n";

        return $message;
    }
    /**
     * Create users table
     * @param array $config Database configuration array
     * @return string Returns the success or error message in creating the users table
     */
    private function createTable($config)
    {
        $DatabaseQuery = new DatabaseQuery($config);
       
        if ($DatabaseQuery->mysql->connect_errno == 0) {
           echo $DatabaseQuery->createTable(); 
        } 
       
    }
    /**
     * Will insert users from the CSV file into the users table
     * @param string $csvfile The name of the CSV to be parsed
     * @param array $config Database configuration array
     */
    public function insertFromCSV($csvfile, $config)
    {
        $users = parseCSV($csvfile);
        $affectedRows = 0;
        $output = "";
        $error = "";

        $DatabaseQuery = new DatabaseQuery($config);

        if ($DatabaseQuery->mysql->connect_errno == 0) {
            if ($users) {
                $UsersObj = array();
                for ($i=0; $i < count($users) ; $i++) { 
                    $name = $users[$i]["name"];
                    $surname = $users[$i]["surname"];
                    $email = $users[$i]["email"];
                    // map users from csv file to Users object
                    $UsersObj[$i] = new User($name, $surname, $email); 
                    // save to db
                    $stmt_result = $UsersObj[$i]->save($DatabaseQuery);

                    if (gettype($stmt_result) === "string") {
                        echo $stmt_result;
                    } else {
                        if (!empty($stmt_result->error)) {
                            $error .=  $stmt_result->error."\n";
                        }

                        if($stmt_result->affected_rows == 1) {
                            $affectedRows++;
                        }
                    }
                }

                if (!empty($error)) {
                    $output .= $error;
                }

                if (intval($affectedRows) > 0) {
                    $output .= $affectedRows . " row";
                    $output .= (intval($affectedRows) > 1 ? "s " : " ");
                    $output .= "inserted. \n";
                }
            } else {
                $output = "There was an error loading the $csvfile file. Please try again.";
            }
            echo $output;
        } 
    }
    /**
     * Check if array has a duplicate
     * @param array $array Array to be checked
     * @return boolean
     */
    public function arrayHasDuplicate($array) {
        return count($array) !== count(array_unique($array));
    }
    /**
     * 
     * @param array $argv Command line arguments
     * @return array $config Database configuration array
     */
    public function insertScript($argv)
    {
        $commands = array();

        if(count($argv) === 9 ) {

            for ($i=1; $i < count($argv); $i++) { 
                $value = $argv[$i];
                if($value[0] === "-") {
                    $commands[$value] = $argv[$i + 1];
                }
            }

            if (array_key_exists("-u", $commands) 
                AND array_key_exists("-p", $commands) 
                AND array_key_exists("-h", $commands)) {

                foreach ($commands as $key => $value) {
                    if ($key === "-u") {
                        $username = $value;
                    } else if ($key === "-p") {
                        $password = $value;
                    } else if ($key === "-h") {
                        $hostname = $value;
                    } 
                }

                $config  = array(
                  "hostname" => $hostname,
                  "username" => $username,
                  "password" => $password
                );
                
            } else {
                return INVALID_COMMAND_FILE;
            }

            return $config;

        } else { 
            return INVALID_COMMAND_FILE;
        }
    }
    /**
     * Check if command is a valid dry run command
     * @param array $argv Command line arguments
     * @return boolean
     */
    public function isValidDryRunCmd($argv)
    {
        return (!$this->arrayHasDuplicate($argv) 
            AND count($argv) === 4 
            AND $argv[1] === "--dry_run" 
            AND $argv[2] === "--file");
    }
    /**
     * Check if command is a valid --file with dry run command
     * @param array $argv Command line arguments
     * @return boolean
     */
    public function isValidFileDryRunCmd($argv)
    {
        return (!$this->arrayHasDuplicate($argv) 
            AND count($argv) === 4 
            AND $argv[1] === "--file" 
            AND $argv[3] === "--dry_run");
    }
    /**
     * 
     * @param array $argv Command line arguments
     * @param string $csvfile CSV filename
     */
    public function dryRun($argv, $csvfile)
    {
        $users = parseCSV($csvfile);
        $output = "";

        if ($users) {
            $UsersObj = array();
            echo "\nThe following data will be inserted to the users table except for those with invalid email address format.\n\n";
            for ($i=0; $i < count($users) ; $i++) { 
                $name = $users[$i]["name"];
                $surname = $users[$i]["surname"];
                $email = $users[$i]["email"];
                // map users from csv file to Users object
                $UsersObj[$i] = new User($name, $surname, $email);       
                $UsersObj[$i]->displayValidUserFormat();
            }
        } else {
            $output = "There was an error loading the $csvfile file. Please try again.";
        }
        echo $output;
    }
    /**
     *
     * @param array $argv Command line arguments
     * @return array $config Database configuration array
     */
    public function createTableScript($argv)
    {
        $commands = array();
        
        if(count($argv) === 8 OR count($argv) === 10) {

            for ($i=2; $i < count($argv); $i++) { 
                $value = $argv[$i];
                if($value[0] === "-") {
                    $commands[$value] = $argv[$i + 1];
                }
            }

            if (array_key_exists("-u", $commands) 
                AND array_key_exists("-p", $commands) 
                AND array_key_exists("-h", $commands) 
                OR array_key_exists("--file", $commands)) {

                foreach ($commands as $key => $value) {
                    if ($key === "-u") {
                        $username = $value;
                    } else if ($key === "-p") {
                        $password = $value;
                    } else if ($key === "-h") {
                        $hostname = $value;
                    } else if ($key === "--file") {
                        $csvfile = $value;
                    } 
                }

                $config  = array(
                  "hostname" => $hostname,
                  "username" => $username,
                  "password" => $password,
                  "csvfile" => $csvfile
                );

            } else {
                return INVALID_COMMAND_CREATE_TABLE;
            }

            return $config;

        } else { 
            return INVALID_COMMAND_CREATE_TABLE;
        }
    }
    /**
     * Read command line arguments  
     * @param array $argv Array of arguments passed to script
     */
    public function readCommandLineArgs($argv)
    {
        switch ($argv[1]) {
            case "--file":
                $csvfile = $argv[2];
                if ($this->isValidFileDryRunCmd($argv)) {
                    $this->dryRun($argv, $csvfile);
                } else {
                    $config = $this->insertScript($argv);
                    if (is_array($config)) {
                        $this->insertFromCSV($csvfile, $config);
                    } else {
                        echo $config;
                    }             
                }
                break;
            case "--create_table":
                $config = $this->createTableScript($argv);
                if (is_array($config)) {
                    $csvfile = $config["csvfile"];
                    if (array_key_exists("csvfile", $config) AND !empty($csvfile)) {
                        echo $this->createTable($config);
                        $this->insertFromCSV($csvfile, $config);
                    } else {
                        echo $this->createTable($config);
                    }
                } else {
                    echo $config;
                }
                break;
            case "--dry_run":
                $csvfile = $argv[3];
                if ($this->isValidDryRunCmd($argv)) {
                    $this->dryRun($argv, $csvfile);
                } else {
                    echo INVALID_COMMAND;
                }
                break;
            case "-u":
                echo INVALID_MYSQL_COMMAND;
                break;
            case "-p":
                echo INVALID_MYSQL_COMMAND;
                break;
            case "-h":
                echo INVALID_MYSQL_COMMAND;
                break;
            case "--help":
                echo $this->displayHelp();
                break;
            default:
                echo INVALID_COMMAND;
                break;
        }
    }
}