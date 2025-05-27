<?php

namespace App\Enums;

use PhpParser\Builder\EnumCase;

enum VoteType: string
{
    case UP = '1';
    case DOWN = '-1';

    public static function toValues()
    {
        return array_map(fn(VoteType $case) => $case->value, static::cases());
    }

}
