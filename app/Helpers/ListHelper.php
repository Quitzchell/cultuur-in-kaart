<?php

namespace App\Helpers;

class ListHelper
{
    public static function sortFilamentList(string $string): string
    {
        $explode = explode(', ', $string);
        sort($explode);

        return implode(', ', $explode);
    }
}
