<?php

namespace App\Observers;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     *
     * @param  \App\Models\Student  $model
     * @return void
     */
    public function creating(Student $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the Student "updated" event.
     *
     * @param  \App\Models\Student  $model
     * @return void
     */
    public function updating(Student $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the Student "deleted" event.
     *
     * @param  \App\Models\Student  $model
     * @return void
     */
    public function deleting(Student $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the Student "restored" event.
     *
     * @param  \App\Models\Student  $student
     * @return void
     */
    public function restored(Student $student)
    {
        //
    }

    /**
     * Handle the Student "force deleted" event.
     *
     * @param  \App\Models\Student  $student
     * @return void
     */
    public function forceDeleted(Student $student)
    {
        //
    }
}
