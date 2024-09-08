<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;

    protected $table = "category";

    protected $fillable = [
        "name",
        'department'
    ];

    public function assets()
    {
        return $this->hasMany(assetModel::class, 'ctg_ID');
    }
}
