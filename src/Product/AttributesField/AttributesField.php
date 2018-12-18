<?php

namespace Chang\Erp\Nova\Product\AttributesField;

use Chang\Erp\Models\ProductAttribute;
use Chang\Erp\Models\ProductAttributeValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class AttributesField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'product-attributes-field';

    public $showOnIndex = false;

    /**
     * @var null|Model
     */
    public $valueModel = null;

    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
    {
        parent::__construct($name, null, $resolveCallback);
        $this->valueModel = $attribute ?? ProductAttributeValue::class;
    }

    public function on(string $eventKey)
    {
        return $this->withMeta(['eventKey' => $eventKey]);
    }

    public function api(string $url)
    {
        return $this->withMeta(['api' => $url]);
    }

    public function params(string $params)
    {
        return $this->withMeta(['params' => $params]);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        if ($resource->exists) {
            $this->withMeta(['attributes' => ProductAttribute::whereTaxon($resource->taxon_id)->withTranslation()->get()]);
        };
        return $resource->attributeValuesTranslation;
    }


    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $data = collect($request[$requestAttribute]);
            /** @var Model $model */
            if ($model->exists) {
                // update
                $this->attributeValueUpdateOrCreate($data, $model);
            } else {
                // create
                $model::created(function ($model) use ($data) {
                    $this->attributeValueUpdateOrCreate($data, $model);
                });
            }
        }
    }

    protected function attributeValueUpdateOrCreate(Collection $data, Model $model)
    {
        $data->each(function ($attributeValue, $attributeId) use ($model) {

            collect($attributeValue)->each(function ($value, $locale) use ($attributeId, $model) {
                if ( !is_null($value['origin'])) {
                    $valueModel = $this->valueModel::findOrFail($value['origin']);
                    $valueModel->update([
                        'locale_code' => $locale,
                        'text_value' => $value['value'],
                        'attribute_id' => $attributeId,
                        'product_id' => $model->id,
                    ]);
                } else {
                    $this->valueModel::create([
                        'locale_code' => $locale,
                        'text_value' => $value['value'],
                        'attribute_id' => $attributeId,
                        'product_id' => $model->id,
                    ]);
                }
            });
        });
    }


}
