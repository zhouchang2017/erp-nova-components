<?php

namespace Chang\Erp\Nova\Product\VariantsField;

use Chang\Erp\Models\ProductVariant;
use Chang\Erp\Models\ProductVariantOptionValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class VariantsField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'product-variants-field';

    public $showOnIndex = false;

    public $showOnDetail = false;

    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
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

    public function origin($product)
    {
        return $this->withMeta(['origin' => $product]);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        $resource->loadMissing(['variants.optionValues']);
        $value = $resource->{$attribute};
        return $value;
    }


    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $data = collect($request[$requestAttribute]);

            /** @var Model $model */
            if ($model->exists) {
                DB::transaction(function () use ($data, $model) {
                    $this->removeDeletedVariants($data, $model);
                    $this->updateOrCreateVariant($data, $model);
                });

            } else {
                // create

                $model::created(function ($model) use ($data) {
                    DB::transaction(function () use ($data, $model) {
                        $this->updateOrCreateVariant($data, $model);
                    });
                });
            }
        }
    }

    protected function getVariantIds(Collection $data)
    {
        return $data->filter(function ($variant) {
            return key_exists('id', $variant);
        })->pluck('id');
    }

    protected function removeDeletedVariants(Collection $data, Model $model, $relationName = 'variants')
    {
        $model->{$relationName}()->pluck('id')->diff($data->pluck('id')->filter())
            ->each(function ($id) use ($model, $relationName) {
                if ($variant = $model->{$relationName}()->where('id', $id)->first()) {
                    info('产品更新,删除变体=>', $variant->toArray());
                    $variant->delete();
                }
            });
    }

    protected function updateOrCreateVariant(Collection $data, Model $model, $relationName = 'variants')
    {
        $data->each(function ($attribute) use ($model, $relationName) {
            // 创建或更新变体
            $variant = $model->{$relationName}()->updateOrCreate([
                'id' => optional($attribute)['id'],
            ], array_only($attribute, $model->{$relationName}()->getModel()->getFillable()));

            // 更新变体价格
            $this->updateVariantPrice($variant, optional($attribute)['price']);

            // 更新变体销售属性
            $this->syncVariantOptionValues($variant, $attribute['options']);

        });
    }

    protected function updateVariantPrice(ProductVariant $variant, $price = '0')
    {
        $variant->price()->updateOrCreate(['variant_id' => $variant->id], ['price' => $price]);
    }

    protected function syncVariantOptionValues(ProductVariant $variant, array $attribute)
    {
        collect($attribute)->tap(function ($options) use ($variant) {
            // 删除不存在的属性值
            /** @var Collection $options */
            $variant->optionValues()->pluck('id')->diff($options->pluck('id')->filter())->each(function ($optionValueId
            ) use ($variant) {
                $variantOptionValue = $variant->optionValues()->find($optionValueId);
                info('变体', $variant->toArray());
                info('删除变体属性', $variantOptionValue->toArray());
                optional($variantOptionValue)->delete();
            });

        })->each(function ($option) use ($variant) {
            // 可选属性值
            if (key_exists('value_id', $option) && !is_null($option['value_id'])) {
                // has id No need to update

                if ( !array_key_exists('id', $option)) {
                    $variantOptionValue = $variant->optionValues()->create([
                        'option_id' => $option['option_id'],
                        'value_id' => $option['value_id'],
                    ]);
                    info('创建可选属性！');
                    info('可选属性=>', $variantOptionValue->toArray());
                }
            } else {
                // 自定义属性

                $variantOptionValue = $variant->optionValues()->updateOrCreate(['id' => optional($option)['id']], [
                    'option_id' => $option['option_id'],
                ]);

                if ($variantOptionValue instanceof ProductVariantOptionValue) {
                    $this->fillOptionValueTranslation($variantOptionValue,
                        $option['translations'])->save();
                }

            }
        });

    }


    protected function fillOptionValueTranslation(ProductVariantOptionValue $variantOptionValue, array $translations)
    {
        // 更新属性名称
        return tap($variantOptionValue, function ($variantOptionValue) use ($translations) {
            collect($translations)->each(function ($item) use ($variantOptionValue) {
                $variantOptionValue->translateOrNew($item['locale_code'])->value = $item['value'];
            });
        });

    }


}
