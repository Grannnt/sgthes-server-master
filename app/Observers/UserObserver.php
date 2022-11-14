<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $model
     * @return void
     */
    public function creating(User $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $model
     * @return void
     */
    public function updating(User $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $model
     * @return void
     */
    public function deleting(User $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
