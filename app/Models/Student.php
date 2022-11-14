<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'students';

    public function school_year()
    {
        return $this->hasOne(SchoolYear::class, 'id');
    }

    public function section()
    {
        return $this->hasOne(Section::class, 'id');
    }

    public function subject()
    {
        return $this->hasOne(Subject::class, 'id');
    }

    public function student_answer_sheet_info()
    {
        return $this->hasMany(StudentAnswerSheetInfo::class, 'student_id');
    }

    public function scopeSchoolYear($query)
    {
        return $query->leftJoin('school_years', 'students.school_year_id', 'school_years.id');
    }

    public function scopeSection($query)
    {
        return $query->leftJoin('sections', 'students.section_id', 'sections.id');
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}