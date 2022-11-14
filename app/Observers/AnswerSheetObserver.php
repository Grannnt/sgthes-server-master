<?php

namespace App\Observers;

use App\Models\AnswerSheet;
use Illuminate\Support\Facades\Auth;

class AnswerSheetObserver
{
    /**
     * Handle the AnswerSheet "created" event.
     *
     * @param  \App\Models\AnswerSheet  $model
     * @return void
     */
    public function creating(AnswerSheet $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the AnswerSheet "updated" event.
     *
     * @param  \App\Models\AnswerSheet  $model
     * @return void
     */
    public function updating(AnswerSheet $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the AnswerSheet "deleted" event.
     *
     * @param  \App\Models\AnswerSheet  $model
     * @return void
     */
    public function deleting(AnswerSheet $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the AnswerSheet "restored" event.
     *
     * @param  \App\Models\AnswerSheet  $answerSheet
     * @return void
     */
    public function restored(AnswerSheet $answerSheet)
    {
        //
    }

    /**
     * Handle the AnswerSheet "force deleted" event.
     *
     * @param  \App\Models\AnswerSheet  $answerSheet
     * @return void
     */
    public function forceDeleted(AnswerSheet $answerSheet)
    {
        //
    }
}