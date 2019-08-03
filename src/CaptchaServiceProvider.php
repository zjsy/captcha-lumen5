<?php

namespace zjsy\CaptchaLumen5;

use zjsy\CaptchaLumen5\Captcha;
use Illuminate\Support\ServiceProvider;

/**
 * Class CaptchaServiceProvider
 * @package Mews\Captcha
 */
class CaptchaServiceProvider extends ServiceProvider {

    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        // register router
        //lumen 5.5 and +
        if(preg_match('/5\.[56789]\.\d+/', $this->app->version())){
            $this->app->router->get('captchaInfo[/{type}]', 'zjsy\CaptchaLumen5\LumenCaptchaController@getCaptchaInfo');
            $this->app->router->get('captcha/{type}/{captchaId}', 'zjsy\CaptchaLumen5\LumenCaptchaController@getCaptcha');
        }else{
            $this->app->get('captchaInfo[/{type}]', 'zjsy\CaptchaLumen5\LumenCaptchaController@getCaptchaInfo');
            $this->app->get('captcha/{type}/{captchaId}', 'zjsy\CaptchaLumen5\LumenCaptchaController@getCaptcha');
        }
        // import configuration files
        $this->app->configure('lumen-captcha');

        // Validator extensions
        $this->app['validator']->extend('captcha', function($attribute, $value, $parameters)
        {
            $captchaId=$parameters[0];
            return app('captcha')->checkCaptchaById($value,$captchaId);
        });

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge configs
        $this->mergeConfigFrom(
            __DIR__.'/../config/lumen-captcha.php', 'lumen-captcha'
        );

        // Bind captcha
        $this->app->bind('captcha', function($app)
        {
            return new Captcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
    }

}
