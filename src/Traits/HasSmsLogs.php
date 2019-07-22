<?php
/**
 * Created by PhpStorm.
 * User: devmsh
 * Date: 6/11/18
 * Time: 3:56 PM.
 */

namespace Digitalcloud\SMS\Providers;

use Digitalcloud\SMS\Models\SmsLog;

trait HasSmsLogs
{
    public function smsLogs()
    {
        return $this->morphMany(SmsLog::class, 'notifiable');
    }
}
