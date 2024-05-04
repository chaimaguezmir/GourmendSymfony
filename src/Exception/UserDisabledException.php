<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserDisabledException extends AuthenticationException
{
    public function getMessageKey(): string
    {
        return 'L\'utilisateur est désactivé.';
    }
}