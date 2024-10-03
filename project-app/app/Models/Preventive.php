<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\assetModel;

class Preventive extends Model
{
    use HasFactory;

    protected $table = 'preventive'; // Specify the table name if it's not plural

    protected $fillable = ['asset_key', 'cost', 'frequency', 'ends', 'occurrences', 'status']; // Include the missing fields

    public function asset()
    {
        return $this->belongsTo(assetModel::class, 'asset_key');
    }
}
