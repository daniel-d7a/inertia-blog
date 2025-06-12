<?php

namespace App\Enums;



enum VoteableType: string
{
  case POST = "Post";
  case COMMENT = "Comment";

  public static function toValues()
  {
    return array_column(self::cases(), 'value');
  }
}