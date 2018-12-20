<?php

namespace Chang\Erp\Nova\Product\VariantResourceField;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class VariantResourceField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'variant-resource-field';

    /**
     * The field's relation resource.
     *
     * @var string
     */
    protected $relationResource = null;


    /**
     * The field's selected relation resources marked field attribute .
     *
     * @var string
     */
    protected $relationResourcePrimaryKey = 'id';


    protected $relationResourceId = null;

    public $showOnIndex = false;


    public function typeId($id)
    {
        $this->relationResourceId = $id;
        return $this;
    }


    public function relationResources(string $model)
    {
        $this->relationResource = Str::plural(Str::snake(class_basename($model), '-'));
        return $this;
    }

    public function relationResourceOnChange(string $eventName)
    {
        return $this->withMeta(['relationResourceOnChange' => $eventName]);
    }

    public function relationResourceOnChangeValue(string $key)
    {
        return $this->withMeta(['relationResourceOnChangeValue' => $key]);
    }

    public function relationResourceIdOnChange(string $eventName)
    {
        return $this->withMeta(['relationResourceIdOnChange' => $eventName]);
    }

    public function relationResourceIdOnChangeValue(string $key)
    {
        return $this->withMeta(['relationResourceIdOnChangeValue' => $key]);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        $resource->{$attribute}->loadMissing('variant');
        return $resource->{$attribute};
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $data = collect($request[$requestAttribute]);
            if ($model->exists) {
                // update
                // update updated items
                DB::transaction(function () use ($data, $model, $attribute) {
                    $originTracks = $this->getOriginItems($data)->map(function ($item) use ($model, $attribute) {
                        return tap($model->{$attribute}()->where('id', $item['id'])->first(),
                            function ($itemInstance) use ($item) {
                                if ($itemInstance) {
                                    $class = get_class($itemInstance);
                                    $itemInstance->update(array_only($item, app($class)->getFillable()));
                                }
                            });
                    });
                    // remove deleted items
                    $this->removeItems($originTracks->pluck('id'), $model, $attribute);
                    // add new items
                    $this->getAddItems($data)->map(function ($item) use ($model, $attribute) {
                        return $model->{$attribute}()->create($item);
                    });
                });

            } else {
                // create
                $model::created(function ($model) use ($data, $attribute) {
                    DB::transaction(function () use ($data, $model, $attribute) {
                        $this->getAddItems($data)->map(function ($item) use ($model, $attribute) {
                            return $model->{$attribute}()->create($item);
                        });
                    });
                });
            }
        }
    }

    protected function getOriginItems(Collection $collection)
    {
        return $collection->filter(function ($item) {
            return !is_null($item['id']);
        });
    }

    protected function getAddItems(Collection $collection)
    {
        return $collection->filter(function ($item) {
            return is_null($item['id']);
        });
    }

    protected function removeItems($remainingIds, $model, $attribute)
    {
        $items = $model->{$attribute};
        $items->pluck('id')->diff($remainingIds)->each(function ($id) use ($items) {
            if ($item = $items->where('id', $id)->first()) {
                $item->delete();
            }
        });
        return $remainingIds;
    }


    /**
     * Get additional meta information to merge with the element payload.
     *
     * @return array
     */
    public function meta()
    {
        return array_merge(parent::meta(), [
            'relationResource' => $this->relationResource,
            'relationResourceId' => $this->relationResourceId,
        ]);
    }
}
