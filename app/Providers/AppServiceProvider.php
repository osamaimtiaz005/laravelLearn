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
        // extend() passes 4 args: $attribute, $value, $parameters (array), $validator
        // NOT the same $fail Closure that ValidationRule::validate() expects
        Validator::extend('ageLimit', function ($attribute, $value, $parameters, $validator) {
            (new ageLimit())->validate($attribute, $value, function ($message) use ($attribute, $validator) {
                $validator->errors()->add($attribute, $message);
            });

            return true; // return true so Laravel does not add a duplicate generic error
        });

        // Option B: Inline closure (no separate Rule class needed)
        // Validator::extend('ageLimit', function ($attribute, $value, $parameters, $validator) {
        //     if ($value < 18) {
        //         return false;
        //     }
        //     if ($value > 100) {
        //         return false;
        //     }
        //     return true;
        // });
    }
}
