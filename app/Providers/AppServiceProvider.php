<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        Validator::extend('before_or_equal_if_filled', function ($attribute, $value, $parameters, $validator) {
            $anotherField = $parameters[0];
            $anotherValue = $validator->getData()[$anotherField];

            if (!$anotherValue) {
                // If the other field is not filled, this field is valid
                return true;
            }

            return \Carbon\Carbon::parse($value)->lte(\Carbon\Carbon::parse($anotherValue));
        });

        Validator::replacer('before_or_equal_if_filled', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':other', $parameters[0], $message);
        });
    }
}
