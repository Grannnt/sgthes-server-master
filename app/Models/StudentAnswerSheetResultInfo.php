<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswerSheetResultInfo extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function student_answer_sheet_result()
    {
        return $this->belongsTo(StudentAnswerSheetResult::class, 'student_answer_sheet_result_id');
    }
}