<?php
namespace Chang\Erp\Nova\Fields\ElementBelongsTo;


use Laravel\Nova\Fields\BelongsTo;

class ElementBelongsTo extends BelongsTo
{
    public $component = 'element-belongs-to-field';

    public function eventKey(string $key)
    {
        return $this->withMeta(['eventKey' => $key]);
    }
}