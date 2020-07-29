<?php

namespace App\Exceptions;

use App\Models\Organization;

class InvalidRoleTransition extends Exception
{
    public static function forAdmin()
    {
        return new self("Admin role can't be assigned to regular employees");
    }

    public static function forPrimaryManager()
    {
        return new self("Primary manager for this organization can't change it's role");
    }
}
