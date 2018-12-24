<?php

namespace Chang\Erp\Nova\Order\OrderItemsTool;

use Laravel\Nova\ResourceTool;

class OrderItemsTool extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return '出货单';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'order-items-tool';
    }

    public function expend($resource)
    {
        return $this->withMeta(['expend' => $resource]);
    }

    public function slot(string $slot, string $canShow = 'SHIPPED')
    {
        return $this->withMeta(['slot' => $slot, 'canShow' => $canShow]);
    }
}
