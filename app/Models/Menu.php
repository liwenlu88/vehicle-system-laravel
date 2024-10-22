<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static select(string $string, string $string1, string $string2)
 */
class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'url',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
