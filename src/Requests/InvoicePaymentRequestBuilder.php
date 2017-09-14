<?php

namespace LasseRafn\Fortnox\Requests;

use LasseRafn\Fortnox\Models\Invoice;
use LasseRafn\Fortnox\Models\InvoicePayment;
use LasseRafn\Fortnox\Utils\RequestBuilder;

class InvoicePaymentRequestBuilder extends RequestBuilder
{
    /**
     * @param $guid
     *
     * @return \LasseRafn\Fortnox\Utils\Model|mixed|InvoicePayment
     */
    public function find($guid)
    {
        return parent::find($guid);
    }
}
