<?php

namespace Chang\Erp\Nova\Product\OptionsField;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class OptionsField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'product-options-field';

    public $showOnIndex = false;

    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
    }

    public function on(string $eventKey)
    {
        return $this->withMeta(['eventKey' => $eventKey]);
    }

    public function emit(string $eventKey)
    {
        return $this->withMeta(['emitKey' => $eventKey]);
    }

    public function api(string $url)
    {
        return $this->withMeta(['api' => $url]);
    }

    public function params(string $params)
    {
        return $this->withMeta(['params' => $params]);
    }

    public function origin($product)
    {
        return $this->withMeta(['origin' => $product]);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        $optionValues = null;
        if ($resource->exists) {
            $resource->loadMissing(['optionValues']);
            $optionValues = $resource->optionValues->groupBy('option_id');
        }
        $this->withMeta(['optionValues' => $optionValues]);
        return parent::resolveAttribute($resource, $attribute);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $data = collect($request[$requestAttribute]);
            /** @var Model $model */
            if ($model->exists) {
                // update
                $model->{$requestAttribute}()->sync($data);
            } else {
                // create
                $model::created(function ($model) use ($data, $requestAttribute) {
                    $model->{$requestAttribute}()->sync($data);
                });
            }
        }
    }


}
