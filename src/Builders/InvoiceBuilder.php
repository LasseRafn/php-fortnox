<?php

namespace LasseRafn\Fortnox\Builders;

use LasseRafn\Fortnox\Models\Invoice;

class InvoiceBuilder extends Builder
{
    protected $entity = 'Invoices';
    protected $model = Invoice::class;
}
