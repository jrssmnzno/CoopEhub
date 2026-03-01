<?php

namespace App\Enums;

enum LoanType: string
{
    case CASH = 'cash';
    case SWINE = 'swine';
    case GOAT = 'goat';
    case FERTILIZERS = 'fertilizers';

    public function label(): string
    {
        return match($this) {
            self::CASH => 'Cash',
            self::SWINE => 'Swine',
            self::GOAT => 'Goat',
            self::FERTILIZERS => 'Fertilizers',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::CASH => 'Cash loan for general purposes',
            self::SWINE => 'Loan for swine raising and livestock',
            self::GOAT => 'Loan for goat raising and livestock',
            self::FERTILIZERS => 'Loan for agricultural fertilizers and supplies',
        };
    }

    public static function options(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }
}
