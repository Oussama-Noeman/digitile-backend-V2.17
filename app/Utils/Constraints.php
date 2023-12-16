<?php

namespace App\Utils;

class Constraints
{
    public static function allowedCompanies()
    {
        $allowedCompanies = auth()->user()->AllowedCompanies;
        if ($allowedCompanies) {
            return $allowedCompanies->pluck('name', 'id')->toArray();
        }
        return;
    }
}
