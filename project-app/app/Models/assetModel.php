<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;;

class assetModel extends Model
{
    use HasFactory;

    protected $table = 'asset'; // Ensure the table name matches

    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'asst_img',  // Updated to match your new table structure
        'qr_img',
        'code',
        'purchase_date',
        'usage_lifespan',
        'salvage_value',
        'purchase_cost',
        'depreciation',
        'status',
        'custom_fields',
        'ctg_ID',
        'dept_ID',
        'manufacturer_key',
        'model_key',
        'loc_key',
        'last_used_by',
        'created_at',
        'updated_at',
        'isDeleted',
    ];

    /**
     * Override the delete method to mark as soft-deleted.
     */
    public function delete()
    {
        $this->isDeleted = 1;
        $this->save();
    }

    /**
     * Restore a soft-deleted record.
     */
    public function restore()
    {
        $this->isDeleted = 0;
        $this->save();
    }

    /**
     * Scope to exclude soft-deleted records.
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('isDeleted', 0);
    }

    /**
     * Scope to include only soft-deleted records.
     */
    public function scopeOnlyDeleted($query)
    {
        return $query->where('isDeleted', 1);
    }

    // Relationships with other models

    public function category()
    {
        return $this->belongsTo(Category::class, 'ctg_ID');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_key');
    }

    public function model()
    {
        return $this->belongsTo(ModelAsset::class, 'model_key');
    }

    public function location()
    {
        return $this->belongsTo(LocationModel::class, 'loc_key');
    }

    public function preventive()
    {
        return $this->hasMany(Preventive::class, 'asset_key');
    }

    /**
     * Get the asset image URL.
     */
    public function getImageUrlAttribute()
    {
        return $this->asst_img ? asset('storage/' . $this->asst_img) : asset('images/no-image.png');
    }
}
