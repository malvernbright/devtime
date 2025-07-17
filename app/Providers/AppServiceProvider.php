<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\TimeHelper;

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
        // Register custom Blade directives for time formatting
        Blade::directive('duration', function ($expression) {
            return "<?php echo App\Helpers\TimeHelper::formatDuration($expression); ?>";
        });

        Blade::directive('durationShort', function ($expression) {
            return "<?php echo App\Helpers\TimeHelper::formatDurationShort($expression); ?>";
        });
    }
}
