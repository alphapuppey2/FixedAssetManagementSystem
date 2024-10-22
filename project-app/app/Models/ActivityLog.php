<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['activity', 'description', 'userType', 'user_id', 'asset_id', 'request_id'];
}

