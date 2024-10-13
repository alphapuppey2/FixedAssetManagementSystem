<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'message', 'status', 'is_deleted', 'authorized_by'
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include non-deleted notifications.
     */
    public function scopeNonDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    /**
     * Mark the notification as deleted.
     */
    public function markAsDeleted()
    {
        $this->update(['is_deleted' => 1]);
    }

    public function authorizedUser()
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }


}
