<?php

namespace App\Observers;

use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;

class UserRoleObserver
{
    /**
     * Handle the UserRole "created" event.
     *
     * @param  \App\Models\UserRole  $model
     * @return void
     */
    public function creating(UserRole $model)
    {
        if (Auth::user()) {
            $model->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the UserRole "updated" event.
     *
     * @param  \App\Models\UserRole  $model
     * @return void
     */
    public function updating(UserRole $model)
    {
        if (Auth::user()) {
            $model->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the UserRole "deleted" event.
     *
     * @param  \App\Models\UserRole  $model
     * @return void
     */
    public function deleting(UserRole $model)
    {
        if (Auth::user()) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        }
    }

    /**
     * Handle the UserRole "restored" event.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return void
     */
    public function restored(UserRole $userRole)
    {
        //
    }

    /**
     * Handle the UserRole "force deleted" event.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return void
     */
    public function forceDeleted(UserRole $userRole)
    {
        //
    }
}
