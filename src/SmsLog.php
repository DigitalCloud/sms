<?php

namespace Digitalcloud\SMS;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = ['notifiable_type', 'notifiable_id', 'response', 'status', 'response_code', 'notification', 'mobile', 'provider', 'message'];

    protected $casts = ["success" => "boolean"];

    public function notifiable()
    {
        return $this->morphTo();
    }
}
