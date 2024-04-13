<?php

namespace App\Enums\Coordinator;

enum Role: string
{
    case Administrator = 'Administrator';
    case Werknemer = 'Werknemer';

    public static function labels(): array
    {
        $labels = [];
        foreach(self::cases() as $case) {
            $labels[] = $case->name;
        }

        return $labels;
    }

    public static function values(): array
    {
        $values = [];
        foreach(self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }

    public static function toArray(): array
    {
        $roleArray = [];
        foreach (self::cases() as $case){
            $roleArray[$case->name] = $case->value;
        }

        return $roleArray;
    }
}
