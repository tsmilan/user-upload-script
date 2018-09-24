<?php

/**
 * CommandsTest class
 * @author Trisha Milan <tshmilan@gmail.com>
 */

require_once("app/Commands.php");

class CommandsTest extends \PHPUnit\Framework\TestCase
{
    public function testArrayHasDuplicateFunction()
    {
        $cmd = new Commands();
        $array = ["-h", "-u", "--file", "-u"];

        $this->assertTrue($cmd->arrayHasDuplicate($array));
    }

    public function testInsertScriptReturningProperConfigFormat()
    {
        $cmd = new Commands();
        $array = array(
            "0" => "user_upload.php",
            "1" => "--file",
            "2" => "users.csv",
            "3" => "-u",
            "4" => "root",
            "5" => "-p",
            "6" => "rootp",
            "7" => "-h",
            "8" => "localhost"
        );

        $config  = array(
            "hostname" => "localhost",
            "username" => "root",
            "password" => "rootp"
        );

        $result = $cmd->insertScript($array);

        $this->assertEquals($config, $result);

    }

    public function testCreateTableScriptReturningProperConfigFormat()
    {
        $cmd = new Commands();
        $array = array(
            "0" => "user_upload.php",
            "1" => "create_table",
            "2" => "--file",
            "3" => "users.csv",
            "4" => "-u",
            "5" => "root",
            "6" => "-p",
            "7" => "rootp",
            "8" => "-h",
            "9" => "localhost"
        );

        $config  = array(
            "hostname" => "localhost",
            "username" => "root",
            "password" => "rootp",
            "csvfile" => "users.csv"
        );

        $result = $cmd->createTableScript($array);

        $this->assertEquals($config, $result);
    }

    public function testIsValidDryRunCommand()
    {
        $cmd = new Commands();
        $array = array(
            "0" => "user_upload.php",
            "1" => "--dry_run",
            "2" => "--file",
            "3" => "users.csv"
        );

        $this->assertTrue($cmd->isValidDryRunCmd($array));
    }

    public function testIsValidFileDryRunCmd()
    {
        $cmd = new Commands();
        $array = array(
            "0" => "user_upload.php",
            "1" => "--file",
            "2" => "users.csv",
            "3" => "--dry_run"
        );

        $this->assertTrue($cmd->isValidFileDryRunCmd($array));
    }
}