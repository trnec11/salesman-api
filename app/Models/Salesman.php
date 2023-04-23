<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'titles_before',
        'titles_after',
        'prosight_id',
        'email',
        'gender',
        'marital_status',
    ];
}
