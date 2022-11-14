<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAnswerSheetResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'student_answer_sheet_results';
    public $timestamps = false;

    public function student_answer_sheet_info()
    {
        return $this->belongsTo(StudentAnswerSheetInfo::class, 'student_answer_sheet_info_id');
    }

    public function student_answer_sheet_result_info()
    {
        return $this->hasMany(StudentAnswerSheetResultInfo::class, 'student_answer_sheet_result_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
