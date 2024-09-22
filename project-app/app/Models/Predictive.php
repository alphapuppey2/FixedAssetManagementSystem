<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\assetModel;

class Predictive extends Model
{
    use HasFactory;

    protected $table = 'predictive'; // Specify the table name if it's not plural

    public function asset()
    {
        return $this->belongsTo(assetModel::class, 'asset_key');
    }
}
