<?php

namespace Chang\Erp\Nova;

use Illuminate\Support\ServiceProvider;

class ErpNovaComponentServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->register('Chang\Erp\Nova\ElementUI\NovaElementsServiceProvider');
        $this->app->register('Chang\Erp\Nova\Fields\ElementBelongsTo\FieldServiceProvider');
        $this->app->register('Chang\Erp\Nova\Fields\ElementCascader\FieldServiceProvider');
        $this->app->register('Chang\Erp\Nova\Fields\ElementStepField\FieldServiceProvider');
        $this->app->register('Chang\Erp\Nova\Product\AttributesField\AttributesFieldServiceProvider');
        $this->app->register('Chang\Erp\Nova\Product\OptionsField\OptionsFieldServiceProvider');
        $this->app->register('Chang\Erp\Nova\Product\VariantResourceField\FieldServiceProvider');
        $this->app->register('Chang\Erp\Nova\Product\VariantsField\VariantsFieldServiceProvider');
        $this->app->register('Chang\Erp\Nova\Tools\SendShipmentTool\ToolServiceProvider');
    }
}