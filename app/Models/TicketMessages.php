<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessages extends Model
{
    use HasFactory;

    public function sendUser()
    {
        return $this->belongsTo(User::class, 'sender_user_id', 'id');
    }

    public function replyUser()
    {
        return $this->belongsTo(User::class, 'reply_admin_user_id', 'id');
    }

}
