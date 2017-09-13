<?php

namespace LasseRafn\Fortnox\Requests;

use LasseRafn\Fortnox\Models\Order;
use LasseRafn\Fortnox\Utils\RequestBuilder;

class OrderRequestBuilder extends RequestBuilder
{
    public function onlyCancelled()
    {
        $this->parameters['filter'] = 'cancelled';

        return $this;
    }

    public function onlyExpired()
    {
        $this->parameters['filter'] = 'expired';

        return $this;
    }

    public function onlyInvoiceCreated()
    {
        $this->parameters['filter'] = 'invoicecreated';

        return $this;
    }

    public function onlyInvoiceNotCreated()
    {
        $this->parameters['filter'] = 'invoicenotcreated';

        return $this;
    }

    /**
     * @param $guid
     *
     * @return \LasseRafn\Fortnox\Utils\Model|mixed|Order
     */
    public function find($guid)
    {
        return parent::find($guid);
    }
}
