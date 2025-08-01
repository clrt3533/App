<?php

namespace App\Providers;

use App\Helpers\App\Traits\SetPaymentConfig;
use App\Helpers\App\Traits\SetSettingsConfig;
use App\Mail\App\Traits\SetMailConfig;
use App\Services\App\SmsSetting\Message91Services;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Exception;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        if (!$this->app->environment('production') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }
        $this->app->singleton(Message91Services::class,function (Application $app){
            $client = Http::withHeaders([
                'accept' => 'application/json',
                'authkey' => env('MSG91_AUTH_KEY', '238708A7BIRMsept5ba3c275'),
                'content-type' => 'application/json',
            ])->baseUrl('https://control.msg91.com/api/v5/flow');

            return new Message91Services($client);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        /*
         * Application locale defaults for various components
         *
         * These will be overridden by LocaleMiddleware if the session local is set
         */

        // setLocale for php. Enables ->formatLocalized() with localized values for dates
        setlocale(LC_TIME, config('app.locale_php'));

        // setLocale to use Carbon source locales. Enables diffForHumans() localized
        Carbon::setLocale(config('app.locale'));

        /*
         * Set the session variable for whether or not the app is using RTL support
         * For use in the blade directive in BladeServiceProvider
         */
        if (! app()->runningInConsole()) {
            if (config('locale.languages')[config('app.locale')][2]) {
                session(['lang-rtl' => true]);
            } else {
                session()->forget('lang-rtl');
            }
        }

        // Force SSL in production
        /*if ($this->app->environment() === 'production') {
            URL::forceScheme('https');
        }*/

        // Set the default template for Pagination to use the included Bootstrap 4 template
        // Custom Blade Directives

        /*
         * The block of code inside this directive indicates
         * the project is currently running in read only mode.
         */
        Blade::if('readonly', function () {
            return config('app.read_only');
        });

        /*
         * The block of code inside this directive indicates
         * the chosen language requests RTL support.
         */
        Blade::if('langrtl', function ($session_identifier = 'lang-rtl') {
            return session()->has($session_identifier);
        });

        try {
            SetMailConfig::new(true)
                ->clear()
                ->set();

            SetSettingsConfig::new(true)
                ->clear()
                ->set();
            SetPaymentConfig::new(true)
                ->clear()
                ->set();

        } catch (Exception $exception){

        }
    }
}
