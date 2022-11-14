<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Support extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'supports';

    public function support_conversation()
    {
        return $this->hasMany(SupportConversation::class, 'support_id');
    }

    public function scopeUser($query)
    {
        return $query->leftJoin('users', 'supports.user_id', 'users.id');
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
