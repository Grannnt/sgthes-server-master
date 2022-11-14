<?php

namespace App\Observers;

use App\Models\StudentAnswerSheetResult;
use Illuminate\Support\Facades\Auth;

class StudentAnswerSheetResultObserver
{
    /**
     * Handle the StudentAnswerSheetResult "created" event.
     *
     * @param  \App\Models\StudentAnswerSheetResult  $studentAnswerSheetResult
     * @return void
     */
    public function creating(StudentAnswerSheetResult $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the StudentAnswerSheetResult "updated" event.
     *
     * @param  \App\Models\StudentAnswerSheetResult  $studentAnswerSheetResult
     * @return void
     */
    public function updating(StudentAnswerSheetResult $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the StudentAnswerSheetResult "deleted" event.
     *
     * @param  \App\Models\StudentAnswerSheetResult  $studentAnswerSheetResult
     * @return void
     */
    public function deleting(StudentAnswerSheetResult $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the StudentAnswerSheetResult "restored" event.
     *
     * @param  \App\Models\StudentAnswerSheetResult  $studentAnswerSheetResult
     * @return void
     */
    public function restored(StudentAnswerSheetResult $studentAnswerSheetResult)
    {
        //
    }

    /**
     * Handle the StudentAnswerSheetResult "force deleted" event.
     *
     * @param  \App\Models\StudentAnswerSheetResult  $studentAnswerSheetResult
     * @return void
     */
    public function forceDeleted(StudentAnswerSheetResult $studentAnswerSheetResult)
    {
        //
    }
}
