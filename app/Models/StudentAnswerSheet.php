<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAnswerSheet extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'student_answer_sheets';

    public function student_answer_sheet_info()
    {
        return $this->hasMany(StudentAnswerSheetInfo::class, 'student_answer_sheet_id');
    }

    public function answer_key()
    {
        return $this->belongsTo(AnswerKey::class, 'answer_key_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeAnswerKey($query)
    {
        return $query->leftJoin('answer_keys', 'student_answer_sheets.answer_key_id', 'answer_keys.id');
    }

    public function scopeSchoolYear($query)
    {
        return $query->leftJoin('school_years', 'student_answer_sheets.school_year_id', 'school_years.id');
    }

    public function scopeSection($query)
    {
        return $query->leftJoin('sections', 'student_answer_sheets.section_id', 'sections.id');
    }

    public function scopeSubject($query)
    {
        return $query->leftJoin('subjects', 'student_answer_sheets.subject_id', 'subjects.id');
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
