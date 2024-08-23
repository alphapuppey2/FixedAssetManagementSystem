<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class assetModel extends Model
{
    use HasFactory;
    protected $table ='asset';

    protected $fillable = [ 'id' ,
                            'code',
                            'name',
                            'image',
                            'cost',
                            'ctg_ID',
                            'dept_ID',
                            'manufacturer_key',
                            'model_key',
                            'loc_key',
                            'custom_fields',
                          ];
}
