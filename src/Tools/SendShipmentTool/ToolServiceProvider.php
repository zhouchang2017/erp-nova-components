<?php

namespace Chang\Erp\Nova\Tools\SendShipmentTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Nova::serving(function (ServingNova $event) {
            Nova::script('send-shipment-tool', __DIR__.'/dist/js/tool.js');
            Nova::style('send-shipment-tool', __DIR__.'/dist/css/tool.css');
        });
    }



    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
