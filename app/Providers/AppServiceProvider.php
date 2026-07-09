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
/*
|--------------------------------------------------------------------------
| Detailed Explanation for Beginners
|--------------------------------------------------------------------------
| The following is a word-by-word, line-by-line explanation of the code
| inside AppServiceProvider.php, including what the AppServiceProvider is,
| what `extends` means, how inheritance works in PHP, and the purpose of
| the `boot` method and custom validation.
|
| This is aimed at helping a beginner understand every element.
*/

/*
|--------------------------------------------------------------------------
| What is AppServiceProvider?
|--------------------------------------------------------------------------
| In Laravel, a Service Provider is a central place to configure and register
| services, bind classes into the service container, and extend the framework's
| core functionality. Any logic that needs to happen on every request
| (or as part of app setup) often goes here.
|
| AppServiceProvider is one of several providers Laravel includes by default,
| specifically for your application-level bootstrapping.
*/

/*
|--------------------------------------------------------------------------
| What does 'extends' mean in PHP?
|--------------------------------------------------------------------------
| The 'extends' keyword in PHP is used to create a child class from a parent
| class. This is called "inheritance."
| 
| Syntax: 
|   class ChildClass extends ParentClass
|
| In this file:
|   class AppServiceProvider extends ServiceProvider
| Means: AppServiceProvider is a new class.
| It inherits (gets) everything (methods, properties) from the ServiceProvider
| class, which is defined by Laravel.
|
| This allows AppServiceProvider to override or use methods like 'register'
| and 'boot', customizing how your app starts up.
*/

/*
|--------------------------------------------------------------------------
| What is the 'boot' method?
|--------------------------------------------------------------------------
| The 'boot' method is called automatically by Laravel after all service
| providers have been registered. Think of it as the "startup script" for
| your app, a place to register events, custom validation, macros, etc.
|
| Code inside this method runs on every request, making it a perfect spot
| to set up application-wide functionality.
*/

/*
|--------------------------------------------------------------------------
| What is happening in this code?
|--------------------------------------------------------------------------
| 1. The Validator::extend() function is being called.
|      - Validator is Laravel's class for running and defining validation rules.
|      - The 'extend' method allows you to define a new validation rule
|        that can be used in your form validation code.
|      - Here, we're calling Validator::extend('ageLimit', ...);
|      - 'ageLimit' is the name of our new custom validation rule.
|      - The second argument is a closure (anonymous function)
|        which contains the logic for the rule.
|
| 2. Inside the closure:
|      - The closure receives these arguments:
|         $attribute  -> the name of the field being validated (e.g. 'age')
|         $value      -> the value the user submitted for that field (e.g. 22)
|         $parameters -> any extra rule parameters (not used here)
|         $validator  -> the validator instance itself (for adding errors)
|
| 3. (new ageLimit())->validate(...) runs the validate method from your
|     ageLimit Rule class. This is called "reusing logic" — so the rule logic lives
|     in one place (ageLimit Rule class), and both the class and the string version
|     of the validator use the same logic.
|      - (new ageLimit()) creates a new instance of the ageLimit rule class.
|      - ->validate(...) calls its validate function with:
|          $attribute: field name
|          $value: field value
|          an inline function (closure) to handle error messages
|
| 4. If validation fails (e.g. the user is too young/old), the rule calls
|     $validator->errors()->add($attribute, $message); which adds the validation
|     error to Laravel's error bag, so the user will see it in their form errors.
|
| 5. The closure always returns true.
|      - Why? By default, Laravel considers a rule failed if you return false,
|        but here, the error is already pushed onto the error bag using
|        $validator->errors()->add(), so we return true to avoid duplicate/generic
|        error messages.
|
| 6. Now, in your controllers, you can use 'ageLimit' as a validation rule using
|    a string: 'age' => 'required|ageLimit'
|
| 7. Alternative (commented out): Instead of reusing a Rule class, you could just
|    put custom validation logic inline using another closure (see Option B),
|    but reusing a Rule class is preferred — single point of logic.
*/

/*
| By understanding this structure, you can add any custom validation logic and
| register it globally for your whole Laravel app!
*/