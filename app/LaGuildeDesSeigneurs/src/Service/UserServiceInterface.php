<?php

namespace App\Service;

use App\Entity\User;

interface UserServiceInterface
{
    public function getToken(User $user): string;
    public function findOneByEmail(string $token);
    public function parseToken(string $token);
}
