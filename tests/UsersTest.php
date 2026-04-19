<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPArcade\Users;

class UsersTest extends TestCase
{
    public function testPasswordGenerateReturnsTenCharHexString(): void
    {
        $password = Users::passwordGenerate();
        $this->assertMatchesRegularExpression('/^[0-9a-f]{10}$/', $password);
    }

    public function testPasswordGenerateIsRandom(): void
    {
        $this->assertNotEquals(Users::passwordGenerate(), Users::passwordGenerate());
    }

    public function testUserPasswordHashReturnsVerifiableHash(): void
    {
        $hash = Users::userPasswordHash('testpass');
        $this->assertTrue(password_verify('testpass', $hash));
    }

    public function testUserPasswordHashRejectsMismatch(): void
    {
        $hash = Users::userPasswordHash('testpass');
        $this->assertFalse(password_verify('wrongpass', $hash));
    }

    public function testUserPasswordMD5ReturnsCorrectHash(): void
    {
        $this->assertSame(md5('admin'), Users::userPasswordMD5('admin'));
    }
}
