<?php

namespace Chang\Erp\Nova\Product\AttributesField;

use Laravel\Nova\Fields\Field;

class AttributesField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'product-attributes-field';

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
}
