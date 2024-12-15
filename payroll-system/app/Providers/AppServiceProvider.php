<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Events\BeneficiaryUpdated;
use App\Listeners\UpdateTotalBeneficiaries;
use Illuminate\Support\Facades\Validator;
use App\Models\Municipality;


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
    public function boot()
    {
        Event::listen(
            BeneficiaryUpdated::class,
            [UpdateTotalBeneficiaries::class, 'handle']
        );
        \DB::listen(function ($query) {
            \Log::info($query->sql);
            \Log::info($query->bindings);
            \Log::info($query->time);
        });

        Validator::extend('belongs_to_province', function ($attribute, $value, $parameters, $validator) {
            $provinceID = $validator->getData()['provinceID'] ?? null;

            if (!$provinceID) {
                return false;
            }

            return Municipality::where('municipalityID', $value)
                ->where('provinceID', $provinceID)
                ->exists();
        });

    }
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(60)->by($request->user('admin')?->id ?: $request->ip());
        });

    }

}
