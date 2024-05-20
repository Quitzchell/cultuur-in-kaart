<?php

namespace App\Enums\Workday;

use Illuminate\Support\Collection;

enum Workday: string
{







    case Monday = 'Maandag';
    case Tuesday = 'Dinsdag';
    case Wednesday = 'Woensdag';
    case Thursday = 'Donderdag';
    case Friday = 'Vrijdag';
    case Saturday = 'Zaterdag';
    case Sunday = 'Zondag';

    public static function labels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[] = $case->name;
        }

        return $labels;
    }

    public static function values(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }

    public static function toArray(): array
    {
        $casesToArray = [];
        foreach (self::cases() as $case) {
            $casesToArray[$case->name] = $case->value;
        }

        return $casesToArray;
    }

    public static function labelsToCollection(): Collection
    {
        $collection = collect();
        foreach (self::cases() as $case) {
            $collection->push($case->name);
        }

        return $collection;
    }
}
