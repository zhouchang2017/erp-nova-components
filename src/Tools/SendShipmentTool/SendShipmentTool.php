<?php

namespace Chang\Erp\Nova\Tools\SendShipmentTool;

use Laravel\Nova\ResourceTool;

class SendShipmentTool extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Send Shipment';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'send-shipment-tool';
    }
}
