<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswerSheetInfo extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'student_answer_sheet_infos';
    public $timestamps = false;

    public function student_answer_sheet()
    {
        return $this->belongsTo(StudentAnswerSheet::class, 'student_answer_sheet_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function student_answer_sheet_result()
    {
        return $this->hasOne(StudentAnswerSheetResult::class);
    }

    public function scopeStudentJoin($query)
    {
        return $query->leftJoin('students', 'student_answer_sheet_infos.student_id', 'students.id');
    }

    public function scopeStudentAnswerSheet($query)
    {
        return $query->leftJoin('student_answer_sheets', 'student_answer_sheet_infos.student_answer_sheet_id', 'student_answer_sheets.id');
    }
}
