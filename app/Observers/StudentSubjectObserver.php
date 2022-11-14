<?php

namespace App\Observers;

use App\Models\StudentSubject;
use Illuminate\Support\Facades\Auth;

class StudentSubjectObserver
{
    /**
     * Handle the StudentSubject "created" event.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return void
     */
    public function creating(StudentSubject $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the Student "updated" event.
     *
     * @param  \App\Models\StudentSubject  $model
     * @return void
     */
    public function updating(StudentSubject $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the Student "deleted" event.
     *
     * @param  \App\Models\StudentSubject  $model
     * @return void
     */
    public function deleting(StudentSubject $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the StudentSubject "restored" event.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return void
     */
    public function restored(StudentSubject $studentSubject)
    {
        //
    }

    /**
     * Handle the StudentSubject "force deleted" event.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return void
     */
    public function forceDeleted(StudentSubject $studentSubject)
    {
        //
    }
}