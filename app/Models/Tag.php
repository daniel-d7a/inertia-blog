<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static function resolveTags(array $tagNames)
    {
        return collect($tagNames)
            ->map(fn($name) => strtolower(trim($name)))
            ->filter()
            ->unique()
            ->map(function ($name) {
                return static::firstOrCreate(
                    ['name' => $name],
                    ['name' => $name]
                );
            });
    }
}
