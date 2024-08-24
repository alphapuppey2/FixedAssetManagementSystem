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
        'description',
        'type',
        'cost',
        'requested_at',
        'authorized_at',
        'completion',
        'asset_key',
        'authorized_by',
        'requestor'
    ];

    // Handle date attributes
    protected $dates = [
        'requested_at',
        'authorized_at',
        'completion'
    ];

    protected $primaryKey = 'id'; // Default is 'id', but specify if different
    public $incrementing = true; // Set to false if the primary key is not auto-incrementing
    protected $keyType = 'int'; // Default is 'int', change if necessary


}
