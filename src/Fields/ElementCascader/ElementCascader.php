<?php

namespace Chang\Erp\Nova\Fields\ElementCascader;


use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Closure;

class ElementCascader extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'element-cascader';

    protected $resolveSelectedAttributeFunction = null;

    public function options($options)
    {
        return $this->withMeta(['options' => $options]);
    }

    public function eventKey(string $key)
    {
        return $this->withMeta(['eventKey' => $key]);
    }

    public function resolveSelectedAttribute(Closure $closureFunction = null)
    {
        $this->resolveSelectedAttributeFunction = $closureFunction;
        return $this;
    }

    /**
     * @param $attribute
     * @param $resource
     * @return mixed
     */
    protected function resolveSelected($attribute, $resource)
    {
        if (is_null($this->resolveSelectedAttributeFunction)) {
            return data_get($resource, str_replace('->', '.', $attribute));
        }
        return call_user_func($this->resolveSelectedAttributeFunction, $attribute, $resource);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        $this->withMeta(['formatValue' => $this->resolveSelected($resource, $attribute)]);

        return parent::resolveAttribute($resource, $attribute);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$model->{$attribute}()->getForeignKey()} = $request[$requestAttribute];
        }
    }


}