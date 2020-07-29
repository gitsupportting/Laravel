<?php

namespace Tests\Feature;

use App\Models\User;

trait WorksWithUsers
{
    public function manager(): User
    {
        return User::query()->managers()->first();
    }

    public function user(): User
    {
        return User::query()->employees()->first();
    }
}
