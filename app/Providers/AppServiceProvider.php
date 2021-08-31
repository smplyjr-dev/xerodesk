<?php

namespace App\Providers;

use App\Models\Client\Attachment;
use App\Models\Client\Message;
use App\Models\Client\Session;
use App\Observers\Client\AttachmentObserver;
use App\Observers\Client\MessageObserver;
use App\Observers\Client\SessionObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // observer
        Attachment::observe(AttachmentObserver::class);
        Message::observe(MessageObserver::class);
        Session::observe(SessionObserver::class);

        // validations
        $this->image64Validation();
        $this->alphaUnderscoreValidation();
        $this->alphaSpaceValidation();
    }

    /**
     * Adds image64 validation rule and message to validator. 
     *
     * @return void
     */
    public function image64Validation()
    {
        // Create base64 image format validation
        Validator::extend('image64', function ($attribute, $value, $parameters, $validator) {
            $type = explode('/', explode(':', substr($value, 0, strpos($value, ';')))[1])[1];

            if (in_array($type, $parameters)) return true;
            return false;
        });

        // Image file type
        Validator::replacer('image64', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':values', join(", ", $parameters), $message);
        });
    }

    /**
     * Create validation for Alpha with Underscore
     * No trailing underscore are allowed and the first character must be an Alphabet
     *
     * @return void
     */
    public function alphaUnderscoreValidation()
    {
        Validator::extend('alpha_underscore', function ($attribute, $value, $parameters, $validator) {
            return preg_match("/^([A-Za-z]_?)+$/", $value);
        });
    }

    /**
     * Create validation for Alpha with Space
     * No trailing spaces are allowed and the first character must be an Alphabet
     *
     * @return void
     */
    public function alphaSpaceValidation()
    {
        Validator::extend('alpha_space', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[\pL\s]+$/u', $value);
        });
    }
}
