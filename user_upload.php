<?php 

/**
 *
 * @author Trisha Milan <tshmilan@gmail.com>
 */

require_once('db_config.php');
require_once('functions.php');
require_once('Database.php');
require_once('DatabaseQuery.php');
require_once('Commands.php');

// Connect to database
Database::getInstance($config);

$cmd = new Commands();
$cmd->readCommandLineArgs($argv);




