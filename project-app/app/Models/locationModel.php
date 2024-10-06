<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class locationModel extends Model
{
    use HasFactory;

    protected $table = 'location';

    protected $fillable = [
        'id',
        'name',
        'description',
        'dept_ID',
        'created_at',
        'updated_at'
    ];

}
