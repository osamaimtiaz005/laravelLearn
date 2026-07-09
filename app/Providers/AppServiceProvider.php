<?php

namespace App\Providers;

use App\Rules\ageLimit;
use Illuminate\Support\Facades\Validator;
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
        /*
        |--------------------------------------------------------------------------
        | Custom Validation Rule — Service Provider (Approach 2)
        |--------------------------------------------------------------------------
        | Register the rule here once, then use it as a STRING anywhere:
        |   'age' => 'required|ageLimit'
        |
        | Laravel looks for validateAgeLimit() on Validator — extend() creates it.
        */

        // Option A: Reuse your existing Rule class (recommended — logic stays in one place)
        Validator::extend('ageLimit', function ($attribute, $value, $fail) {
            (new ageLimit())->validate($attribute, $value, $fail);
        });

        // Option B: Inline closure (no separate Rule class needed)
        // Validator::extend('ageLimit', function ($attribute, $value, $fail) {
        //     if ($value < 18) {
        //         $fail('You must be at least 18 years old to register.');
        //     }
        //     if ($value > 100) {
        //         $fail('You must be less than 100 years old to register.');
        //     }
        // });
    }
}
