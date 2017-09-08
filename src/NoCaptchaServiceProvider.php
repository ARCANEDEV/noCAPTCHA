<?php namespace Arcanedev\NoCaptcha;

use Arcanedev\LaravelHtml\Contracts\FormBuilder;
use Arcanedev\Support\PackageServiceProvider as ServiceProvider;

/**
 * Class     NoCaptchaServiceProvider
 *
 * @package  Arcanedev\NoCaptcha
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptchaServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'no-captcha';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

        $this->registerConfig();
        $this->registerNoCaptcha();
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        parent::boot();

        $this->publishConfig();
        $this->registerFormMacros($this->app);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Contracts\NoCaptcha::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register NoCaptcha service.
     */
    private function registerNoCaptcha()
    {
        $this->singleton(Contracts\NoCaptcha::class, function($app) {
            /** @var  \Illuminate\Contracts\Config\Repository  $config */
            $config = $app['config'];

            return new NoCaptcha(
                $config->get('no-captcha.secret'),
                $config->get('no-captcha.sitekey'),
                $config->get('no-captcha.lang'),
                $config->get('no-captcha.attributes', [])
            );
        });
    }

    /**
     * Register Form Macros.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    private function registerFormMacros($app)
    {
        foreach ([FormBuilder::class, 'form'] as $alias) {
            if ($app->bound($alias)) {
                $app[$alias]->macro('captcha', function($name = null, array $attributes = []) use ($app) {
                    return $app[Contracts\NoCaptcha::class]->display($name, $attributes);
                });
            }
        }
    }
}
