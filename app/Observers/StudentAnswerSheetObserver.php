<?php

namespace App\Observers;

use App\Models\StudentAnswerSheet;
use Illuminate\Support\Facades\Auth;

class StudentAnswerSheetObserver
{
    /**
     * Handle the StudentAnswerSheet "created" event.
     *
     * @param  \App\Models\StudentAnswerSheet  $studentAnswerSheet
     * @return void
     */
    public function creating(StudentAnswerSheet $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the StudentAnswerSheet "updated" event.
     *
     * @param  \App\Models\StudentAnswerSheet  $studentAnswerSheet
     * @return void
     */
    public function updating(StudentAnswerSheet $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the StudentAnswerSheet "deleted" event.
     *
     * @param  \App\Models\StudentAnswerSheet  $studentAnswerSheet
     * @return void
     */
    public function deleting(StudentAnswerSheet $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the StudentAnswerSheet "restored" event.
     *
     * @param  \App\Models\StudentAnswerSheet  $studentAnswerSheet
     * @return void
     */
    public function restored(StudentAnswerSheet $studentAnswerSheet)
    {
        //
    }

    /**
     * Handle the StudentAnswerSheet "force deleted" event.
     *
     * @param  \App\Models\StudentAnswerSheet  $studentAnswerSheet
     * @return void
     */
    public function forceDeleted(StudentAnswerSheet $studentAnswerSheet)
    {
        //
    }
}
