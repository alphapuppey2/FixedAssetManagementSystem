<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\ModelFactory;



class ModelAsset extends Model
{
    use HasFactory;
    protected $table = "model";
    protected $fillable = [
        'name',
        'description',
    ];
}
