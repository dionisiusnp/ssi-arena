<?php

namespace App\Providers;

use App\Enums\QuestEnum;
use App\Models\Activity;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin.partials.navbar', function ($view) {
            $testingActivities = Activity::with('claimedBy')
                                         ->where('status', QuestEnum::TESTING->value)
                                         ->orderByDesc('updated_at')
                                         ->get();
            $view->with('testingActivities', $testingActivities);
        });
    }
}
