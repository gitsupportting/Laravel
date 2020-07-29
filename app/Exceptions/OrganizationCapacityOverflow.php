<?php

namespace App\Exceptions;

use App\Models\Organization;

class OrganizationCapacityOverflow extends Exception
{
    public static function onCreate(Organization $organization)
    {
        return new self("You have reached your license capacity of {$organization->license_capacity} employees,
            please contact support to add more.");
    }

    public static function onRestore(Organization $organization)
    {
        return new self("Selected employee(s) can not be reinstated as it would exceed your license
        capacity of {$organization->license_capacity} employees, please contact support to add more user licenses.");
    }
}
