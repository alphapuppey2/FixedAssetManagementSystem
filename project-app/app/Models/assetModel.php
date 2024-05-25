<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assetModel extends Model
{
    use HasFactory;
    protected $table ='asset';

    protected $fillable = ['id' ,
                            'name',
                            'ctg_ID',
                            'dept_ID',
                            'status'];
}
