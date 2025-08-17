<?php

namespace App\Providers;

use App\Models\Task;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
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

        // Paksa https di production/ngrok
        (app()->environment('production') || Str::contains(request()->getHost(), 'ngrok'))
            ? URL::forceScheme('https')
            : null;

        // Reset harian jika jam 6 pagi
        now()->hour == 6
            ? Task::where('type', 'harian')->update(['status' => 'belum_dimulai'])
            : null;
        }
}
