<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolYear extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'school_years';

    public function students()
    {
        return $this->hasMany(Student::class, 'school_year_id');
    }

    public function scopeCreatedBy($query)
    {
        return $query->leftJoin('users', 'school_years.created_by', 'users.id');
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}