<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportConversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'support_conversations';

    public function user_from()
    {
        return $this->belongsTo(User::class, 'user_from');
    }

    public function user_to()
    {
        return $this->belongsTo(User::class, 'user_to');
    }
}
