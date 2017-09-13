<?php

namespace LasseRafn\Fortnox\Requests;

use LasseRafn\Fortnox\Models\Customer;
use LasseRafn\Fortnox\Utils\RequestBuilder;

class CustomerRequestBuilder extends RequestBuilder
{
    public function onlyActive()
    {
        $this->parameters['filter'] = 'active';

        return $this;
    }

    public function onlyInactive()
    {
        $this->parameters['filter'] = 'inactive';

        return $this;
    }

    /**
     * @param $guid
     *
     * @return \LasseRafn\Fortnox\Utils\Model|mixed|Customer
     */
    public function find($guid)
    {
        return parent::find($guid);
    }
}
