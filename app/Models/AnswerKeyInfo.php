<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerKeyInfo extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'answer_key_infos';
    public $timestamps = false;
}
