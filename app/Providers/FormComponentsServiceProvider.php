<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FormComponentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Form::component('clLabel', 'admin.form_components.label', [ 'name' => null, 'size' => 2 ]);
        \Form::component('clText', 'admin.form_components.text', ['name', 'value' => null, 'attributes' => [] ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
