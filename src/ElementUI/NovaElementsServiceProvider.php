<?php

namespace Chang\Erp\Nova\ElementUI;

use Laravel\Nova\Nova;
use Illuminate\Support\ServiceProvider;

class NovaElementsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/dist/fonts' => public_path('fonts'),
            ], 'public');
        }

        Nova::serving(function ($event) {
            Nova::script('nova-element-ui', __DIR__.'/dist/js/nova-element-ui.js');
            Nova::style('nova-element-ui', __DIR__.'/dist/css/nova-element-ui.css');
        });

    }

}
