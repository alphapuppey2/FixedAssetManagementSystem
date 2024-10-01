<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    // Specify the table name if it doesn't follow Laravel's convention
    protected $table = 'maintenance';

    // Specify which attributes are mass-assignable
    protected $fillable = [
        'id',
        'description',
        'type',
        'cost',
        'requested_at',
        'authorized_at',
        'start_date',
        'completion_date',
        'reason',
        'status',
        'asset_key',
        'authorized_by',
        'requestor',
        'completed'
    ];

    // Cast timestamps to Carbon instances
    protected $casts = [
        'requested_at' => 'datetime',
        'authorized_at' => 'datetime',
        'start_date' => 'datetime',
        'completion_date' => 'datetime',
    ];

    protected $primaryKey = 'id'; // Default is 'id', but specify if different
    public $incrementing = true; // Set to false if the primary key is not auto-incrementing
    protected $keyType = 'int'; // Default is 'int', change if necessary

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor', 'id');
    }

    // Maintenance.php

    public function asset()
    {
        return $this->belongsTo(assetModel::class, 'asset_key');
    }

    public function manufacturer()
    {
        return $this->hasOneThrough(Manufacturer::class, assetModel::class, 'id', 'id', 'asset_key', 'manufacturer_key');
    }

    public function category()
    {
        return $this->hasOneThrough(Category::class, assetModel::class, 'id', 'id', 'asset_key', 'ctg_ID');
    }

    public function location()
    {
        return $this->hasOneThrough(locationModel::class, assetModel::class, 'id', 'id', 'asset_key', 'loc_key');
    }

    public function model()
    {
        return $this->hasOneThrough(ModelAsset::class, assetModel::class, 'id', 'id', 'asset_key', 'model_key');
    }

}
