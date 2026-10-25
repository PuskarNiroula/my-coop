<?php

namespace AccountTypes\Enum;
enum InterestFrequencyEnum:string
{
    case MATURITY = 'MATURITY';
    case ANNUAL = 'ANNUAL';
    case QUARTERLY = 'QUARTERLY';
    case SEMI_ANNUAL = 'SEMI_ANNUAL';
    case MONTHLY = 'MONTHLY';

    public static function getAsArray():array{
        return [
            self::MONTHLY->name=>self::MONTHLY->value,
            self::QUARTERLY->name=>self::QUARTERLY->value,
            self::SEMI_ANNUAL->name=>self::SEMI_ANNUAL->value,
            self::ANNUAL->name=>self::ANNUAL->value,
            self::MATURITY->name=>self::MATURITY->value,
        ];
    }

    public static function getAsOneDArray():array{

        return [
            self::MONTHLY->value,
            self::QUARTERLY->value,
            self::SEMI_ANNUAL->value,
            self::ANNUAL->value,
            self::MATURITY->value,
            ];

    }
}
