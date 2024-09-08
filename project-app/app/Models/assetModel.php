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

    public $timestamps = true;

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
                            'created_at',
                            'updated_at',
                          ];
                          
    // Relationship to the Category model
    public function category()
    {
        return $this->belongsTo(Category::class, 'ctg_ID');
    }
}
