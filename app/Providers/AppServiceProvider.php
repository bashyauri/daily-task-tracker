<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        RateLimiter::for('login', function (Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Too many login attempts. Please try again later.',
                ], 429);
            } else {
                return [
                    Limit::perMinute(100)->by($request->ip())->response(function() {
                        return redirect()->back()->withErrors([
                            'email' => 'Too many login attempts from this IP. Please try again later.',
                        ]);
                    }),
                    Limit::perMinute(5)->by(strtolower($request->input('email')))->response(function() {
                        return redirect()->back()->withErrors([
                            'email' => 'Too many login attempts for this email. Please try again later.',
                        ]);
                    }),
                ];
            }
        });
        RateLimiter::for('password-reset-requests', function (Request $request) {
            return [
                Limit::perHour(5)->by($request->ip()),
                Limit::perHour(5)->by(strtolower($request->input('email')))
            ];
        });
        RateLimiter::for('password-reset', function (Request $request) {
            return [
                Limit::perHour(5)->by($request->ip()),
                Limit::perHour(5)->by(strtolower($request->input('email')))
            ];
        });
        Password::defaults(function () {
            if (config('app.env') === 'local') {
                return Password::min(4);
            }
            return Password::min(8)->mixedCase()->uncompromised()->letters()->numbers()->symbols();
        });
    }
}
