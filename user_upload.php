<?php 

/**
 *
 * @author Trisha Milan <tshmilan@gmail.com>
 */

require_once('functions.php');
require_once('Database.php');
require_once('DatabaseQuery.php');
require_once('Commands.php');
require_once('User.php');

$cmd = new Commands();
$cmd->readCommandLineArgs($argv);







