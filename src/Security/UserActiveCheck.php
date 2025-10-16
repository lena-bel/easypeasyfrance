<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UserActiveCheck implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof \App\Entity\User) {
            return;
        }

        
        if (!$user->isActive()) {
            throw new CustomUserMessageAuthenticationException(
                'Your account is not active. Please verify your email before logging in.'
            );
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        
    }
}
