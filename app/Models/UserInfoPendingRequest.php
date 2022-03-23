<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfoPendingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'created_by',
        'approved_by',
        'request_type_id',
        'user_info_id'
    ];

    /**
     * Get the request_type that owns the UserInfoPendingRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function request_type()
    {
        return $this->belongsTo(RequestType::class, 'request_type_id');
    }


    /**
     * Get the admin_creator that owns the UserInfoPendingRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin_creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
