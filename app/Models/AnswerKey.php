<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswerKey extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'answer_keys';

    public function answer_key_info()
    {
        return $this->hasMany(AnswerKeyInfo::class, 'answer_key_id');
    }

    public function student_answer_sheet()
    {
        return $this->hasMany(StudentAnswerKey::class, 'answer_key_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeSchoolYear($query)
    {
        return $query->leftJoin('school_years', 'answer_keys.school_year_id', 'school_years.id');
    }

    public function scopeSection($query)
    {
        return $query->leftJoin('sections', 'answer_keys.section_id', 'sections.id');
    }

    public function scopeSubject($query)
    {
        return $query->leftJoin('subjects', 'answer_keys.subject_id', 'subjects.id');
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
