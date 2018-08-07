<?php

namespace App\Providers;

use GatherContent\LaravelFractal\LaravelFractalService;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;

class FractalServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('fractal.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('fractal', function ($app) {

            $inputkey = config('fractal.include_key');
            $includes = $app['request']->input($inputkey);

            $manager = new Manager();
            $manager->setSerializer(new ArraySerializer());

            if ($includes) {
                $manager->parseIncludes($includes);
            }

            return new LaravelFractalService($manager);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('fractal');
    }
}
