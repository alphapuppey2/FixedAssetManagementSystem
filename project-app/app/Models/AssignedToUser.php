<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedToUser extends Model
{
    use HasFactory;

    protected $table ='userAssetActivity';
    public $timestamps = false;

    public $fillable = [
        'id',
        'date_acquired',
        'date_returned',
        'used_by',
        'asset_id',
        'assigned_by',
    ];


    public function assetUserBy(){
        return $this->belongsTo(User::class , 'used_by');
    }
    public function assignedBy(){
        return $this->belongsTo(User::class , 'assigned_by');
    }
}
