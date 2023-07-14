<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

spl_autoload_register(function ($class) {
    if (file_exists('controllers/' . $class . '.php')) {
        require 'controllers/' . $class . '.php';
    } else if (file_exists('models/' . $class . '.php')) {
        require 'models/' . $class . '.php';
    } else if (file_exists('core/' . $class . '.php')) {
        require 'core/' . $class . '.php';
    }
});

final class UserTest extends TestCase
{
    public function testTypeIsValidFromModel(): void
    {
        $this->assertEquals(
            User::getTableName(),
            'users'
        );
    }

    public function testIfUserCanLoginWithInvalidToken(): void
    {
        $auth = new Auth();
        $result = $auth->verifyToken('invalidtoken.invalidtoken.invalidtoken');
        $this->assertFalse($result['success']);
    }
}