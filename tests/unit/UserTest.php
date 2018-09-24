<?php

/**
 * UserTest class
 * @author Trisha Milan <tshmilan@gmail.com>
 */

require_once("app/model/User.php");

class UserTest extends \PHPUnit\Framework\TestCase
{
    public function testValidEmailAddressFormat()
    {
        $user = new User("test", "test", "test");
        $this->assertFalse($user->isValidEmail("aaa@gmail-com"));
        $this->assertFalse($user->isValidEmail("aaaa@gmail@com@com"));
    }

    public function testTrimWhiteSpace()
    {
        $user = new User("  John  ", " smith     ", " jsmith@gmail.com  ");
        $user->trimWhiteSpace();

        $this->assertEquals("John", $user->name);
        $this->assertEquals("smith", $user->surname);
        $this->assertEquals("jsmith@gmail.com", $user->email);
    }

    public function testCapitalizeFirstCharacterOfEachWordInName()
    {
        $user = new User("john michael", "smith", "jsmith@gmail.com");
        $user->fixUserCaps();

        $this->assertEquals("John Michael", $user->name);
    }

    public function testCapitalizeFirstLetterOfSurname()
    {
        $user = new User("john", "smith", "jsmith@gmail.com");
        $user->fixUserCaps();

        $this->assertEquals("Smith", $user->surname);
    }
}