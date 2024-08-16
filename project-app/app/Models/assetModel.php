<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assetModel extends Model
{
    use HasFactory;
    protected $table ='asset';

    protected $fillable = [ 'id' ,
                            'name',
                            'image',
                            'cost',
                            'depreciation',
                            'salvageVal',
                            'status',
                            'ctg_ID',
                            'dept_ID',
                            'manufacturer_key',
                            'model_key',
                            'loc_key',
                            'custom_fields',
                          ];
}
