<?php 

/**
 *
 * @author Trisha Milan <tshmilan@gmail.com>
 */

require_once('db_config.php');
require_once('Database.php');
require_once('DatabaseQuery.php');
require_once('functions.php');

$DatabaseQuery = new DatabaseQuery($config);

$tableFields = getFieldNamesFromCSV($argv[2]);

if ($tableFields) {
    echo $DatabaseQuery->createTable($tableFields); 
} else {
    exit('Invalid file. Please try again.');
}




