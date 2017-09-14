<?php

namespace LasseRafn\Fortnox\Builders;

use LasseRafn\Fortnox\Models\InvoicePayment;

class InvoicePaymentBuilder extends Builder
{
    protected $entity = 'InvoicePayments';
    protected $entity_singular = 'InvoicePayment';
    protected $model = InvoicePayment::class;
}
