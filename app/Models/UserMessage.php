<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMessage extends Model
{
    protected $fillable = ['message_id','sender_id','receiver_id','message'];
    
    use SoftDeletes;

    public function message() {
        return $this->belongsTo(Message::class);
    }

    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Models\User', 'receiver_id');
    }
}
