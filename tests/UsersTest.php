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
        $connection_string = "mysql:host=localhost;dbname=phparcade";
        $db = new PDO($connection_string, 'root', '');

        $useradd = $db->exec("
          INSERT INTO `phparcade`.`members`
          SET
            `id` = 1,
            `username` = 'admin',
            `password` = '21232f297a57a5a743894a0e4a801fc3',
            `email` = 'admin@example.com',
            `active` = 'Yes',
            `regtime` = 1219016824,
            `totalgames` = 0,
            `twitter_id` = '',
            `github_id` = NULL,
            `facebook_id` = NULL,
            `admin` = 'Yes',
            `favorites` = '',
            `ip` = '',
            `birth_date` = '',
            `last_login` = NOW();";
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