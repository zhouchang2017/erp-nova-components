<?php

namespace Chang\Erp\Nova\Order\OrderItemsTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OrderItemsToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Nova::serving(function (ServingNova $event) {
            Nova::script('order-items-tool', __DIR__.'/dist/js/tool.js');
            Nova::style('order-items-tool', __DIR__.'/dist/css/tool.css');
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
