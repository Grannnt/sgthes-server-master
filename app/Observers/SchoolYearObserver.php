<?php

namespace App\Observers;

use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;

class SchoolYearObserver
{
    /**
     * Handle the SchoolYear "created" event.
     *
     * @param  \App\Models\SchoolYear  $model
     * @return void
     */
    public function creating(SchoolYear $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the SchoolYear "updated" event.
     *
     * @param  \App\Models\SchoolYear  $model
     * @return void
     */
    public function updating(SchoolYear $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the SchoolYear "deleted" event.
     *
     * @param  \App\Models\SchoolYear  $model
     * @return void
     */
    public function deleting(SchoolYear $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the SchoolYear "restored" event.
     *
     * @param  \App\Models\SchoolYear  $schoolYear
     * @return void
     */
    public function restored(SchoolYear $schoolYear)
    {
        //
    }

    /**
     * Handle the SchoolYear "force deleted" event.
     *
     * @param  \App\Models\SchoolYear  $schoolYear
     * @return void
     */
    public function forceDeleted(SchoolYear $schoolYear)
    {
        //
    }
}
