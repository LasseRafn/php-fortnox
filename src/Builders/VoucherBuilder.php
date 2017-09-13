<?php

namespace LasseRafn\Fortnox\Builders;

use LasseRafn\Fortnox\Models\Voucher;

class VoucherBuilder extends Builder
{
    protected $entity = 'Vouchers';
    protected $entity_singular = 'Voucher';
    protected $model = Voucher::class;
}
