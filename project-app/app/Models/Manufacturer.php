<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $table = "manufacturer";
    protected $fillable = [
        'id',
        'name',
        'description',
        'created_at',
        'updated_at'
    ] ;
}
