<?php

namespace App\Helpers;
 
use App\Models\User;
use App\Models\UserMessage;

class Helper
{
     public static function getUnreadmsgcount($sender_id) {
        $counts = UserMessage::where('seen_status', 0)
                ->where('sender_id', $sender_id) 
                ->count();
        return $counts;
    }
}
