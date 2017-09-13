<?php

namespace LasseRafn\Fortnox\Builders;

use LasseRafn\Fortnox\Models\Invoice;

class InvoiceBuilder extends Builder
{
    protected $entity = 'Invoices';
    protected $entity_singular = 'Invoice';
    protected $model = Invoice::class;
}
