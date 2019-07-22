<?php

namespace Digitalcloud\SMS\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = ['notifiable_type', 'notifiable_id', 'payload', 'status', 'notification', 'mobile', 'provider', 'message'];

    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    public function notifiable()
    {
        return $this->morphTo();
    }
}
