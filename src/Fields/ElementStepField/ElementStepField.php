<?php

namespace Chang\Erp\Nova\Fields\ElementStepField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ElementStepField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'element-step-field';


    public $default = null;


    public function defaultValue($value)
    {
        $this->default = $value;
        return $this;
    }


    public function options(array $options)
    {
        return $this->withMeta(['options' => $options]);
    }

    public function failOption(array $failOption)
    {
        return $this->withMeta(['failOption' => $failOption]);
    }

    /*
     * 供应商提交审核 to-review
     * */
    public function slot(string $slot, string $canStatus = 'UN_COMMIT')
    {
        return $this->withMeta(['slot' => $slot, 'canStatus' => $canStatus]);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        $data = parent::resolveAttribute($resource, $attribute);
        return empty($data) ? $this->default : $data;
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = $request[$requestAttribute];
        }
    }
}
