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
        $tableFields = getFieldNamesFromCSV(CSV_FILE);

        if ($tableFields) {
            return $DatabaseQuery->createTable($tableFields); 
        } else {
            exit('Invalid file. Please try again.');
        }
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
                //echo "Parse CSV file and insert to users table";
                break;
            case "--create_table":
                echo $this->createTable();
                break;
            case "--dry_run":
                //echo "dry run";
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