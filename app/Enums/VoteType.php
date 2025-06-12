<?php

namespace App\Enums;

enum VoteType: string
{
    case UP = '1';
    case DOWN = '-1';

    public static function toValues()
    {
        return array_column(self::cases(), 'value');
    }

}
