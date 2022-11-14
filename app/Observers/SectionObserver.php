<?php

namespace App\Observers;

use App\Models\Section;
use Illuminate\Support\Facades\Auth;

class SectionObserver
{
    /**
     * Handle the Section "created" event.
     *
     * @param  \App\Models\Section  $model
     * @return void
     */
    public function creating(Section $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the Section "updated" event.
     *
     * @param  \App\Models\Section  $model
     * @return void
     */
    public function updating(Section $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the Section "deleted" event.
     *
     * @param  \App\Models\Section  $model
     * @return void
     */
    public function deleting(Section $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the Section "restored" event.
     *
     * @param  \App\Models\Section  $section
     * @return void
     */
    public function restored(Section $section)
    {
        //
    }

    /**
     * Handle the Section "force deleted" event.
     *
     * @param  \App\Models\Section  $section
     * @return void
     */
    public function forceDeleted(Section $section)
    {
        //
    }
}
