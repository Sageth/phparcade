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
        if (isset($_SESSION['user']))
        {
            @session_destroy();
            unset($_SESSION);
        }

    }

    public function testGetGravatarHash(): void
    {
        $email = 'test@example.com';
        $this->assertEquals('55502f40dc8b7c769880b10874abc9d0', md5(strtolower(trim($email))));
    }
    public function testStartSession(): void
    {
        /* Suppress errors with session_start */
        @session_start();
        $username = 'testuser';
        $_SESSION['user'] = array( 'name' => $username );
        $this->assertEquals($username, $_SESSION['user']['name']);
    }
    public function testUserAdd(): void{
        $connection_string = "mysql:host=localhost;port=3306;dbname=phparcade";
        $db = new PDO($connection_string, 'root', '');

        $count = $db->exec("INSERT INTO `members` 
                              (`id`,`username`,`password`,`email`,`active`,`regtime`, `admin`,`ip`) 
                             VALUES 
                              ('7', 'travis1', '6a204bd89f3c8348afd5c77c717a097a', 'travis1@example.com', 'yes', 1524003311, 'No', '192.168.1.1');";

        $rowcount = $count->rowCount();
        $this->assertEquals($rowcount, 1);
    }
    public function testUserPasswordHash(): void
    {
        $hash = password_hash('password', PASSWORD_DEFAULT);
        $this->assertEquals($hash, crypt('password', $hash));
    }
    public function testUserPasswordGenerate(): void
    {
        $password = 'password';
        $this->assertEquals('70617373776f7264', bin2hex($password));
    }
    public function testUserPasswordMD5(): void
    {
        $password = 'password';
        $this->assertEquals('5f4dcc3b5aa765d61d8327deb882cf99', md5($password));
    }
}