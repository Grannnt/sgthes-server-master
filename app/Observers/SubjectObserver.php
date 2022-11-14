<?php

namespace App\Observers;

use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class SubjectObserver
{
    /**
     * Handle the Subject "created" event.
     *
     * @param  \App\Models\Subject  $model
     * @return void
     */
    public function creating(Subject $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the Subject "updated" event.
     *
     * @param  \App\Models\Subject  $model
     * @return void
     */
    public function updating(Subject $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the Subject "deleted" event.
     *
     * @param  \App\Models\Subject  $model
     * @return void
     */
    public function deleting(Subject $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the Subject "restored" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function restored(Subject $subject)
    {
        //
    }

    /**
     * Handle the Subject "force deleted" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function forceDeleted(Subject $subject)
    {
        //
    }
}
