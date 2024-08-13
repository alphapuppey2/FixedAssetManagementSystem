<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assetModel extends Model
{
    use HasFactory;
    protected $table ='asset';

    protected $fillable = ['id' ,
                            'name',
                            'image',
                            'ctg_ID',
                            'manufacturer_key',
                            'model_key',
                            'dept_ID',
                            'status'
                          ];
        protected function data(): Attribute{
            return Attribute::make(
                get: fn($value) => json_decode($value, true),
                set: fn($value) => json_encode($value),
            );
        }
}
