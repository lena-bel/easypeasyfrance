<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    //no errors 
    public function testHydration(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('hashed-password');
        $user->setRoles(['ROLE_USER']);

        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('hashed-password', $user->getPassword());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertIsArray($user->getRoles());
    }
}
