<?php

namespace App\Observers;

use App\Models\AnswerKey;
use Illuminate\Support\Facades\Auth;

class AnswerKeyObserver
{
    /**
     * Handle the AnswerKey "created" event.
     *
     * @param  \App\Models\AnswerKey  $answerKey
     * @return void
     */
    public function creating(AnswerKey $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the AnswerKey "updated" event.
     *
     * @param  \App\Models\AnswerKey  $answerKey
     * @return void
     */
    public function updating(AnswerKey $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the AnswerKey "deleted" event.
     *
     * @param  \App\Models\AnswerKey  $answerKey
     * @return void
     */
    public function deleting(AnswerKey $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the AnswerKey "restored" event.
     *
     * @param  \App\Models\AnswerKey  $answerKey
     * @return void
     */
    public function restored(AnswerKey $answerKey)
    {
        //
    }

    /**
     * Handle the AnswerKey "force deleted" event.
     *
     * @param  \App\Models\AnswerKey  $answerKey
     * @return void
     */
    public function forceDeleted(AnswerKey $answerKey)
    {
        //
    }
}
