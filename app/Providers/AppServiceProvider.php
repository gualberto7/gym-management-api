<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Builder::macro('allowedSorts', function ($allowedSorts) {
            return $this->when(request()->filled('sort'), function ($query) use ($allowedSorts) {
                $sortField = request()->input('sort');
                $sortDirection = 'asc';

                if (Str::of($sortField)->startsWith('-')) {
                    $sortDirection = 'desc';
                    $sortField = Str::of($sortField)->substr(1);
                }

                if (!in_array($sortField, $allowedSorts)) {
                    abort(400, 'Invalid sort field');
                }

                return $query->orderBy($sortField, $sortDirection);
            });
        });
    }
}
