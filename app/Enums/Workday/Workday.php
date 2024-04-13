<?php

namespace App\Enums\Workday;

enum Workday: string
{
    case Maandag = 'Maandag';
    case Dinsdag = 'Dinsdag';
    case Woensdag = 'Woensdag';
    case Donderdag = 'Donderdag';
    case Vrijdag = 'Vrijdag';
    case Zaterdag = 'Zaterdag';
    case Zondag = 'Zondag';

    public static function labels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[] = $case->name;
        }

        return $labels;
    }

    public static function toArray(): array
    {
        $casesToArray = [];
        foreach (self::cases() as $case) {
            $casesToArray[$case->name] = $case->value;
        };

        return $casesToArray;
    }
}
