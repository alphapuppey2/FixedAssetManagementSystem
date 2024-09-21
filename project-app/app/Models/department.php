<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    use HasFactory;

    protected $table = 'department';
    protected $fillable = ['id','name','assetSequence'];

    
    /**
     * Get the users for the department.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'dept_id');
    }

}
