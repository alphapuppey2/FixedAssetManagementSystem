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
                            'qr',
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

    public function category() {
        return $this->belongsTo(category::class, 'ctg_ID');
    }

    public function manufacturer() {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_key');
    }

    public function model() {
        return $this->belongsTo(ModelAsset::class, 'model_key');
    }

    public function location() {
        return $this->belongsTo(locationModel::class, 'loc_key');
    }

    public function preventive()
    {
        return $this->hasMany(Preventive::class, 'asset_key');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/no-image.png');
    }
}
