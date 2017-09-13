<?php

namespace LasseRafn\Fortnox\Builders;

use LasseRafn\Fortnox\Models\Order;

class OrderBuilder extends Builder
{
    protected $entity = 'Orders';
    protected $entity_singular = 'Order';
    protected $model = Order::class;
}
