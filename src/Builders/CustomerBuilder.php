<?php

namespace LasseRafn\Fortnox\Builders;

use LasseRafn\Fortnox\Models\Customer;

class CustomerBuilder extends Builder
{
    protected $entity = 'Customers';
    protected $entity_singular = 'Customer';
    protected $model = Customer::class;
}
