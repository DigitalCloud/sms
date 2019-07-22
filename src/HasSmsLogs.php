<?php

namespace Digitalcloud\SMS;


trait HasSmsLogs
{
    public function smsLogs()
    {
        return $this->morphMany(SmsLog::class, 'notifiable');
    }
}
