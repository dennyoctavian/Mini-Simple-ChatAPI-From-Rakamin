<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;
    protected $table = 'chat_rooms';

    protected $fillable = [
        'user_one',
        'user_two'
    ];

    public function listChat() {
        return $this->hasMany(Chat::class);
    }
}
