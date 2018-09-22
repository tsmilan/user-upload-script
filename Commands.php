<?php

/**
 * This class contains command line script directives
 * 
 * @author Trisha Milan <tshmilan@gmail.com>
 */

define("CSV_FILE", "users.csv");

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
     * @return string Returns the success or error message in creating the users table
     */
    private function createTable()
    {
        $DatabaseQuery = new DatabaseQuery($config);
        echo $DatabaseQuery->createTable($tableFields); 
    }
    /**
     * Will insert users from the CSV file into the users table
     * @param string $csvfile The name of the CSV to be parsed
     */
    public function insertFromCSV($csvfile)
    {
        $users = parseCSV($csvfile);
        $affectedRows = 0;
        $output = "";
        $error = "";

        if ($users) {
            $UsersObj = array();
            for ($i=0; $i < count($users) ; $i++) { 
                $name = $users[$i]['name'];
                $surname = $users[$i]['surname'];
                $email = $users[$i]['email'];
                // map users from csv file to Users object
                $UsersObj[$i] = new User($name, $surname, $email); 
                // save to db
                $stmt_result = $UsersObj[$i]->save();

                if (gettype($stmt_result) === "string") {
                    echo $stmt_result . "\n";
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
            $output = "There was an error loading the file. Please try again.";
        }

        echo $output;
    }
    public function arrayHasDuplicate($array) {
       return count($array) !== count(array_unique($array));
    }

    private function isValidDryRunCmd($argv)
    {
      return (!$this->arrayHasDuplicate($argv) 
                AND count($argv) === 4 
                AND $argv[1] === "--dry_run" 
                AND $argv[2] === "--file");
    }

    private function isValidFileDryRunCmd($argv)
    {
      return (!$this->arrayHasDuplicate($argv) 
                AND count($argv) === 4 
                AND $argv[1] === "--file" 
                AND $argv[3] === "--dry_run");
    }

    public function dryRun($argv, $csvfile)
    {
        $users = parseCSV($csvfile);
        $output = "";

        if ($users) {
            $UsersObj = array();
            echo "\nThe following data will be inserted to the users table except for those with invalid email address format.\n\n";
            for ($i=0; $i < count($users) ; $i++) { 
                $name = $users[$i]['name'];
                $surname = $users[$i]['surname'];
                $email = $users[$i]['email'];
                // map users from csv file to Users object
                $UsersObj[$i] = new User($name, $surname, $email);       
                $UsersObj[$i]->displayValidUserFormat();
            }
            
        } else {
            $output = "There was an error loading the file. Please try again.";
        }

        echo $output;

    }
    /**
     * Read command line arguments  
     * @param array $argv Array of arguments passed to script
     */
    public function readCommandLineArgs($argv)
    {
        $mysql=Database::getInstance($config);
        switch ($argv[1]) {
            case "--file":
                $csvfile = $argv[2];
                if ($this->isValidFileDryRunCmd($argv)) {
                    $this->dryRun($argv, $csvfile);
                } else {
                    $this->insertFromCSV($csvfile);
                }
                break;
            case "--create_table":
                echo $this->createTable();
                break;
            case "--dry_run":
                $csvfile = $argv[3];
                if ($this->isValidDryRunCmd($argv)) {
                    $this->dryRun($argv, $csvfile);
                } else {
                    echo "\nInvalid command. Please use --dry_run --file [filename] or --file [filename] --dry_run or use --help for more information.";
                }
                break;
            case "-u":
                echo $mysql->getUsername();
                break;
            case "-p":
                echo $mysql->getPassword();
                break;
            case "-h":
                echo $mysql->getHostname();
                break;
            case "--help":
                echo $this->displayHelp();
                break;
            default:
                echo "Invalid command! Use --help for the list of available commands.";
                break;
        }
    }
}