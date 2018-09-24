<?php 

/**
 *
 * @author Trisha Milan <tshmilan@gmail.com>
 */

require_once("app/constants.php");
require_once("app/functions.php");
require_once("app/db/Database.php");
require_once("app/db/DatabaseQuery.php");
require_once("app/Commands.php");
require_once("app/model/User.php");

$cmd = new Commands();
$cmd->readCommandLineArgs($argv);







