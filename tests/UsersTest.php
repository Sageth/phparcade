<?php

use PHPUnit\Framework\TestCase;

final class UsersTest extends TestCase
{
    public function setUp()
    {
        $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . "/../";
        include_once $_SERVER['DOCUMENT_ROOT'] . 'includes/classes/Core.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . 'includes/classes/Users.php';
    }

    public function tearDown()
    {
        unset($_SERVER['DOCUMENT_ROOT']);
    }

    public function testUserPasswordHash(): void
    {
        $hash = password_hash('password', PASSWORD_DEFAULT);
        $this->assertEquals($hash, crypt('password', $hash));
    }
    public function testUserPasswordGenerate(): void
    {
        $hexPassword = 'password';
        $this->assertEquals('70617373776f7264', bin2hex($hexPassword));
    }
}