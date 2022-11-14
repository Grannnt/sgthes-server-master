<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\UserRole::observe(\App\Observers\UserRoleObserver::class);
        \App\Models\SchoolYear::observe(\App\Observers\SchoolYearObserver::class);
        \App\Models\Section::observe(\App\Observers\SectionObserver::class);
        \App\Models\Subject::observe(\App\Observers\SubjectObserver::class);
        \App\Models\Student::observe(\App\Observers\StudentObserver::class);
        \App\Models\AnswerKey::observe(\App\Observers\AnswerKeyObserver::class);
        \App\Models\StudentAnswerSheet::observe(\App\Observers\StudentAnswerSheetObserver::class);
        \App\Models\StudentAnswerSheetResult::observe(\App\Observers\StudentAnswerSheetResultObserver::class);
    }
}
